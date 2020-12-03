<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\SendMailable;
use App\Utility\StringHelper;

class RegisterController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data) {
        return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
        ]);
    }

    public function postRegister(Request $request) {
        $action = $request->action;
        $username = $request->username;
             
        if (!$action || ($action != 1 && $action != 2) || !$username) {
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu không hợp lệ']);
        }
        //Luu tam vao session sdt = 5 so cuoi
        if ($action == 1) {
            $password = $request->password;
            if (!$password) {
                return response()->json(['ResponseCode' => -600, 'Description' => 'Vui lòng nhập mật khẩu']);
            }
            //Check tai khoan da ton tai trong db
            if (User::where('username', $username)->exists()) {
                return response()->json(['ResponseCode' => -1, 'Description' => 'Tài khoản đã tồn tại']);
            }
            //Tao random number
            $stringUtility = new StringHelper();
            $verify_code = $stringUtility->randomOTP();
            $data = array(
                'name' => $username,
                'verify_code' => $verify_code,
                'timeout' => '30 phút'
            );
            Mail::to($username)->send(new SendMailable('emails.verify', 'Xác thực tài khoản', $data));

            $cacheData = [
                'password' => $password,
                'code' => $verify_code,
            ];
            Cache::put($username . '_register', $cacheData, now()->addMinutes(30));
            return response()->json(['ResponseCode' => 1, 'Description' => 'OK']);
        } else if ($action == 2) {
            $verify_code = $request->code;
            if (!$verify_code) {
                return response()->json(['ResponseCode' => -1, 'Description' => 'Vui lòng nhập mã kích hoạt']);
            }

            $registerData = Cache::get($username . '_register');
            if (!$registerData)
                return response()->json(['ResponseCode' => -1, 'Description' => 'Mã xác thực đã hết hạn']);

            if ($verify_code !== $registerData['code'])
                return response()->json(['ResponseCode' => -1, 'Description' => 'Mã xác thực không đúng']);

            $newUser = new User;
            try {
                parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY), $queries);
                if ($queries) {
                    $followID = $queries['fid'];
                    if ($followID && strlen($followID) === 32 && User::where('ref_id', $followID)->exists())
                        $newUser->follow_id = $followID;
                }

                DB::beginTransaction();
                $newUser->username = $username;
                $newUser->email = $username;
                $newUser->password = Hash::make($registerData['password']);
                $newUser->created_at = date('Y-m-d H:i:s');
                $newUser->ref_id = md5($username . 'b3stm0n3yvn');

                $newUser->save();
                DB::table('dbo_balance')->insert(
                        [
                            'userid' => $newUser->id,
                        ]
                );
                DB::commit();
            } catch (Exception $ex) {
                DB::rollback();
                return response()->json(['ResponseCode' => -99, 'Description' => 'Exception occurs']);
            }
            \Auth::guard('user')->login($newUser);
            //Remove session
            Cache::forget($username . '_register');
            return response()->json(['ResponseCode' => 1, 'Description' => 'Success, allow logged']);
        }
    }

}
