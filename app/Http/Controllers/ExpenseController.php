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

class ExpenseController extends Controller
{
    public function expense(Request $request)
    {
        $params = $request->query->all();
        $config = DB::table('dbo_config')->where('type', Config::get('constants.expense_type'))->get()->toArray();
        $user =  DB::table('dbo_admin')->where('active', 1)->get()->toArray();
        if (!$params || !count($params)) {
            $expense = DB::table('dbo_expense')->join('dbo_config', 'dbo_expense.config_id', '=', 'dbo_config.id')
                ->leftJoin('dbo_admin', 'dbo_expense.user_id', '=', 'dbo_admin.id')
                ->select('dbo_expense.*', 'dbo_admin.username', 'dbo_config.name')
                ->orderBy('date_original', 'DESC')
                ->get()->toArray();
            return view('admin/expense')->with(compact('expense', 'config', 'user'));
        } else {
            $data_back = array();
            $query = DB::table('dbo_expense')->join('dbo_config', 'dbo_expense.config_id', '=', 'dbo_config.id')
                ->leftJoin('dbo_admin', 'dbo_expense.user_id', '=', 'dbo_admin.id')->select('dbo_expense.*', 'dbo_admin.username', 'dbo_config.name');
            if (isset($params['start']) && $params['start']) {
                $query->where('dbo_expense.date_original', '>=', date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $params['start']))));
                $data_back['start'] = $params['start'];
            }
            if (isset($params['end']) && $params['end']) {
                $query->where('dbo_expense.date_original', '<=', date('Y-m-d 23:59:00', strtotime(str_replace('/', '-', $params['end']))));
                $data_back['end'] = $params['end'];
            }
            if (isset($params['expense_user']) && $params['expense_user']) {
                $query->where('dbo_expense.user_id', '=', $params['expense_user']);
                $data_back['expense_user'] = $params['expense_user'];
            }
            if (isset($params['expense_purpose']) && $params['expense_purpose']) {
                $query->where('dbo_expense.config_id', '=', $params['expense_purpose']);
                $data_back['expense_purpose'] = $params['expense_purpose'];
            }
            $expense =  $query->orderBy('date_original', 'DESC')->get()->toArray();
            return view('admin/expense')->with(compact('expense', 'config', 'user', 'data_back'));
        }
    }


    public function create()
    {
        $config = DB::table('dbo_config')->where('type', Config::get('constants.expense_type'))->get()->toArray();
        $user =  DB::table('dbo_admin')->where('active', 1)->get()->toArray();
        return view('admin/create_expense')->with(compact('config', 'user'));
    }

    public function edit($id = null)
    {
        if ($id && $id > 0) {
            $expense_detail = DB::table('dbo_expense')->where('id', $id)->first();
            if (!$expense_detail)
                return redirect()->route('create_expense.admin');
            $config = DB::table('dbo_config')->where('type', Config::get('constants.expense_type'))->get()->toArray();
            $user =  DB::table('dbo_admin')->where('active', 1)->get()->toArray();
            return view('admin/edit_expense')->with(compact('config', 'user', 'expense_detail'));
        }
        return redirect()->route('create_expense.admin');
    }

    public function save_expense(Request $request)
    {
        $this->validate(
            $request,
            [
                'amount' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $price = (int) preg_replace("/[^0-9.]/", "", $value);
                        if ($price <= 0) {
                            return $fail('Vui lòng nhập số tiền chi');
                        }
                    }
                ],
                'expense_purpose' => 'required',
                'expense_date' => 'required',
                'expense_user' => 'required'
            ],
            [
                'amount.required' => 'Vui lòng nhập số tiền chi',
                'expense_purpose.required' => 'Vui lòng chọn mục đích chi',
                'expense_date.required' => 'Vui lòng chọn ngày chi',
                'expense_user.required' => 'Vui lòng chọn người chi'
            ]
        );

        if ($request->edit_id  && $request->edit_id > 0) {
            $old_data = DB::table('dbo_expense')->where('id', $request->edit_id)->first();
            DB::table('dbo_expense')->where('id', $request->edit_id)->update([
                'config_id' => $request->expense_purpose,
                'amount' => (int) preg_replace("/[^0-9.]/", "", $request->amount),
                'note' => $request->note,
                'user_id' => $request->expense_user,
                'date_string' => $request->expense_date,
                'date_original' =>  date('Y-m-d 12:00:00', strtotime(str_replace('/', '-',  $request->expense_date))),
                'is_refund' => $request->is_refund === 'on' ? true : false,
                'updated_user' => \Auth::guard('admin')->user()->username,
            ]);
            $new_data = DB::table('dbo_expense')->where('id', $request->edit_id)->first();
            LogActivity::add_log(\Auth::guard('admin')->user()->username, "Sửa khoản chi", json_encode($old_data), json_encode($new_data));
            return response()->json([
                'statusCode' => 200,
                'redirect' => route('expense.admin'),
                'modal' => true,
                'message' => 'Sửa khoản chi thành công',
                'reload' => true
            ]);
        }
        $id = DB::table('dbo_expense')->insertGetId([
            'config_id' => $request->expense_purpose,
            'amount' => (int) preg_replace("/[^0-9.]/", "", $request->amount),
            'note' => $request->note,
            'user_id' => $request->expense_user,
            'date_string' => $request->expense_date,
            'date_original' =>  date('Y-m-d 12:00:00', strtotime(str_replace('/', '-',  $request->expense_date))),
            'created_user' => \Auth::guard('admin')->user()->username,
            'is_refund' => $request->is_refund === 'on' ? true : false,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        //Ghi log
        LogActivity::add_log(\Auth::guard('admin')->user()->username, "Tạo mới khoản chi", "id khoản chi: $id");
        return response()->json([
            'statusCode' => 200,
            'redirect' => route('expense.admin'),
            'modal' => true,
            'message' => 'Thêm khoản chi thành công',
            'reload' => true
        ]);
    }

    public function delete(Request $request)
    {
        try {
            DB::table('dbo_expense')->where('id', $request->id)->delete();
            return response()->json("1");
        } catch (\Exception $ex) {
            return response()->json("-99");
        }
    }

    public function refund(Request $request)
    {
        try {
            DB::table('dbo_expense')->where('id', $request->id)->update([
                'is_refund' => true,
            ]);
            return response()->json("1");
        } catch (\Exception $ex) {
            return response()->json("-99");
        }
    }
}
