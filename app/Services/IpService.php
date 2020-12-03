<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$myPublicIP = file_get_contents('https://api.ipify.org');
$botToken = '746178718:AAEwlyW7zpJgqWGjGKkOgKjT3wMfFEUMY5I';
//$chatId = '415331345';
$chatIdGroup = '-395668049';
$telegramBot = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatIdGroup}&text=Server IP: {$myPublicIP}";
//Set session
file_get_contents($telegramBot);

