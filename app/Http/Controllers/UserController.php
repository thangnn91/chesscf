<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Utility\StringHelper;
use Illuminate\Support\Facades\Log;
use App\User;
use Hash,
    Auth,
    Image,
    Config,
    DateTime;

class UserController extends Controller {

    public function __construct() {
        $this->middleware('permission');
    }

    public function index() {
        return view('userIndex');
    }

    public function updateProfile() {
        $userInfo = Auth::guard('user')->user();
        return view('user.updateProfile')->with(compact('userInfo'));
    }

    public function transactionHistory() {
        return view('user.transactionHistory');
    }

    public function listUserTransaction(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if (!$startDate || !$endDate)
            return response()->json('Dữ liệu không hợp lệ');
        $uid = Auth::guard('user')->user()->id;
        $query = DB::table('dbo_payment_order')->select('id', 'status', 'serviceid', 'productcode', 'totalproduct', 'grandamount', 'memory_id', 'created_at')
                ->where('userid', $uid)
                ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $startDate))))
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $endDate))));
        if ($request->status) {
            $arrayStatus = explode(";", $request->status);
            for ($i = 0; $i < count($arrayStatus); $i++) {
                if ($i === 0)
                    $query->where('status', Config::get('constants.' . $arrayStatus[$i]));
                else
                    $query->orWhere('status', Config::get('constants.' . $arrayStatus[$i]));
            }
        }

        $results = $query->latest()->get();
        return response()->json(['data' => $results]);
    }

    public function userCancelOrder(Request $request) {
        if (!$request->oid)
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu không hợp lệ']);

        $orderInfo = DB::table('dbo_payment_order')->where('id', $request->oid)->where('userid', Auth::guard('user')->user()->id)->first();
        if (!$orderInfo)
            return response()->json(['ResponseCode' => -1, 'Description' => 'Thao tác không hợp lệ']);
        if ($orderInfo->status != Config::get('constants.cashin_init'))
            return response()->json(['ResponseCode' => -1, 'Description' => 'Trạng thái đơn hàng không hợp lệ']);

        $stringUtility = new StringHelper();
        DB::table('dbo_payment_order')->where('id', $request->oid)->update(['status' => Config::get('constants.customer_cancel_order'),
            'description' => 'Khách hàng chủ động hủy đơn hàng. IP khách: ' . $stringUtility->getUserIP()]);
        return response()->json(['ResponseCode' => 1, 'Description' => 'Success']);
    }

    public function sendClaimOrder(Request $request) {
        if (!$request->oid || !$request->msg)
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu không hợp lệ']);

        $orderInfo = DB::table('dbo_payment_order')->where('id', $request->oid)->where('userid', Auth::guard('user')->user()->id)->first();
        if (!$orderInfo)
            return response()->json(['ResponseCode' => -1, 'Description' => 'Thao tác không hợp lệ']);
        if ($orderInfo->status != Config::get('constants.cashin_payment_ok') && $orderInfo->status != Config::get('constants.cashin_received') &&
                $orderInfo->status != Config::get('constants.cashin_pending') && $orderInfo->status != Config::get('constants.cashin_fail'))
            return response()->json(['ResponseCode' => -1, 'Description' => 'Trạng thái đơn hàng không hợp lệ']);

        //update
        if (DB::table('dbo_payment_claim')->where('orderid', $request->oid)->first()) {
            DB::table('dbo_payment_claim')->where('orderid', $request->oid)->update(['claim_msg' => $request->msg]);
        }
        //insert
        else {
            DB::table('dbo_payment_claim')->insert(
                    ['orderid' => $request->oid, 'claim_msg' => $request->msg, 'created_at' => date('Y-m-d H:i:s')]
            );
        }
        return response()->json(['ResponseCode' => 1, 'Description' => 'Success']);
    }

    public function tradeHistory() {
        return view('user.tradeHistory');
    }

    public function getListTrade(Request $request) {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        if (!$startDate || !$endDate)
            return response()->json('Dữ liệu không hợp lệ');
        $uid = Auth::guard('user')->user()->id;

        $query = DB::table('dbo_lottery')->select('id', 'day', 'picked_number', 'in_money', 'out_money', 'is_checked', 'created_at', 'code')
                ->where('userid', $uid)
                ->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime(str_replace('/', '-', $startDate))))
                ->where('created_at', '<=', date('Y-m-d 23:59:59', strtotime(str_replace('/', '-', $endDate))));

        $results = $query->latest()->get();
        return response()->json(['data' => $results]);
    }

    public function bonusHistory() {
        return view('user.bonusHistory');
    }

    public function getListBonus(Request $request) {
        $uid = Auth::guard('user')->user()->id;
        $ref_user = $request->ref_user;
        if ($ref_user) {
            $ref_user = DB::table('dbo_user')->where('username', $ref_user)->first();
            if ($ref_user) {
                $ref_user_id = $ref_user->id;
                $query = DB::table('dbo_freeze_balance')
                                ->select('bonus_money', 'description', 'dbo_freeze_balance.is_active', 'dbo_freeze_balance.created_at', 'dbo_user.username')
                                ->join('dbo_user', 'dbo_freeze_balance.user_id', '=', 'dbo_user.id')
                                ->where('dbo_freeze_balance.follow_user_id', $uid)->where('user_id', $ref_user_id);
            } else
                return response()->json(['data' => []]);
        } else
            $query = DB::table('dbo_freeze_balance')
                    ->select('bonus_money', 'description', 'dbo_freeze_balance.is_active', 'dbo_freeze_balance.created_at', 'dbo_user.username')
                    ->join('dbo_user', 'dbo_freeze_balance.user_id', '=', 'dbo_user.id')
                    ->where('dbo_freeze_balance.follow_user_id', $uid);

        $results = $query->latest()->get();
        return response()->json(['data' => $results]);
    }

    public function saveProfile(Request $request) {
        try {
            \DB::beginTransaction();
            $user = Auth::guard('user')->user();
            if ($user->status != 3) {
                if (!empty($request->fullname))
                    $user->fullname = $request->fullname;
                if (!empty($request->mobile))
                    $user->mobile = $request->mobile;
                if (!empty($request->passport))
                    $user->passport = $request->passport;
            }
            if (!empty($request->email))
                $user->email = $request->email;
            $user->save();
            \DB::commit();
            return response()->json(['ResponseCode' => 1, 'Description' => 'Thông tin tài khoản đã được lưu lại']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['ResponseCode' => -99, 'Description' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    public function getAvatarModalData() {
        $user = Auth::guard('user')->user();
        $title = "Thay ảnh đại diện";
        $url_img = Config::get('constants.avatar_default');
        if ($user->avatar && file_exists(public_path(Config::get('constants.avatar_path') . '/' . $user->username . '/' . $user->avatar))) {
            $url_img = Config::get('constants.avatar_path') . '/' . $user->username . '/' . $user->avatar;
        }
        $image = "<img id='imgPreview' src='" . url($url_img) . "'>";
        $body = "<div class='row mr-auto ml-auto ava-upload' id='ava_upload_form'>
                    <div class='col-md-3 text-center'>" . $image . "</div>
                    <div class='col-md-9'>
                        <div class='form-group'>
                            <label for='avaUploadFile'>File ảnh</label>
                            <div class='input-group'>
                                <input id='input_name' type='text' class='form-control' readonly>
                                <input id='input_upload' onchange='InputImgChange(this);' type='file' style='display:none;'>
                                <span class='input-group-append'>
                                    <button id='btn_select_file' onclick=" . "$('#input_upload').click();" . " class='btn btn-metal' type='button'>
                                        Chọn file
                                    </button>
                                </span>
                            </div>
                            <span class='invalid-feedback'></span>
                        </div>
                    </div>
                </div>";
        $footer = "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Hủy</button>
                    <button id='btn_upload' type='button' onclick='UploadAvatar();' class='btn btn-primary'>
                        <i class='la la-check'></i> Cập nhật
                    </button>";
        $data = array(
            'modal-title' => $title,
            'modal-body' => $body,
            'modal-footer' => $footer
        );
        return $data;
    }

    public function postChangeAvatar(Request $request) {
        try {
            $user = Auth::guard('user')->user();
            $base64Img = $request->base64Img;
            $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Img));

            $stringUtility = new StringHelper();
            $name = $stringUtility->getDateTimeString(new DateTime(), 'YmdHi') . '.png';
            $filepath = public_path(Config::get('constants.avatar_path') . '/' . $user->username);
            if (!\File::exists($filepath)) {
                \File::makeDirectory($filepath, 0777, true);
            }
            $fullPath = $filepath . '/' . $name;
            // Save the image in a defined path
            \File::put($fullPath, $data);

            \DB::beginTransaction();
            $user->update(['avatar' => $name]);
            \DB::commit();
            return response()->json(['ResponseCode' => 1, 'Description' => 'Đã thay đổi ảnh đại diện']);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json(['ResponseCode' => -99, 'Description' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    public function verifyAccount() {
        $userInfo = User::where('id', Auth::guard('user')->user()->id)->first();
        if ($userInfo->status == 1 || $userInfo->status == 3)
            return redirect()->route('index.user');
        if (!$userInfo->fullname || !$userInfo->passport)
            return view('user.verifyAccount', [
                'msg' => 'Vui lòng cập nhật họ tên và số chứng minh thư/ hộ chiếu'
            ]);
        return view('user.verifyAccount');
    }

    public function sendVerifyAccount(Request $request) {
        if (!$request->hasFile('verify1') || !$request->hasFile('verify2') || !$request->hasFile('verify3')) {
            return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu không hợp lệ']);
        }
        $this->validate($request, [
            'verify1' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            'verify2' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            'verify3' => 'required|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
        ]);

        $user = Auth::guard('user')->user();
        $filepath = public_path(Config::get('constants.account_verify_path') . '/' . $user->username);

        if (!\File::exists($filepath)) {
            \File::makeDirectory($filepath, 0777, true);
        }
        try {
            $file1 = $request->file('verify1');
            $name1 = (round(microtime(true)) * 1000) . '1.' . $file1->getClientOriginalExtension();
            $file1->move($filepath . '/', $name1);

            $file2 = $request->file('verify2');
            $name2 = (round(microtime(true)) * 1000) . '2.' . $file2->getClientOriginalExtension();
            $file2->move($filepath . '/', $name2);

            $file3 = $request->file('verify3');
            $name3 = (round(microtime(true)) * 1000) . '3.' . $file3->getClientOriginalExtension();
            $file3->move($filepath . '/', $name3);

            //Tim trong table xem da ton tai ban ghi user chua

            $verifyUser = DB::table('dbo_user_certificate')->where('uid', $user->id)->first();

            if ($verifyUser) {
                if ($verifyUser->status != 1)
                    return response()->json(['ResponseCode' => -1, 'Description' => 'Trạng thái tài khoản không hợp lệ']);
                else {
                    DB::beginTransaction();
                    DB::table('dbo_user_certificate')
                            ->where('id', $verifyUser->id)
                            ->update([
                                'status' => 0, 'created_at' => date('Y-m-d H:i:s'),
                                'images' => ($name1 . '|' . $name2 . '|' . $name3)
                    ]);
                }
            } else {
                DB::beginTransaction();
                DB::table('dbo_user_certificate')->insert(
                        ['uid' => $user->id, 'images' => ($name1 . '|' . $name2 . '|' . $name3), 'created_at' => date('Y-m-d H:i:s')]
                );
            }

            $user->update(['status' => 1]);
            DB::commit();
            return response()->json(['ResponseCode' => 1, 'Description' => 'Success!']);
        } catch (Exception $ex) {
            \DB::rollback();
            return response()->json(['ResponseCode' => -99, 'Description' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    public function accSecure() {
        $user = Auth::guard('user')->user();
        if (!$user->secretkey) {
            $google2fa = app('pragmarx.google2fa');
            $gg_secret_key = $google2fa->generateSecretKey();
            session(["secretkey" => encrypt($gg_secret_key)]);

            // Set the content-type
            header('Content-Type: image/png');
            // Create the image secret key
            $im = imagecreatetruecolor(330, 60);
            // Create some colors
            $black = imagecolorallocate($im, 0, 0, 0);
            $bg = imagecolorallocate($im, 239, 240, 241);
            imagefilledrectangle($im, 0, 0, 330, 60, $bg);
            // Replace path by your own font path
            $font = public_path('fonts/roboto.ttf');

            $google2fa_url = $google2fa->getQRCodeInline(
                    config('app.name'), $user->username, $gg_secret_key
            );
            // The text to draw
            $text = $gg_secret_key;
            // Add the text
            imagettftext($im, 20, 0, 20, 40, $black, $font, $text);

            //Temporary file secret key image
            $file = 'img/' . md5($text) . '.png';
            imagepng($im, $file);
            imagedestroy($im);

            $data = array(
                'google2fa_url' => $google2fa_url,
                'secret_key' => 'data:image/png;base64,' . base64_encode(file_get_contents($file))
            );
            unlink($file);
        }
        return view('user.accSecure', compact('data'));
    }

    public function enableSecure(Request $request) {
        try {
            if (!$request->verify_code) {
                return response()->json(['ResponseCode' => -600, 'Description' => 'Dữ liệu đầu vào không hợp lệ']);
            }
            \DB::beginTransaction();
            $user = Auth::guard('user')->user();
            $google2fa = app('pragmarx.google2fa');

            $secretkey = decrypt(session('secretkey'));
            if (!$google2fa->verifyKey($secretkey, $request->verify_code)) {
                return response()->json(['ResponseCode' => -1, 'Description' => 'Mã xác thực không đúng']);
            }
            //$secureMethod>>1:giao dich tieu, 2: Dang ky, 3: thanh toan co han muc
            $secureMethod = '1';
            if ($request->secureMethod)
                $secureMethod = $secureMethod . '|' . $request->secureMethod;

            $paymentLitmit = 0;
            if ($request->paymentLimit) {
                $paymentLitmit = (int) $request->paymentLimit;
            }
            $user->secretkey = session('secretkey');
            //Payment method
            $user->save();
            //insert vao bang user_secure
            DB::table('dbo_user_secure')->insert([
                'userid' => $user->id,
                'secure_method' => $secureMethod,
                'pay_limit' => $paymentLitmit]
            );
            \DB::commit();
            return response()->json(['ResponseCode' => 1, 'Description' => 'Đã kích hoạt bảo mật']);
        } catch (\Exception $e) {
            \DB::rollback();
            Log::channel('daily')->info("Ex: " . $e->getMessage());
            return response()->json(['ResponseCode' => -99, 'Description' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

    public function disableSecure(Request $request) {
        try {
            \DB::beginTransaction();
            $user = Auth::guard('user')->user();
            $google2fa = app('pragmarx.google2fa');

            $secretkey = decrypt($user->secretkey);
            if (!$google2fa->verifyKey($secretkey, $request->input('verify_code'))) {
                return response()->json(['ResponseCode' => -600, 'Description' => 'Mã xác thực không đúng']);
            }
            $user->secretkey = "";
            $user->save();
            DB::table('dbo_user_secure')->where('userid', $user->id)->delete();
            \DB::commit();
            return response()->json(['ResponseCode' => 1, 'Description' => 'Đã gỡ bỏ bảo mật']);
        } catch (\Exception $e) {
            \DB::rollback();
            Log::channel('daily')->info("Ex: " . $e->getMessage());
            return response()->json(['ResponseCode' => -99, 'Description' => 'Hệ thống đang bận, vui lòng thử lại sau']);
        }
    }

}
