<?
session_start();
require_once "tmpl/top.php";
?><h2>Регистрация нового пользователя</h2><div class=nav></div>
<form class=reg action='' method=post>
<b>Поля, отмеченные * обязательны к заполнению.</b><br><br>
<div>Логин <small>*</small></div><input class=i type=text name=login maxlength=10 size=10><br><br>
<div>Пароль <small>*</small></div><input class=i type=password name=pass><br><br>
<div>e-mail <small>*</small></div><input class=i type=text name=email><br><br>
<div>icq:</div><input class=i type=text name=icq maxlength=9 size=9><br><br>
<div>Реквизиты для выплат <small>*</small></div><input class=i type=text name=rekv><br><br>
<div>&nbsp;</div><img src="kcaptcha/kcaptcha.php?<?=session_name()?>=<?=session_id()?>"><br><br>
<div>Код на картинке</div><input class=i type="text" name="keystring" maxlength=6 size=6><br><br>
<input class=s type=submit name=submit value='Регистрация'> &nbsp;&nbsp;<input class=s type=reset value='Очистить'>
</form>
<?
if(isset($_POST['submit'])) {
        if(!empty($_POST['login']) and !empty($_POST['pass']) and !empty($_POST['email']) and !empty($_POST['rekv'])) {
                if(isset($_SESSION['captcha_keystring'])&&$_SESSION['captcha_keystring']==$_POST['keystring']){
                        unset($_SESSION['captcha_keystring']);
                        unset($_POST['keystring']);
                        $_POST['icq'] = intval($_POST['icq']);
                        $_POST['login'] = str_replace("\\","",mysql_escape_string(htmlspecialchars($_POST['login'])));
                        $_POST['pass'] = md5($_POST['pass']);
                        $_POST['email'] = str_replace("\\","",mysql_escape_string(htmlspecialchars($_POST['email'])));
                        $_POST['rekv'] = str_replace("\\","",mysql_escape_string(htmlspecialchars($_POST['rekv'])));
                        $query = mysql_query("SELECT login FROM `users` WHERE `login` = '$_POST[login]'");
                        if(mysql_num_rows($query)==0)                         {
                                $query = mysql_query("INSERT INTO `users` VALUES('','$_POST[login]','$_POST[pass]','$_POST[email]','$_POST[icq]','$_POST[rekv]','0','inactive','off')");
                                if($query) echo "<br><br>Регистрация прошла успешно. В течении 24 часов ваш аккаунт активирует администратор.";
                        } else echo "<br><br><div class=e>Данный логин уже занят</div>";
                } else echo "<br><br><div class=e>Код на картинке введен неверно</div>";
        } else echo "<br><br><span style='color:red'>Поля, отмеченные <small>*</small> обязательны к заполнению</span>";
}
require_once "tmpl/bottom.php";?>
