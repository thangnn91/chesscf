<?php

namespace App\Utility;

use DateTime;

class StringHelper {

    function CutString($start, $end, $str) {
        $x1 = strpos($str, $start);
        if ($x1) {
            $x2 = strpos($str, $end, $x1 + 1);
            $getbet = substr($str, $x1 + strlen($start), $x2 - $x1 - strlen($start));
        } else {
            $getbet = "";
        }
        return $getbet;
    }
    
    function randomString($len = 6, $isLowerCase = false) {
        $seed = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($isLowerCase)
            $seed = '0123456789ABCDEFGHIabcdefghijklJKLMNOPQRSTUVWXYZmnopqrstuvwxyz';
        return substr(str_shuffle(str_repeat($seed, $len)), 0, $len);
    }
    function randomOTP($len = 6) {
        $seed = '0123456789';
        return substr(str_shuffle(str_repeat($seed, $len)), 0, $len);
    }

    function getDateTimeString($dateTime, $format) {
        $res = $dateTime->format($format);
        if ($res)
            return $res;
        return (new DateTime())->format($format);
    }

    function cardMasking($cardNumber, $maskingCharacter = '*') {
        $cardLen = strlen($cardNumber);
        return substr_replace($cardNumber, str_repeat($maskingCharacter, ($cardLen - 3)), 0, ($cardLen - 3));
    }

    function accountHolderMasking($accountHolder, $maskingCharacter = '*') {
        if (!$accountHolder)
            return '';

        $arrayAccountHolder = explode(" ", $accountHolder);
        for ($x = 0; $x < (count($arrayAccountHolder) - 1); $x++) {
            $arrayAccountHolder[$x] = str_repeat($maskingCharacter, strlen($arrayAccountHolder[$x]));
        }
        return implode(" ", $arrayAccountHolder);
    }

    function moneyFormat($priceFloat, $addSymbol = false, $symbol_thousand = '.') {
        $symbol = 'vnđ';
        $decimal_place = 0;
        $price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
        if ($addSymbol)
            return $price . ' ' . $symbol;
        else
            return $price;
    }

    function getUserIP() {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        return $ip;
    }

    public static function slugify($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }

}
