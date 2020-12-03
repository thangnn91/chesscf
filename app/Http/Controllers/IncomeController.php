<?php

namespace App\Http\Controllers;

use Auth;
use App\Admin;
use App\Rules\MoneyRequire;
use Illuminate\Http\Request;
use App\Utility\LogActivity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use DateTime,
    DatePeriod,
    DateInterval;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->query->all();
        $config = DB::table('dbo_config')->where('type', Config::get('constants.income_type'))->get()->toArray();
        $data_back = array();
        if (!$params || !count($params)) {
            $income = DB::table('dbo_income')->join('dbo_config', 'dbo_income.config_id', '=', 'dbo_config.id')
                ->select('dbo_income.*', 'dbo_config.name')
                ->orderBy('date_original', 'DESC')
                ->get()->toArray();
        } else {
            $query = DB::table('dbo_income')->join('dbo_config', 'dbo_income.config_id', '=', 'dbo_config.id')
                ->select('dbo_income.*', 'dbo_config.name');
            if (isset($params['start']) && $params['start']) {
                $query->where('dbo_income.date_original', '>=', date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $params['start']))));
                $data_back['start'] = $params['start'];
            }
            if (isset($params['end']) && $params['end']) {
                $query->where('dbo_income.date_original', '<=', date('Y-m-d 23:59:00', strtotime(str_replace('/', '-', $params['end']))));
                $data_back['end'] = $params['end'];
            }
            if (isset($params['income_purpose']) && $params['income_purpose']) {
                $query->where('dbo_income.config_id', '=', $params['income_purpose']);
                $data_back['income_purpose'] = $params['income_purpose'];
            }
            $income =  $query->orderBy('date_original', 'DESC')->get()->toArray();
        }

        return view('admin/income')->with(compact('config', 'income', 'data_back'));
    }

    public function create()
    {
        $config = DB::table('dbo_config')->where('type', Config::get('constants.income_type'))->get()->toArray();
        return view('admin/create_income')->with(compact('config'));
    }

    public function edit($id = null)
    {
        if ($id && $id > 0) {
            $income_detail = DB::table('dbo_income')->where('id', $id)->first();
            if (!$income_detail)
                return redirect()->route('create_income.admin');
            $config = DB::table('dbo_config')->where('type', Config::get('constants.income_type'))->get()->toArray();
            return view('admin/edit_income')->with(compact('config', 'income_detail'));
        }
        return redirect()->route('create_income.admin');
    }

    public function save_income(Request $request)
    {
        $this->validate(
            $request,
            [
                'amount' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $price = (int) preg_replace("/[^0-9.]/", "", $value);
                        if ($price <= 0) {
                            return $fail('Vui lòng nhập số tiền thu');
                        }
                    }
                ],
                'income_purpose' => 'required',
                'income_date' => 'required'
            ],
            [
                'income_purpose.required' => 'Vui lòng chọn loại khoản thu',
                'income_date.required' => 'Vui lòng chọn ngày thu'
            ]
        );
        $config = DB::table('dbo_config')->where('type', Config::get('constants.income_type'))
            ->where('id', $request->income_purpose)->first();

        if (!$config)
            return response()->json([
                'statusCode' => -1,
                'modal' => true,
                'message' => 'Loại hình khoản thu không hợp lệ'
            ]);

        if ($request->edit_id  && $request->edit_id > 0) {
            return response()->json([
                'statusCode' => -1,
                'modal' => true,
                'message' => 'Chức năng đang được xây dựng',
                'reload' => true
            ]);
        }

        $id = DB::table('dbo_income')->insertGetId([
            'config_id' =>  $request->income_purpose,
            'amount' => (int) preg_replace("/[^0-9.]/", "", $request->amount),
            'note' => !$request->note ? $config->name : ($config->name . '. Nội dung: ' . $request->note),
            'date_string' => $request->income_date,
            'date_original' => date('Y-m-d 12:00:00', strtotime(str_replace('/', '-',  $request->income_date))),
            'created_user' => \Auth::guard('admin')->user()->username,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json([
            'statusCode' => 200,
            'redirect' => route('income.admin'),
            'modal' => true,
            'message' => 'Thêm khoản thu thành công',
            'reload' => true
        ]);
    }

    public function delete(Request $request)
    {
        try {
            DB::table('dbo_income')->where('id', $request->id)->delete();
            return response()->json("1");
        } catch (\Exception $ex) {
            return response()->json("-99");
        }
    }

    public function collect_income(Request $request)
    {
        $daily_income = 0;
        if (!$request->from_date && !$request->to_date) {
            $request_date = date("d/m/Y");
            $daily_income = DB::table('dbo_order')->where('created_at', '>=', date("Y-m-d 00:00:00"))
                ->where('created_at', '<=', date("Y-m-d 23:59:59"))->sum('grand_amount');

            if ($daily_income > 0) {
                $this->collect_process(date('Y-m-d 23:00:00', strtotime(str_replace('/', '-',  $request_date))), $daily_income);
            }
        } else {
            if (!$request->from_date || !$request->to_date) {
                return response()->json([
                    'statusCode' => -1,
                    'message' => 'Vui lòng chọn ngày bắt đầu và ngày kết thúc để tổng hợp',
                ]);
            }
            $from_date = new DateTime(date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $request->from_date))));
            $to_date = new DateTime(date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $request->to_date))));

            $diff_days =  $from_date->diff($to_date)->format('%a');;
            //dd(date_add($from_date, date_interval_create_from_date_string('23 hours'))->format('d/m/Y H:i:s'));
            if ($from_date > $to_date) {
                return response()->json([
                    'statusCode' => -1,
                    'message' => 'Ngày bắt đầu phải nhỏ hơn ngày kết thúc',
                ]);
            } else if ($diff_days == 0) {
                $end_of_day = date_add($from_date, date_interval_create_from_date_string('23 hours'));
                $daily_income = DB::table('dbo_order')->where('created_at', '>=', $from_date)
                    ->where('created_at', '<=', $end_of_day->format('Y-m-d H:i:s'))->sum('grand_amount');
                if ($daily_income > 0) {
                    $this->collect_process($end_of_day, $daily_income);
                }
            } else if ($diff_days <= 31) {
                $daterange = new DatePeriod($from_date, new DateInterval('P1D'), $to_date);
                foreach ($daterange as $date) {
                    $daily_income = DB::table('dbo_order')->where('created_at', '>=', $date->format("Y-m-d 00:00:00"))
                        ->where('created_at', '<=', $date->format("Y-m-d 23:59:59"))->sum('grand_amount');
                    if ($daily_income > 0) {
                        $this->collect_process(date_add($date, date_interval_create_from_date_string('23 hours')), $daily_income);
                    }
                }
            } else
                return response()->json([
                    'statusCode' => -1,
                    'message' => 'Bạn chỉ có thể tổng hợp tối đa 31 ngày',
                ]);
        }

        return response()->json([
            'statusCode' => 200,
            'redirect' => route('expense.admin'),
            'modal' => true,
            'message' => 'Tổng hợp thành công',
            'reload' => true
        ]);
    }
    private function collect_process($date, $money)
    {
        $date_string = $date->format('d/m/Y');
        $collect_data = DB::table('dbo_income')->where('date_string', $date_string)->where('config_id', Config::get('constants.daily_income'))->first();
        if (!$collect_data) {
            $id = DB::table('dbo_income')->insertGetId([
                'config_id' =>  Config::get('constants.daily_income'),
                'amount' => $money,
                'note' => "Tổng hợp doanh thu cuối ngày tự động",
                'date_string' => $date_string,
                'date_original' =>  $date->format('Y-m-d H:i:s'),
                'created_user' => \Auth::guard('admin')->user()->username,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            LogActivity::add_log(\Auth::guard('admin')->user()->username, "Tạo tổng hợp doanh thu cuối ngày tự động", "id khoản thu: $id");
        } else if ($money !== $collect_data->amount) {
            DB::table('dbo_income')->where('id', $collect_data->id)->update([
                'amount' => $money,
                'updated_user' => \Auth::guard('admin')->user()->username,
            ]);
            $new_data = DB::table('dbo_income')->where('id', $collect_data->id)->first();
            LogActivity::add_log(\Auth::guard('admin')->user()->username, "Sửa tổng hợp doanh thu cuối ngày tự động", json_encode($collect_data), json_encode($new_data));
        }
    }
}
