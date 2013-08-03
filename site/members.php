<?
error_reporting(E_ALL);
require_once("../config.php"); #указать путь до файла!

if(isset($_GET['p']) && !empty($_GET['p'])) {

        $_GET['p']=md5($_GET['p']);
        $q=mysql_query("SELECT * FROM `passlog` WHERE `pass` = '{$_GET['p']}'");
        if(mysql_num_rows($q)==0) die("неверный пароль");

        $r=mysql_fetch_array($q);

        if(isset($_COOKIE['refer_id'])) {
           $q=mysql_query("select * from `goodpass` where `pass` = '{$_GET['p']}'");
           if(mysql_num_rows($q)==0) {
              $q=mysql_query("insert into `goodpass` values('','{$r['cost_rur']}','{$_GET['p']}','{$_COOKIE['refer_id']}')");
           }

           # здесь нужно прописать url до up33date.php который находится в папке с партнеркой.
           $url=file_get_contents("http://192.168.5.7/work/sms/up33date.php?id={$_COOKIE['refer_id']}&sum={$r['cost_rur']}");
         }

        mysql_close();

} else die("неверный пароль");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<title>секретная часть сайта</title>

</head>
<body>
<h3>секретная часть сайта</h3>
</body></html>