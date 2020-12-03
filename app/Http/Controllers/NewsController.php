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

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->query->all();
        if (!$params || !count($params)) {
            $news = DB::table('dbo_news')
                ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime("-30 day")))
                ->where('created_at', '<=', date('Y-m-d 23:59:59'))
                ->orderBy('created_at', 'DESC')
                ->get()->toArray();
            return view('admin/news')->with(compact('news'));
        } else {
            $data_back = array();
            $query = DB::table('dbo_news');
            if (isset($params['start']) && $params['start']) {
                $query->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $params['start']))));
                $data_back['start'] = $params['start'];
            }
            if (isset($params['end']) && $params['end']) {
                $query->where('created_at', '<=', date('Y-m-d 23:59:00', strtotime(str_replace('/', '-', $params['end']))));
                $data_back['end'] = $params['end'];
            }
            if (isset($params['title']) && $params['title']) {
                $query->where('title', 'like', '%' . $params['title'] . '%');
                $data_back['title'] = $params['title'];
            }

            $news =  $query->orderBy('created_at', 'DESC')->get()->toArray();
            return view('admin/news')->with(compact('news', 'data_back'));
        }
    }

    public function add()
    {
        return view('admin/news_item');
    }

    public function save(Request $request)
    {
        try {
            $this->validate(
                $request,
                [
                    'title' => 'required',
                    'intro_txt' => 'required',
                    'desc_txt' => 'required'
                ],
                [
                    'title.required' => 'Vui lòng nhập tiêu đề',
                    'intro_txt.required' => 'Vui lòng nhập mô tả',
                    'desc_txt.required' => 'Vui lòng nhập nội dung tin bài'

                ]
            );

            //insert
            DB::table('dbo_news')->insert([
                'title' => $request->title,
                'alias' => StringHelper::slugify($request->title),
                'content' => $request->desc_txt,
                'is_hot' => isset($request->is_hot) ? 1 : 0,
                'is_new' => isset($request->is_new) ? 1 : 0,
                'banner' => $request->images_name,
                'summary' => $request->intro_txt,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return redirect()->route('news.admin');
        } catch (\Exception $ex) {
            return response()->json($ex);
        }
    }

    public function showContent(Request $request)
    {
        if (!$request->id || $request->id == 0)
            return response()->json([
                'statusCode' => -1,
                'message' => 'Thông tin đầu vào không hợp lệ',
            ]);

        $news_detail = DB::table('dbo_news')->where('id', $request->id)->first();

        if (!$news_detail) {
            return response()->json([
                'statusCode' => -1,
                'message' => 'Không tìm thấy thông tin bài viết',
            ]);
        }

        return response()->json([
            'statusCode' => 200,
            'modal' => true,
            'message' => 'Thanh toán thành công',
            'content' => $news_detail->content,
        ]);
    }
    public function delete_news(Request $request)
    {
        try {
            DB::table('dbo_news')->where('id', $request->id)->delete();
            return response()->json("1");
        } catch (\Exception $ex) {
            return response()->json("-99");
        }
    }

    public function edit($id = null)
    {
        $news_detail = DB::table('dbo_news')->where('id', $id)->first();
        if ($news_detail) {
            $init_preview = array();
            $init_preview_config = '';
            if ($news_detail->banner) {
                $objImgs = json_decode($news_detail->banner);
                foreach ($objImgs as $item) {
                    $init_preview[] = asset('userfiles') . '/' . $item->key;
                }
                $init_preview_config = $news_detail->banner;
            }
            $init_preview = json_encode($init_preview);
            return view('admin/news_item_edit')->with(compact('init_preview', 'init_preview_config', 'news_detail'));
        }

        abort(404);
    }

    public function store(Request $request)
    {
        $detail = DB::table('dbo_news')->where('id', $request->id)->first();
        if (!$detail) {
            return redirect()->route('add_news.admin', $request->id);
        }
        DB::table('dbo_news')->where('id', $request->id)->update([
            'title' => $request->title,
            'alias' => StringHelper::slugify($request->title),
            'content' => $request->desc_txt,
            'is_hot' => isset($request->is_hot) ? 1 : 0,
            'is_new' => isset($request->is_new) ? 1 : 0,
            'banner' => $request->images_name,
            'summary' => $request->intro_txt,
        ]);
        return redirect()->route('news.admin');
    }
}
