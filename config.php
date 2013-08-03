<?
$sms_api="a89z40pr2d";
$title="SMS-AFFILIATE";
$subtitle="Биллинг Pro-bill.com";
$server="localhost";
$user="root"; #login
$pass="qqq"; #password
$db="test"; #db_name
$q=@mysql_connect($server,$user,$pass);
if(!$q) die('Ошибка подключения к базе данных');
if(!mysql_select_db($db,$q)) die('Ошибка при выборе базы даныых');
$dir="/";
$q=@mysql_query("SET NAMES cp1251");
?>