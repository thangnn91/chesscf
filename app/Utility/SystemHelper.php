<?php

namespace App\Utility;
use Config;
use App\Utility\CookieHelper;
class SystemHelper {

    function checkGoogleCaptcha($captcha) {
        if (!$captcha)
            return false;
        $cookieHelper = new CookieHelper();
        $url = "https://www.google.com/recaptcha/api/siteverify?secret=" . (Config::get('app.g_captcha_secret_key')) . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR'];       
        
        $response = json_decode($cookieHelper->curl($url,null,null,null,1), true);
        if ($response['success'] == false)
            return false;
        return true;
    }
    
    function getNextLotteryDial($code) {
        $dialDays = [4, 6, 8];
        $dialDaysText = ['Wed', 'Fri', 'Sun'];

        if ($code === Config::get('constants.xsmb_code') ||
                $code === Config::get('constants.lomienbac_code') ||
                $code === Config::get('constants.demienbac_code') ||
                $code === Config::get('constants.lomienbac2_code') ||
                $code === Config::get('constants.lomienbac3_code') ||
                $code === Config::get('constants.lomienbac4_code')) {
            $dialDays = [2, 3, 4, 5, 6, 7, 8];
            $dialDaysText = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        }

        $today = date("Y-m-d");
        $number = date('N', strtotime($today)) + 1;
        if (in_array($number, $dialDays)) {
            $lastTime = "17:45:00";
            $time_diff = strtotime($today . ' ' . $lastTime) - time();
            //Truoc 17h thi la ngay hom nay
            if ($time_diff > 0) {
                return date('d/m/Y');
            }
            $lastTime = "19:00:00";
            $time_diff = strtotime($today . ' ' . $lastTime) - time();
            //Sau 19h thi la ngay tiep theo
            if ($time_diff < 0) {
                $index = array_search($number, $dialDays);
                $nextDayText = $index === (count($dialDays) - 1) ? $dialDaysText[0] : $dialDaysText[$index + 1];

                return $nextDayText ? date('d/m/Y', strtotime('next ' . $nextDayText)) : '';
            }
            //trong khoang 17h-19h thi ko cho choi
            return "";
        } else {
            $nextDayText = '';
            for ($i = 0; $i < 3; $i++) {
                if ($dialDays[$i] > $number) {
                    $nextDayText = $dialDaysText[$i];
                    break;
                }
            }
            return $nextDayText ? date('d/m/Y', strtotime('next ' . $nextDayText)) : '';
        }
    }
}
