<?php

$alphabet = "0123456789abcdefghijklmnopqrstuvwxyz"; # do not change without changing font files!
$allowed_symbols = "0123456789"; #digits
$fontsdir = 'fonts';    
$length = 6;
$width = 110;
$height = 50;
$fluctuation_amplitude = 5;
$no_spaces = true;
$show_credits = false;
$credits = 'www.captcha.ru'; # if empty, HTTP_HOST will be shown
$foreground_color = array(mt_rand(0,100), mt_rand(0,100), mt_rand(0,100));
$background_color = array(mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));
$jpeg_quality = 90;
?>