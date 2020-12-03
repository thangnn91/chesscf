<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Auth;
use App\Utility\CookieHelper;
use App\Utility\SecurityHelper;
use App\Utility\SystemHelper;
use App\Utility\StringHelper;

class HomeController extends Controller
{

    public function index()
    {
        // \Artisan::call('command:backupdb');
        // dd("ok");
        $allCate = DB::table('dbo_menu')->orderBy('index')->get()->toArray();
        $productsNew = array();
        $productsDiscount = array();
        $productsHot = array();
        $tmpProducts = null;
        foreach ($allCate as $cate) {
            $tmpProducts = DB::table('dbo_product')->where('is_deleted', 0)->where('categories', 'like', '%' . $cate->id . '%');
            if (count($tmpProducts->get()->toArray())) {
                $productsNew = array_merge($productsNew, $tmpProducts->where('is_new', 1)->get()->toArray());
                $productsDiscount = array_merge($productsDiscount, $tmpProducts->where('discount_price', '!=', '')->where('discount_price', '!=', 0)->get()->toArray());
                $productsHot = array_merge($productsHot, $tmpProducts->where('is_hot', 1)->get()->toArray());
            }
        }
        $cateShowHome = DB::table('dbo_menu')->where('showhome', 1)->get()->toArray();
        foreach ($cateShowHome as $cate) {
            $cate->products = DB::table('dbo_product')->where('is_deleted', 0)
                ->where('categories', 'like', '%' . $cate->id . '%')->take(6)->get()->toArray();
        }
        return view('home')->with(compact('productsNew', 'productsDiscount', 'productsHot', 'cateShowHome'));
    }

    public function category($href = null)
    {
        $cate = DB::table('dbo_menu')->where('href', '/' . $href)->first();
        if (isset($cate)) {
            if ($cate->href === '/lien-he') return view('contact');
            else if ($cate->href === '/dang-ky') {
                $available_class = DB::table('dbo_class')->join('dbo_schedule', 'dbo_class.schedule_id', '=', 'dbo_schedule.id')->where(function ($query) {
                    $query->where('status', '=', Config::get('constants.class_status_admission'))
                        ->orWhere('status', '=', Config::get('constants.class_status_active'));
                })->select('dbo_class.*', 'dbo_schedule.name', 'dbo_schedule.time_range')->get()->toArray();;
                foreach ($available_class as $item) {
                    $count = DB::table('dbo_register')->where('class_code', $item->code)->where(function ($query) {
                        $query->where('status', '=', Config::get('constants.student_init'))
                            ->orWhere('status', '=', Config::get('constants.student_pay'))
                            ->orWhere('status', '=', Config::get('constants.student_studying'));
                    })->count();
                    $item->total =  $count;
                    $item->percent = ceil($count / Config::get('constants.class_max_student') * 100);
                }
                //dd($available_class);
                return view('register')->with(compact('available_class'));
            } else if ($cate->href === '/tin-tuc') {
                //Lay tin tuc
                $news = DB::table('dbo_news')->paginate(6);
                return view('news', ['news' => $news]);
            }

            $cate->products = DB::table('dbo_product')->where('is_deleted', 0)
                ->where('categories', 'like', '%' . $cate->id . '%')->get()->toArray();
            return view('category')->with(compact('cate'));
        } else {
            abort(404);
        }
    }

    public function product($alias = null)
    {
        $id = array();
        preg_match('/(\d+)(?!.*\d)/', $alias, $id);
        if (count($id)) {
            $product = DB::table('dbo_product')->where('id', $id[0])->first();
        }
        if (isset($product)) {
            $relatedProduct = DB::table('dbo_product')->where('is_deleted', 0)
                ->where('id', '!=', $product->id)
                ->where('categories', 'like', '%' . $product->categories . '%')->take(8)->get()->toArray();
            if (!count($relatedProduct)) {
                $relatedProduct = DB::table('dbo_product')->where('is_deleted', 0)
                    ->where('id', '!=', $product->id)->where('is_hot', 1)->orWhere('is_new', 1)->take(8)->get()->toArray();
            }
            return view('product')->with(compact('product', 'relatedProduct'));
        } else {
            abort(404);
        }
    }

    public function error(Request $request)
    {
        $back_url = $request->query('back_url');
        return view('layouts/_errorlayout')->with(compact('back_url'));
    }

    public function send_register(Request $request)
    {
        try {
            if ($request->class_code) {
                $count = DB::table('dbo_register')->where('class_code', $request->class_code)->where(function ($query) {
                    $query->where('status', '=', Config::get('constants.student_init'))
                        ->orWhere('status', '=', Config::get('constants.student_pay'))
                        ->orWhere('status', '=', Config::get('constants.student_studying'));
                })->count();
                if ($count >= Config::get('constants.class_max_student')) {
                    return response()->json(['statusCode' => -1, 'message' => 'Lớp đã đủ số lượng học sinh, vui lòng liên hệ chúng tôi để được tư vấn']);
                }
            }
            $stringHelper = new StringHelper();
            $regCode = $stringHelper->randomString(6);
            DB::table('dbo_register')->insert([
                'parent_name' => $request->parent_name,
                'parent_phone' => $request->parent_name,
                'child_name' => $request->child_name,
                'child_age' => $request->child_age,
                'class_code' => $request->class_code,
                'note' => $request->message,
                'register_code' =>  $regCode,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return response()->json(['statusCode' => 1, 'message' => 'Đăng ký học thành công', 'registerCode' =>  $regCode]);
        } catch (\Exception $ex) {
            return response()->json(['statusCode' => -99, 'message' => 'Có lỗi xảy ra, vui lòng thử lại sau']);
        }
    }

    public function success(Request $request)
    {
        if ($request->has('code') && $request->code) {
            $student = DB::table('dbo_register')->where('register_code', $request->code)->first();
            if ($student) {
                if ($student->class_code) {
                    $classInfo = DB::table('dbo_class')->join('dbo_schedule', 'dbo_class.schedule_id', '=', 'dbo_schedule.id')
                        ->where('code', $student->class_code)->first();
                    if ($classInfo) {
                        $student->class = $classInfo->name . ' ' . $classInfo->time_range;
                        $student->class_desc = $classInfo->description;
                    }
                }
                return view('success')->with(compact('student'));
            }
        }
        return redirect()->route('home');
    }

    public function new_detail($alias = null)
    {
        $id = array();
        preg_match('/(\d+)(?!.*\d)/', $alias, $id);
        if (count($id)) {
            $news_detail = DB::table('dbo_news')->where('id', $id[0])->first();
        }
        if (isset($news_detail)) {
            return view('news_detail')->with(compact('news_detail'));
        } else {
            abort(404);
        }
    }
}
