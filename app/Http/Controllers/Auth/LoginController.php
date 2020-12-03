<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Config,
    DateTime;
use Mail;
use App\Mail\SendMailable;
use App\User;
use App\Admin;
use App\Utility\StringHelper;
use App\Utility\CookieHelper;
use App\Utility\SystemHelper;
use App\UserLog;
use App\Jobs\UserLogQueue;

class LoginController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginAdmin()
    {
        return view('admin/login');
    }

    public function sendOTP(Request $request)
    {
        $stringUtility = new StringHelper();
        $otp = $stringUtility->randomString();
        //Gui telegram
        $botToken = Config::get('constants.telegram_token');
        $chatIdGroup = Config::get('constants.telegram_chatId');
        $telegramBot = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatIdGroup}&text={$otp}";
        $cookieHelper = new CookieHelper();
        $cookieHelper->curl($telegramBot);
        //Set session      
        //file_get_contents($telegramBot);
        $request->session()->put('loginotp', $otp);
        return response()->json(['ResponseCode' => 1, 'Description' => 'Success']);
    }

    public function doPostLogin(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required',
            // 'otp' => 'required',
        ];
        $messages = [
            'username.required' => 'Vui lòng nhập tài khoản',
            'password.required' => 'Vui lòng nhập mật khẩu',
            // 'otp.required' => 'Vui lòng nhập OTP',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails())
            return redirect()->route('login.admin')->withErrors($validator)->withInput();

        //        if (!($request->session()->has('loginotp'))) {
        //            return redirect()->route('login.admin')->withErrors(['OTP hết hạn, vui lòng thử lại'])->withInput();
        //        }
        //
        //        $sessionOtp = $request->session()->get('loginotp');
        //        if (!$sessionOtp) {
        //            return redirect()->route('login.admin')->withErrors(['OTP hết hạn, vui lòng thử lại'])->withInput();
        //        }
        //        if (strcasecmp($sessionOtp, $request->otp) != 0) {
        //            return redirect()->route('login.admin')->withErrors(['Mã otp không hợp lệ'])->withInput();
        //        }
        //
        //        $request->session()->forget('loginotp');

        $user = Admin::where('username', $request->username)->where('active', 1)->first();
        if (!$user)
            return redirect()->route('login.admin')->withErrors(['Tài khoản không tồn tại'])->withInput();

        if ($user->otp && $user->otp !== $request->otp)
            return redirect()->route('login.admin')->withErrors(['Mã xác thực không hợp lệ'])->withInput();

        if (Hash::check($request->password, $user->password)) {
            \Auth::guard('admin')->login($user);
            return redirect('/admin');
        }
        return redirect()->route('login.admin')->withErrors(['Mật khẩu không đúng'])->withInput();
    }

    public function userLogin(Request $request)
    {
        $userName = $request->username;
        $password = $request->password;
        $gCaptcha = $request->captcha;
        if (!$userName || !$password || !$gCaptcha) {
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu không hợp lệ']);
        }

        $systemHelper = new SystemHelper();
        if (!($systemHelper->checkGoogleCaptcha($gCaptcha))) {
            return response()->json(['status' => -100, 'error' => 'Captcha không hợp lệ']);
        }

        $user = User::where('username', $userName)->first();
        if (!$user) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Tài khoản không tồn tại']);
        }
        if (!$user->active) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Tài khoản bị khóa, vui lòng liên hệ quản trị để được hỗ trợ']);
        }
        if (Hash::check($password, $user->password)) {
            //Kiem tra co su dung bao mat dang nhap ko
            $userSecure = \DB::table('dbo_user_secure')->where('userid', $user->id)->first();
            if ($userSecure && strpos($userSecure->secure_method, '2')) {
                session(["user_require_secure" => $user]);
                return response()->json(['ResponseCode' => 2, 'Description' => 'Success']);
            }
            \Auth::guard('user')->login($user);
            $stringUtility = new StringHelper();
            $userLog = array($user->username, "login", 'Login tài khoản. IP:' . $stringUtility->getUserIP());
            UserLogQueue::dispatch($userLog);
            return response()->json(['ResponseCode' => 1, 'Description' => 'Success']);
        }
        return response()->json(['ResponseCode' => -1, 'Description' => 'Mật khẩu không đúng']);
    }

    public function loginVerify(Request $request)
    {
        $otp = $request->otp;
        if (!$otp) {
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu không hợp lệ']);
        }
        $userSession = session('user_require_secure');
        if (!$userSession) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Dữ liệu hết hạn, vui lòng đăng nhập lại']);
        }
        $google2fa = app('pragmarx.google2fa');
        $secretkey = decrypt($userSession->secretkey);
        if (!$google2fa->verifyKey($secretkey, $otp)) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Mã xác thực không hợp lệ']);
        }
        \Auth::guard('user')->login($userSession);
        $stringUtility = new StringHelper();
        $userLog = array($userSession->username, "login", 'Login tài khoản. IP:' . $stringUtility->getUserIP());
        UserLogQueue::dispatch($userLog);
        return response()->json(['ResponseCode' => 1, 'Description' => 'Success']);
    }

    public function forgetPassStep1(Request $request)
    {
        $user_name = $request->username;
        if (!$user_name) {
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu không hợp lệ']);
        }

        $user = User::where('username', $user_name)->first();
        if (!$user) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Tài khoản không tồn tại']);
        }

        //Gen cu phap tin nhan
        $stringHelper = new StringHelper();
        $verify_code = $stringHelper->randomOTP();
        $verify_token = $stringHelper->randomString(20);
        $data = array(
            'name' => $user_name,
            'verify_code' => $verify_code,
            'timeout' => '3 phút'
        );
        Mail::to($user_name)->send(new SendMailable('emails.verify', 'Quên mật khẩu', $data));
        $cacheData = [
            'code' => $verify_code,
            'token' => $verify_token
        ];
        //Luu cache 3 phut
        Cache::put($user_name . '_forgetpass', $cacheData, now()->addMinutes(3));
        return response()->json(['ResponseCode' => 1, 'Description' => 'Success']);
    }

    public function forgetPassStep2(Request $request)
    {
        $user_name = $request->user_name;
        $code = $request->code;
        if (!$user_name) {
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu đầu vào không hợp lệ']);
        }
        if (!$code) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Vui lòng nhập mã xác thực']);
        }

        $cacheData = Cache::get($user_name . '_forgetpass');
        if (!$cacheData) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Mã xác thực không tồn tại hoặc đã hết hạn']);
        }

        if ($code !== $cacheData['code']) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Mã xác thực không đúng']);
        }
        Cache::put($user_name . '_forgetpass', $cacheData['token'], now()->addMinutes(10));
        return response()->json(['ResponseCode' => 1, 'Description' => 'OK', 'Token' => $cacheData['token']]);
    }

    public function confirmResetPassword(Request $request)
    {
        $token = $request->token;
        $user_name = $request->user_name;
        $password = $request->password;
        if (!$token || !$password || !$user_name) {
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu không hợp lệ']);
        }
        $value = Cache::get($user_name . '_forgetpass');
        if (!$value || $value !== $token) {
            return response()->json(['ResponseCode' => -1, 'Description' => 'Dữ liệu hết hạn, vui lòng thử lại']);
        }
        Cache::forget($token);
        //Doi mat khau
        DB::table('dbo_user')
            ->where('username', $user_name)
            ->update([
                'password' => Hash::make($password)
            ]);
        return response()->json(['ResponseCode' => 1, 'Description' => 'Success!']);
    }

    public function logout(Request $request)
    {
        $this->guard('admin')->logout();
        $this->guard('user')->logout();
        $request->session()->invalidate();
        return redirect('/');
    }
}
