<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserGroupController extends Controller
{
    public function group()
    {
        $groups = DB::table('dbo_group')->where('status', 1)->get()->toArray();
        return view('admin/group')->with(compact('groups'));;
    }

    public function save_group(Request $request)
    {
        try {
            if ($request->id) {
                DB::table('dbo_group')->where('id', $request->id)->update(['name' =>  $request->name, 'code' =>  $request->code]);
                return response()->json("1");
            }
            DB::table('dbo_group')->insert([
                'name' => $request->name,
                'code' => $request->code,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            return response()->json("1");
        } catch (\Exception $ex) {
            return response()->json("-99");
        }
    }

    public function delete_group(Request $request)
    {
        try {
            DB::table('dbo_group')->where('id', $request->id)->update(['status' => 0]);;
            return response()->json("1");
        } catch (\Exception $ex) {
            return response()->json("-99");
        }
    }

    public function user()
    {
        $groups = DB::table('dbo_group')->where('status', 1)->get()->toArray();
        $users = DB::table('dbo_admin')->join('dbo_user_group', 'dbo_admin.id', '=', 'dbo_user_group.user_id')
            ->join('dbo_group', 'dbo_group.id', '=', 'dbo_user_group.group_id')->get()->toArray();

        $tmp = array();
        foreach ($users as $element) {
            if (!in_array($element->user_id, array_keys($tmp))) {
                $tmp[$element->user_id] = (object) array(
                    'id' => $element->id,
                    'username' => $element->username,
                    'admin' => $element->admin,
                    'active' => $element->active,
                    'user_id' => $element->user_id,
                    'group_id' => $element->group_id,
                    'group_name' =>  $element->name,
                    'group_code' => $element->code
                );
            } else {
                $tmp[$element->user_id]->group_name = $tmp[$element->user_id]->group_name . ',' . $element->name;
                $tmp[$element->user_id]->group_id = $tmp[$element->user_id]->group_id . ',' . $element->group_id;
            }
        }
        return view('admin/user')->with(compact('tmp', 'groups'));;
    }
    public function save_user(Request $request)
    {

        $this->validate(
            $request,
            [
                'user_name' => 'required_without:user_id',
                'm_select2_3' => 'required|array|min:1'
            ],
            [
                'user_name.required_without' => 'Vui lòng nhập tên tài khoản',
                'm_select2_3.required' => 'Vui lòng chọn nhóm người dùng'
            ]
        );

        try {
            if ($request->user_id && $request->user_id > 0) {
                $user = DB::table('dbo_admin')->where('id', $request->user_id)->first();

                if (!$user) {
                    return response()->json(['statusCode' => -1, 'message' => 'Không tìm thấy thông tin tài khoản']);
                }
                DB::beginTransaction();
                try {
                    DB::table('dbo_user_group')->where('user_id', $request->user_id)->delete();
                    DB::table('dbo_admin')->where('id', $request->user_id)->update([
                        'active' => isset($request->user_status) && $request->user_status === 'on' ? 1 : 0,
                        'admin' => isset($request->user_type) && $request->user_type == 'on' ? 1 : 0,
                        'password' => $request->password ? Hash::make($request->password) : $user->password,
                    ]);
                    foreach ($request->m_select2_3 as $group_id) {
                        DB::table('dbo_user_group')->insert([
                            'user_id' => $request->user_id,
                            'group_id' => $group_id,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                    DB::commit();
                } catch (\Exception $ex) {
                    DB::rollback();
                    throw $ex;
                }
                return response()->json(['statusCode' => 200, 'modal' => true, 'message' => 'Cập nhật tài khoản thành công', 'reload' => true]);
            }

            DB::beginTransaction();
            try {
                $insert_id = DB::table('dbo_admin')->insertGetId([
                    'username' => $request->user_name,
                    'active' => isset($request->user_status) && $request->user_status === 'on' ? 1 : 0,
                    'admin' => isset($request->user_type) && $request->user_type == 'on' ? 1 : 0,
                    'password' => Hash::make($request->password),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                foreach ($request->m_select2_3 as $group_id) {
                    DB::table('dbo_user_group')->insert([
                        'user_id' => $insert_id,
                        'group_id' => $group_id,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                DB::commit();
            } catch (\Exception $ex) {
                DB::rollback();
                return response()->json(['statusCode' => -99, 'message' => 'Lỗi tạo tài khoản, vui lòng thử lại sau']);
            }

            return response()->json(['statusCode' => 200, 'modal' => true, 'message' => 'Thêm mới tài khoản thành công', 'reload' => true]);
        } catch (\Exception $ex) {
            return response()->json(['statusCode' => -99, 'message' => 'Đã có lỗi xảy ra, vui lòng thử lại sau']);
        }
    }
}
