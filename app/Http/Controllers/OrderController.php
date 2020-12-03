<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Utility\LogActivity;
use App\Utility\StringHelper;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->query->all();
        $allow_remove_order = DB::table('dbo_config')->where('name', 'Cho phép xóa đơn hàng')->where('active', 1)->first();
        if (!$params || !count($params)) {
            $orders = DB::table('dbo_order')->where('created_at', '>=', date("Y-m-d 00:00:00"))
                ->where('created_at', '<=', date("Y-m-d 23:59:59"))
                ->orderBy('created_at', 'DESC')
                ->get()->toArray();
            return view('admin/order')->with(compact('orders', 'allow_remove_order'));
        }

        $data_back = array();
        $query = DB::table('dbo_order');
        if (isset($params['start']) && $params['start']) {
            $query->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $params['start']))));
            $data_back['start'] = $params['start'];
        }
        if (isset($params['end']) && $params['end']) {
            $query->where('created_at', '<=', date('Y-m-d 23:59:00', strtotime(str_replace('/', '-', $params['end']))));
            $data_back['end'] = $params['end'];
        }
        $orders =  $query->orderBy('created_at', 'DESC')->get()->toArray();
        return view('admin/order')->with(compact('orders', 'data_back', 'allow_remove_order'));
    }
    public function detail(Request $request)
    {
        if ($request->printer) {
            $order = DB::table('dbo_order')->where('id', $request->id)->first();
            $grand_amount = $order->grand_amount;
            $discount = '';
            if ($order->discount_rate)
                $discount .= "$order->discount_rate %";
            if ($order->discount_vnd)
                $discount .= number_format($order->discount_vnd) . "đ";
            $products = DB::table('dbo_order_item')
                ->join('dbo_product', 'dbo_product.id', '=', 'dbo_order_item.product_id')
                ->where('dbo_order_item.order_id', $request->id)
                ->where('dbo_order_item.is_deleted', 0)
                ->select('dbo_order_item.*', 'dbo_product.name')->get()->toArray();
            return view('partials/pos_print')->with(compact('products', 'grand_amount', 'discount', 'order'));
        }
        $products = DB::table('dbo_order_item')
            ->join('dbo_product', 'dbo_product.id', '=', 'dbo_order_item.product_id')
            ->where('dbo_order_item.order_id', $request->id)
            ->where('dbo_order_item.is_deleted', 0)
            ->select('dbo_order_item.*', 'dbo_product.name')->get()->toArray();
        return view('partials/order_detail')->with(compact('products'));
    }


    public function create()
    {
        $products = DB::table('dbo_product')->where('is_deleted', 0)->get()->toArray();
        return view('admin/created_order')->with(compact('products'));
    }

    public function create_new()
    {
        return view('admin/create_order_new');
    }

    public function edit($id = null)
    {
        if ($id && $id > 0) {
            $order = DB::table('dbo_order')->where('id', $id)->first();
            if (!$order)
                return redirect()->route('create_order.admin');
            $detail = DB::table('dbo_order_item')
                ->where('dbo_order_item.order_id', $id)
                ->where('dbo_order_item.is_deleted', 0)
                ->get()->toArray();
            $products = DB::table('dbo_product')->where('is_deleted', 0)->get()->toArray();
            return view('admin/edit_order')->with(compact('order', 'detail', 'products'));
        }
        return redirect()->route('create_order.admin');
    }

    public function save(Request $request)
    {
        $table = $request->table;
        $productData =  $request->product_data;
        if (!$table || !count($productData))
            return response()->json([
                'statusCode' => -1,
                'message' => 'Thông tin đầu vào không hợp lệ',
            ]);

        $msg = '';
        $order_item_arr = [];
        $grandAmount = 0;
        foreach ($productData as $item) {
            if ($item['productId'] == 0) {
                if ($item['price'] == 0) {
                    $msg = 'Vui lòng nhập giá cho sản phẩm khác';
                    break;
                }
                if (!$item['note']) {
                    $msg = 'Vui lòng nhập ghi chú cho sản phẩm khác';
                    break;
                }
                $orderItemAmount = $item['quantity'] * $item['price'];
                $grandAmount += $orderItemAmount;
                $order_item = ['order_id' => 0, 'product_id' => $item['productId'], 'quantity' => $item['quantity'], 'note' => $item['note'], 'total_amount' =>  $orderItemAmount];
                $order_item_arr[] = $order_item;
            } else {
                //Lay thong tin product
                $product = DB::table('dbo_product')->where('id', $item['productId'])->where('is_deleted', 0)->first();
                if (!$product) {
                    $msg = 'Một số sản phẩm đã chọn không hợp lệ';
                    break;
                }
                if ($product->discount_price)
                    $orderItemAmount = $item['quantity'] * $product->discount_price;
                else
                    $orderItemAmount = $item['quantity'] * $product->price;
                $grandAmount += $orderItemAmount;
                $order_item = ['order_id' => 0, 'product_id' => $item['productId'], 'quantity' => $item['quantity'], 'note' => $item['note'], 'total_amount' =>  $orderItemAmount];
                $order_item_arr[] = $order_item;
            }
        }
        if ($msg)
            return response()->json([
                'statusCode' => -1,
                'message' => $msg,
            ]);

        if ($request->discount_vnd) {
            $grandAmount -= $request->discount_vnd;
        }
        if ($request->discount_rate) {
            $grandAmount = (100 - $request->discount_rate) * $grandAmount / 100;
        }

        if ($grandAmount < 0) {
            return response()->json([
                'statusCode' => -1,
                'message' => 'Tổng số tiền không hợp lệ, vui lòng kiểm tra lại chiết khấu',
            ]);
        }
        try {
            //code...
            DB::beginTransaction();
            $stringHelper = new StringHelper();
            $regCode = $stringHelper->randomString(6);
            $id = DB::table('dbo_order')->insertGetId([
                'table' => $table,
                'discount_rate' => $request->discount_rate,
                'discount_vnd' => $request->discount_vnd,
                'grand_amount' => $grandAmount,
                'creator' => \Auth::guard('admin')->user()->username,
                'code' => $regCode,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            foreach ($order_item_arr as &$item) {
                $item['order_id'] = $id;
            }
            DB::table('dbo_order_item')->insert($order_item_arr);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'statusCode' => -99,
                'message' => 'Có lỗi xảy ra khi tạo đơn',
            ]);
        }

        return response()->json([
            'statusCode' => 200,
            'redirect' => route('order.admin'),
            'modal' => true,
            'message' => 'Tạo đơn hàng thành công',
            'reload' => true
        ]);
    }
    public function remove_item(Request $request)
    {
        $remove_data = explode("|", $request->remove_data);
        $order_items = DB::table('dbo_order_item')->where('order_id', $remove_data[0])->where('is_deleted', 0)->get();
        $delete_item = $order_items->where('product_id', $remove_data[1])->first();
        if (!$delete_item) {
            return response()->json([
                'statusCode' => -1,
                'message' => 'Sản phẩm không tồn tại',
            ]);
        }
        //Tinh toan lai gia
        $grand_amount = $order_items->sum('total_amount') - $delete_item->total_amount;

        //Chiet khau
        $order = DB::table('dbo_order')->where('id', $remove_data[0])->first();

        if (!$order) {
            return response()->json([
                'statusCode' => -1,
                'message' => 'Đơn hàng không tồn tại',
            ]);
        }

        if ($order->discount_vnd) {
            $grand_amount -= $order->discount_vnd;
        }

        if ($order->discount_rate) {
            $grand_amount  = (100 - $order->discount_rate) *  $grand_amount  / 100;
        }

        if ($grand_amount < 0) {
            return response()->json([
                'statusCode' => -1,
                'message' => 'Tổng số tiền không hợp lệ, vui lòng kiểm tra lại chiết khấu',
            ]);
        }

        try {
            DB::beginTransaction();
            DB::table('dbo_order_item')->where([['order_id', '=', $remove_data[0]], ['product_id', '=', $remove_data[1]]])->update([
                'is_deleted' => true,
                'deletor' => \Auth::guard('admin')->user()->username,
                'delete_reason' => $request->reason
            ]);
            DB::table('dbo_order')->where('id', $remove_data[0])->update([
                'grand_amount' => $grand_amount
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'statusCode' => -99,
                'message' => 'Có lỗi xảy ra khi tạo đơn'
            ]);
        }
        LogActivity::add_log(\Auth::guard('admin')->user()->username, "Xóa sản phẩm đơn hàng: $remove_data[0]", json_encode($delete_item), null);
        return response()->json([
            'statusCode' => 200,
            'grandAmount' => $grand_amount
        ]);
    }

    public function save_edit(Request $request)
    {
        $table = $request->table;
        if (!$table || !$request->id || $request->id == 0)
            return response()->json([
                'statusCode' => -1,
                'message' => 'Thông tin đầu vào không hợp lệ',
            ]);

        $order = DB::table('dbo_order')->where('id', $request->id)->first();

        if (!$order) {
            return response()->json([
                'statusCode' => -1,
                'message' => 'Đơn hàng không tồn tại',
            ]);
        }

        $productData =  $request->product_data;
        $grandAmount = 0;
        if (count($productData)) {
            $msg = '';
            $order_item_arr = [];
            foreach ($productData as $item) {
                if ($item['productId'] == 0) {
                    if ($item['price'] == 0) {
                        $msg = 'Vui lòng nhập giá cho sản phẩm khác';
                        break;
                    }
                    if (!$item['note']) {
                        $msg = 'Vui lòng nhập ghi chú cho sản phẩm khác';
                        break;
                    }
                    $orderItemAmount = $item['quantity'] * $item['price'];
                    $grandAmount += $orderItemAmount;
                    $order_item = ['order_id' => 0, 'product_id' => $item['productId'], 'quantity' => $item['quantity'], 'note' => $item['note'], 'total_amount' =>  $orderItemAmount];
                    $order_item_arr[] = $order_item;
                } else {
                    //Lay thong tin product
                    $product = DB::table('dbo_product')->where('id', $item['productId'])->where('is_deleted', 0)->first();
                    if (!$product) {
                        $msg = 'Một số sản phẩm đã chọn không hợp lệ';
                        break;
                    }
                    if ($product->discount_price)
                        $orderItemAmount = $item['quantity'] * $product->discount_price;
                    else
                        $orderItemAmount = $item['quantity'] * $product->price;
                    $grandAmount += $orderItemAmount;
                    $order_item = ['order_id' => 0, 'product_id' => $item['productId'], 'quantity' => $item['quantity'], 'note' => $item['note'], 'total_amount' =>  $orderItemAmount];
                    $order_item_arr[] = $order_item;
                }
            }
            if ($msg)
                return response()->json([
                    'statusCode' => -1,
                    'message' => $msg,
                ]);
        }

        //Tinh lai grand amount
        $order_items = DB::table('dbo_order_item')->where('order_id', $request->id)->where('is_deleted', 0)->get();
        $old_grand_amount = $order_items->sum('total_amount');

        $grandAmount += $old_grand_amount;

        if ($request->discount_vnd) {
            $grandAmount -= $request->discount_vnd;
        }
        if ($request->discount_rate) {
            $grandAmount = (100 - $request->discount_rate) * $grandAmount / 100;
        }

        if ($grandAmount < 0) {
            return response()->json([
                'statusCode' => -1,
                'message' => 'Tổng số tiền không hợp lệ, vui lòng kiểm tra lại chiết khấu',
            ]);
        }

        try {
            //code...
            DB::beginTransaction();
            DB::table('dbo_order')->where('id', $request->id)->update([
                'table' => $table,
                'discount_rate' => $request->discount_rate,
                'discount_vnd' => $request->discount_vnd,
                'grand_amount' => $grandAmount,
            ]);
            foreach ($order_item_arr as &$item) {
                $item['order_id'] = $request->id;
            }
            DB::table('dbo_order_item')->insert($order_item_arr);
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'statusCode' => -99,
                'message' => 'Có lỗi xảy ra khi tạo đơn',
            ]);
        }
        $new_order = DB::table('dbo_order')->where('id', $request->id)->first();
        LogActivity::add_log(\Auth::guard('admin')->user()->username, "Sửa đơn hàng: $request->id", json_encode($order), json_encode($new_order));
        LogActivity::add_log(\Auth::guard('admin')->user()->username, "Cập nhật sản phẩm đơn hàng: $request->id", null, json_encode($order_item_arr));
        return response()->json([
            'statusCode' => 200,
            'redirect' => route('order.admin'),
            'modal' => true,
            'message' => 'Cập nhật đơn hàng thành công',
            'reload' => true,
            'grandAmount' => $grandAmount
        ]);
    }

    public function pay(Request $request)
    {
        if (!$request->id || $request->id == 0)
            return response()->json([
                'statusCode' => -1,
                'message' => 'Thông tin đầu vào không hợp lệ',
            ]);

        $order = DB::table('dbo_order')->where('id', $request->id)->first();

        if (!$order) {
            return response()->json([
                'statusCode' => -1,
                'message' => 'Đơn hàng không tồn tại',
            ]);
        }
        DB::table('dbo_order')->where('id', $request->id)->update([
            'order_status' => Config::get('constants.order_done')
        ]);

        return response()->json([
            'statusCode' => 200,
            'modal' => true,
            'message' => 'Thanh toán thành công',
            'reload' => true,
        ]);
    }
    public function delete(Request $request)
    {
        if (!$request->id || $request->id == 0)
            return response()->json([
                'statusCode' => -1,
                'message' => 'Thông tin đầu vào không hợp lệ',
            ]);

        $allow_remove_order = DB::table('dbo_config')->where('name', 'Cho phép xóa đơn hàng')->where('active', 1)->first();
        if (!\Auth::guard('admin')->user()->admin && !$allow_remove_order)
            return response()->json([
                'statusCode' => -1,
                'message' => 'Bạn không thể thực hiện chức năng này',
            ]);
        $order = DB::table('dbo_order')->where('id', $request->id)->first();

        if (!$order) {
            return response()->json([
                'statusCode' => -1,
                'message' => 'Đơn hàng không tồn tại',
            ]);
        }
        $order_items =  DB::table('dbo_order_item')->where('order_id', $request->id)->get()->toArray();
        try {
            //code...
            DB::beginTransaction();
            //xoa san pham
            DB::table('dbo_order_item')->where('order_id', $request->id)->delete();
            //xoa don hang
            DB::table('dbo_order')->where('id', $request->id)->delete();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
            return response()->json([
                'statusCode' => -99,
                'message' => 'Có lỗi xảy ra khi xóa đơn',
            ]);
        }

        LogActivity::add_log(
            \Auth::guard('admin')->user()->username,
            "Xóa đơn hàng: $order->code",
            "Đơn: " . json_encode($order) . ". Sản phẩm: " . json_encode($order_items),
            null
        );

        return response()->json([
            'statusCode' => 200,
            'modal' => true,
            'message' => 'Xóa đơn hàng thành công',
            'reload' => true,
        ]);
    }
}
