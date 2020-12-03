<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Admin;
use App\Utility\StringHelper;
use App\Utility\CookieHelper;

class AdminController extends Controller
{
    public function index()
    {
        $grouped = DB::table('dbo_expense')
            ->join('dbo_admin', 'dbo_expense.user_id', '=', 'dbo_admin.id')
            ->where('dbo_expense.date_original', '>=', date('Y-m-01 00:00:00'))
            ->where('dbo_expense.date_original', '<=', date('Y-m-t 23:59:59'))
            ->select('dbo_admin.username as name', DB::raw('sum(amount) as y'))
            ->groupBy('dbo_expense.user_id')
            ->get()->toArray();

        $grouped2 = DB::table('dbo_expense')
            ->join('dbo_config', 'dbo_expense.config_id', '=', 'dbo_config.id')
            ->where('dbo_expense.date_original', '>=', date('Y-m-01 00:00:00'))
            ->where('dbo_expense.date_original', '<=', date('Y-m-t 23:59:59'))
            ->select('dbo_config.name as name', DB::raw('sum(amount) as y'))
            ->groupBy('dbo_expense.config_id')
            ->get()->toArray();
        return view('admin/index')->with(compact('grouped', 'grouped2'));
    }

    public function monthlyReport(Request $request)
    {
        if ($request->type == 1) {
            $grouped = DB::table('dbo_expense')
                ->join('dbo_admin', 'dbo_expense.user_id', '=', 'dbo_admin.id')
                ->where('dbo_expense.date_original', '>=', date("Y-$request->month-01 00:00:00"))
                ->where('dbo_expense.date_original', '<=', date("Y-$request->month-t 23:59:59"))
                ->select('dbo_admin.username as name', DB::raw('sum(amount) as y'))
                ->groupBy('dbo_expense.user_id')
                ->get()->toArray();
            return response()->json(['ResponseCode' => 1, 'Description' => 'Ok', 'Data' => $grouped]);
        }
        $grouped2 = DB::table('dbo_expense')
            ->join('dbo_config', 'dbo_expense.config_id', '=', 'dbo_config.id')
            ->where('dbo_expense.date_original', '>=', date("Y-$request->month-01 00:00:00"))
            ->where('dbo_expense.date_original', '<=', date("Y-$request->month-t 23:59:59"))
            ->select('dbo_config.name as name', DB::raw('sum(amount) as y'))
            ->groupBy('dbo_expense.config_id')
            ->get()->toArray();
        return response()->json(['ResponseCode' => 1, 'Description' => 'Ok', 'Data' => $grouped2]);
    }

    public function config()
    {
        $configs = DB::table('dbo_config')->where('active', 1)->get()->toArray();
        $configs =  json_encode($configs);
        return view('admin/config')->with(compact('configs'));
    }
    public function save_config(Request $request)
    {
        DB::table('dbo_config')
            ->update([
                'active' => 0
            ]);
        if ($request->expense) {
            DB::table('dbo_config')->whereIn('name', explode(",", $request->expense))->update(array('active' => 1));
        }
        if ($request->income) {
            DB::table('dbo_config')->whereIn('name', explode(",", $request->income))->update(array('active' => 1));
        }
        if ($request->system_config) {
            DB::table('dbo_config')->whereIn('name', explode(",", $request->system_config))->update(array('active' => 1));
        }

        return response()->json(['ResponseCode' => 1, 'Description' => 'Lưu dữ liệu thành công']);
    }

    public function menu()
    {
        $treeJson = "";
        $menus = DB::table('dbo_menu')->orderBy('index', 'ASC')->get()->toArray();
        if (count($menus)) {
            $menus = json_decode(json_encode($menus), true);
            $tree = $this->buildTree($menus);
            $treeJson = json_encode($tree);
        }
        return view('admin/menu')->with(compact('treeJson'));
    }

    public function saveMenu(Request $request)
    {
        //return response()->json(['ResponseCode' => -1, 'Description' => $request->menu_data]);
        $menu_array = json_decode($request->menu_data, true);
        $menu_array_after = $this->get_elements($menu_array);

        try {
            DB::beginTransaction();
            foreach ($menu_array_after as $item) {
                if (DB::table('dbo_menu')->where('id', $item['id'])->first()) {
                    $showhome = 0;
                    if (isset($item['showhome']) && ($item['showhome'] == "1" || $item['showhome'] == 'on')) {
                        $showhome = 1;
                    }
                    DB::table('dbo_menu')->where('id', $item['id'])->limit(1)
                        ->update([
                            'parentid' => isset($item['parentid']) ? $item['parentid'] : null,
                            'text' => $item['text'],
                            'href' => $item['href'],
                            'title' => $item['title'],
                            'index' => $item['index'],
                            'icon' => (isset($item['icon']) && $item['icon'] !== 'empty') ? $item['icon'] : null,
                            'showhome' => $showhome
                        ]);
                } else {
                    $showhome = 0;
                    if (isset($item['showhome']) && ($item['showhome'] == "1" || $item['showhome'] == 'on'))
                        $showhome = 1;
                    DB::table('dbo_menu')->insert([
                        'id' => $item['id'],
                        'parentid' => isset($item['parentid']) ? $item['parentid'] : null,
                        'text' => $item['text'],
                        'href' => $item['href'],
                        'title' => $item['title'],
                        'index' => $item['index'],
                        'icon' => (isset($item['icon']) && $item['icon'] !== 'empty') ? $item['icon'] : null,
                        'showhome' => $showhome
                    ]);
                }
            }
            DB::commit();
        } catch (Exception $ex) {
            Log::channel('daily')->info("Ex: " . $ex->getMessage());
            DB::rollback();
        }
        return response()->json(['ResponseCode' => 1, 'Description' => 'Ok']);
    }

    function get_elements($array)
    {
        $result = array();
        foreach ($array as $key => $row) {
            $result[] = $row;
            if (isset($row['children']) && count($row['children']) > 0) {
                $result = array_merge($result, $this->get_elements($row['children']));
            }
        }
        return $result;
    }

    function buildTree(array &$elements, $parentId = '')
    {
        $branch = array();

        foreach ($elements as $element) {
            if ($element['parentid'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
                unset($elements[$element['id']]);
            }
        }
        return $branch;
    }

    public function product_manage()
    {
        $products = DB::table('dbo_product')->where('is_deleted', 0)->get()->toArray();
        foreach ($products as $product) {
            $product->categories_name = '';
            foreach (explode(',', $product->categories) as $cateId) {
                $cate = DB::table('dbo_menu')->where('id', $cateId)->first();
                $product->categories_name .= $cate == null ? '' : $cate->text . ', ';
            }
            $product->categories_name = $product->categories_name == '' ? '' : substr($product->categories_name, 0, -2);
            $product->avatar = ($product->images == '' || $product->images == null) ? '' : json_decode($product->images)[0]->key;
        }
        // dd($products);
        return view('admin/product')->with(compact('products'));
    }

    public function product_item($id = null)
    {
        $menus = DB::table('dbo_menu')->orderBy('index', 'ASC')->get()->toArray();
        if ($id && $id > 0) {
            $product_detail = DB::table('dbo_product')->where('id', $id)->first();
            if ($product_detail) {
                $init_preview = array();
                $init_preview_config = '';
                if ($product_detail->images) {
                    $objImgs = json_decode($product_detail->images);
                    foreach ($objImgs as $item) {
                        $init_preview[] = asset('userfiles') . '/' . $item->key;
                    }
                    $init_preview_config = $product_detail->images;
                }
                $init_preview = json_encode($init_preview);
                return view('admin/product_item_edit')->with(compact('menus', 'init_preview', 'init_preview_config', 'product_detail'));
            }
        }
        return view('admin/product_item')->with(compact('menus'));
    }

    public function save_product_item(Request $request)
    {
        try {
            $this->validate(
                $request,
                [
                    'product_name' => 'required',
                    'selected_mid' => 'required',
                    'product_amount' => 'required|numeric',
                    'price' => 'required',
                    'intro_txt' => 'required',
                    'desc_txt' => 'required'
                ],
                [
                    'product_name.required' => 'Vui lòng nhập tên sản phẩm',
                    'selected_mid.required' => 'Vui lòng chọn danh mục',
                    'product_amount.required' => 'Vui lòng nhập số lượng sản phẩm',
                    'product_amount.numeric' => 'Số lượng sản phẩm không hợp lệ',
                    'price.required' => 'Vui lòng nhập giá sản phẩm',
                    'intro_txt.required' => 'Vui lòng nhập giới thiệu sản phẩm',
                    'desc_txt.required' => 'Vui lòng nhập mô tả sản phẩm'
                ]
            );

            $price = (int) preg_replace("/[^0-9.]/", "", $request->price);
            $discount_price = isset($request->discount_price) ? (int) preg_replace("/[^0-9.]/", "", $request->discount_price) : null;

            if ($request->id && $request->id > 0) {
                $product_detail = DB::table('dbo_product')->where('id', $request->id)->first();
                if (!$product_detail) {
                    return redirect()->route('product_item.admin', $request->id);
                }
                DB::table('dbo_product')->where('id', $request->id)->update([
                    'name' => $request->product_name,
                    'categories' => $request->selected_mid,
                    'amount' => $request->product_amount,
                    'price' => $price,
                    'discount_price' => $discount_price,
                    'is_hot' => isset($request->is_hot) ? 1 : 0,
                    'is_new' => isset($request->is_new) ? 1 : 0,
                    'images' => $request->images_name,
                    'sumary' => $request->intro_txt,
                    'description' => $request->desc_txt
                ]);
                return redirect()->route('product.admin');
            }
            //insert
            DB::table('dbo_product')->insert([
                'name' => $request->product_name,
                'alias' => StringHelper::slugify($request->product_name),
                'categories' => $request->selected_mid,
                'amount' => $request->product_amount,
                'price' => $price,
                'discount_price' => $discount_price,
                'is_hot' => isset($request->is_hot) ? 1 : 0,
                'is_new' => isset($request->is_new) ? 1 : 0,
                'images' => $request->images_name,
                'sumary' => $request->intro_txt,
                'description' => $request->desc_txt,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return redirect()->route('product.admin');
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function store_image(Request $request)
    {
        $file = request()->file;
        $objectPreviewConfig = new \stdClass();
        $objectPreviewConfig->size = $file->getSize();
        $imageExt = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
        $imageName = md5($file->getClientOriginalName()) . '.' . $imageExt;
        $file->move(public_path('userfiles'), $imageName);
        $objectPreviewConfig->key = $imageName;
        $objectPreviewConfig->width = '120px';
        $objectPreviewConfig->caption = $imageName;
        return response()->json(['initialPreview' => [asset('userfiles') . '/' . $imageName], 'initialPreviewConfig' => [$objectPreviewConfig], 'file_name' => $imageName]);
    }

    public function delete_image(Request $request)
    {
        try {
            //Fix ko xoa hoan toan tren server
            return response()->json("1");
            $file_path = public_path('userfiles') . '\\' . $request->key;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            return response()->json("1");
        } catch (\Exception $ex) {
            return response()->json("-99");
        }
    }

    public function delete_product(Request $request)
    {
        try {
            $affected = DB::table('dbo_product')->where('id', $request->id)->update(['is_deleted' => 1]);
            return response()->json("1");
        } catch (\Exception $ex) {
            return response()->json("-99");
        }
    }

    public function change_password()
    {
        return view('admin/change_pass');
    }

    public function confirm_change_pass(Request $request)
    {
        if (!$request->old_password || !$request->password) {
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu không hợp lệ']);
        }

        $user = Admin::where('id', Auth::guard('admin')->user()->id)->where('active', 1)->first();
        if (!$user) {
            Auth::logout();
            return response()->json(['ResponseCode' => -1, 'Description' => 'Tài khoản không tồn tại hoặc đã bị khóa']);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Mật khẩu cũ không đúng']);
        }
        try {
            DB::table('dbo_admin')->where('id', $user->id)->update(['password' => Hash::make($request->password)]);
            return response()->json(['ResponseCode' => 1, 'Description' => 'Success']);
        } catch (\Exception $ex) {
            return response()->json(['ResponseCode' => -99, 'Description' => 'Lỗi hệ thống']);
        }
    }

    public function class()
    {
        $schedules = DB::table('dbo_schedule')->get()->toArray();
        $class = DB::table('dbo_class')->join('dbo_schedule', 'dbo_class.schedule_id', '=', 'dbo_schedule.id')
            ->select('dbo_class.*', 'dbo_schedule.name', 'dbo_schedule.time_range')->get()->toArray();
        foreach ($class as $item) {
            $count = DB::table('dbo_register')->where('class_code', $item->code)->where(function ($query) {
                $query->where('status', '=', Config::get('constants.student_init'))
                    ->orWhere('status', '=', Config::get('constants.student_pay'))
                    ->orWhere('status', '=', Config::get('constants.student_studying'));
            })->count();
            $item->total =  $count;
        }
        return view('admin/class')->with(compact('schedules', 'class'));
    }
    public function save_class(Request $request)
    {
        $schedules = DB::table('dbo_class')->where('schedule_id', $request->class_schedule)->where(function ($query) {
            $query->where('status', '=', Config::get('constants.class_status_admission'))
                ->orWhere('status', '=', Config::get('constants.class_status_active'));
        })->first();

        if ($schedules) {
            return response()->json(['statusCode' => -1, 'message' => 'Lịch học đang có lớp hoạt động, ko thể tạo thêm lớp']);
        }
        DB::table('dbo_class')->insert([
            'code' => $request->class_code,
            'schedule_id' => $request->class_schedule,
            'status' => $request->status,
            'creator' => \Auth::guard('admin')->user()->username,
            'start_date' => date('Y-m-d', strtotime(str_replace('/', '-',  $request->start_date))),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        return response()->json(['statusCode' => 200, 'modal' => true, 'message' => 'Tạo lớp học thành công', 'reload' => true]);
    }

    public function promotion_code()
    { }
}
