<?
$sms_api="a89z40pr2d";
$title="SMS-AFFILIATE";
$subtitle="������� Pro-bill.com";
$server="localhost";
$user="root"; #login
$pass="qqq"; #password
$db="test"; #db_name
$q=@mysql_connect($server,$user,$pass);
if(!$q) die('������ ����������� � ���� ������');
if(!mysql_select_db($db,$q)) die('������ ��� ������ ���� ������');
$dir="/";
$q=@mysql_query("SET NAMES cp1251");
?>