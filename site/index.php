<?
#error_reporting(0);
if(isset($_GET['id'])){
    $_GET['id']=intval($_GET['id']);
    if(!isset($_COOKIE['refer_id']) and $_GET['id']>0) setcookie("refer_id",$_GET['id']);

    #учет трафика для адвертов
    require_once("../config.php"); # указать путь до этого файла

    $d=date("Y-m-d");
    $q=mysql_query("select * from `traf` where `date`='{$d}' and `user_id`='{$_GET['id']}'");
    if(mysql_num_rows($q)>0) {
      $r=mysql_fetch_row($q);
      $r[2]++;
      $q=mysql_query("update `traf` set `count`='{$r[2]}' where `user_id`='{$_GET['id']}' and `date`='{$d}'");
    }
    else {
      $q=mysql_query("insert into `traf` values('','{$d}','1','{$_GET['id']}')");
    }

    mysql_close();
}
?>
<html>
<head>
<title>пример сайта</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body>
<h3>Пример платного сайта</h3>
<form action='members.php' method='GET'>
Ваш пароль: <input type=text name=p><br>
<input type=submit name=submit value='Войти'>
</form>
</body>
</html>