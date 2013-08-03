<?
#error_reporting(0);
session_start();
require_once "../config.php";
require_once "class.admin.php";
$admin=new admin();
$admin->CheckAuth();
$admin->dira = $dir;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head><title>Pro-bill &mdash; Управляющий терминал</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251"/>
<link href="admin.css" type=text/css rel=stylesheet>
<script language='javascript'>
function sets(i,v){
if(i==1){document.getElementById('m'+v).style.backgroundColor='#96D7FE';return;}
if(i==2){document.getElementById('m'+v).style.backgroundColor='#EAF2FB';return;}
}
</script>
</head>
<body>
<table width=100% class="table1">
<tr valign=top valign=left>
<td width=150>
<dl>
<dt><h1>Навигация</h1></dt>
<dt id='m0' onmouseover='sets(1,0)' onmouseout='sets(2,0)'><a href='?mod=index'>Главная</a></dt>
<div class=nav></div>
<dt id='m1' onmouseover='sets(1,1)' onmouseout='sets(2,1)'><a href='?mod=users'>Пользователи</a></dt>
<dt id='m2' onmouseover='sets(1,2)' onmouseout='sets(2,2)'><a href='?mod=payments'>Выплаты</a></dt>
<dt id='m3' onmouseover='sets(1,3)' onmouseout='sets(2,3)'><a href='?mod=tickets'>Тикеты</a></dt>
<dt id='m4' onmouseover='sets(1,4)' onmouseout='sets(2,4)'><a href='?mod=news'>Новости</a></dt>
<dt id='m5' onmouseover='sets(1,5)' onmouseout='sets(2,5)'><a href='?mod=links'>Сайты</a></dt>
<dt id='m6' onmouseover='sets(1,6)' onmouseout='sets(2,6)'><a href='?mod=settings'>Настройки</a></dt>
<dt id='m7' onmouseover='sets(1,7)' onmouseout='sets(2,7)'><a href="http://<?=$_SERVER['HTTP_HOST'];?>">Перейти на сайт</a></dt>
<dt id='m8' onmouseover='sets(1,8)' onmouseout='sets(2,8)'><a href='?mod=logout'>Выход</a></dt>
</dl>
</td>
<td id="page"><? $admin->main(); ?></td>
</tr>
</table>
<div class="footer">
<b>SMS-Affiliate 1.3</b> &copy; <a href="http://pro-bill.com" target=_blank>Probill</a> &mdash; Support: support@pro-bill.com &mdash; ICQ: 12345
</div>
</body>
</html>