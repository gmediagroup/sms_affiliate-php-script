<?
#error_reporting(0);
session_start();

require_once "tmpl/top.php";
require_once "class.core.php";
$core=new core();

if(!session_is_registered("logon") and !isset($_SESSION['name'])) {
    if(!isset($_POST['submit'])) {
        echo "Для доступа введите ваш логин и пароль<div class=nav></div><form action='' method=post class=f1>".
             "<div>Логин:</div><input type=text name=login>".
             "<div>Пароль:</div><input type=password name=pass><br><br>".
             "<input type=submit name=submit value='Войти'></form>";
        }
        else {
            if(!empty($_POST['login']) and !empty($_POST['pass'])){
                  $_POST['login'] = $core->check($_POST['login']);
                  $_POST['pass'] = md5($_POST['pass']);
                  $query = mysql_query("SELECT * FROM `users` WHERE `login` = '$_POST[login]' AND `pass` = '$_POST[pass]' AND `status` = 'active'");
                  if(mysql_num_rows($query)>0) {
                      $r = mysql_fetch_array($query);
                      session_register("logon");
                      $_SESSION['name'] = $r['login'];
                      $_SESSION['login_id'] = $r['id'];
                      $core->redirect("/",0);
                  } else echo "<div class=e>Неверный логин или пароль, либо ваш аккаунт еще не активирован</div><br><a class=f3 href='javascript:history.back()'>Назад</a>";
            } else echo "<div class=e>Не указан логин или пароль</div><br><a class=f3 href='javascript:history.back()'>Назад</a>";
        }
}
else {
   $_SESSION['login_id'] = $core->check($_SESSION['login_id']);
   if(!is_string($_SESSION['login_id'])) exit();
   $_SESSION['name'] = $core->check($_SESSION['name']);
   $core->main();
}

require_once "tmpl/bottom.php";
?>