<?
function _e($n){
die('<html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251"><title>������</title>
<body><div style="font-size:16pt;font-family:Tahoma,Arial;border:1px solid #E89B9B;padding:6px;background-color:#FCF3F3;"><b style="color:red">������</b><br><br>'.$n.
'<br><br>&laquo;&laquo; <a href="javascript:history.back()" style="color:#000000">�����</a></div></body></html>');
}

if(eregi("class.admin.php", $_SERVER['PHP_SELF'])) die();

class admin{
        var $dira;
        public function redirect($where,$time) {echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$time; URL=$where\">";}
        public function check($what) {return mysql_escape_string(htmlspecialchars($what));}
        public function CheckAuth() {
            if(!session_is_registered("adminka") and !isset($_SESSION['login'])) {
            if(!isset($_POST['submit'])){
?><meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<link href="admin.css" type=text/css rel=stylesheet>
<div id="ctr" align="center">
<div class="login">
<div class="login-form">
<form action="" method="post" name="loginForm" id="loginForm">
<div class="form-block">
<div class="inputlabel">�����</div>
<div><input name="login" type="text" class="inputbox" size="15" /></div>
<div class="inputlabel">������</div>
<div><input name="pass" type="password" class="inputbox" size="15" /></div>
<div align="left"><input  title=" ������� ���� ����� ����� ����� � ������ " type="submit" name="submit" class="button" value="�����" /></div>
</div>
</form>
</div>
<div class="login-text">
<div class="ctr"><img src="img/security.png" width="64" height="64" alt="security" /></div>
<p>����� ����������!</p>
<p>������� ��� � ������ ��� ������� � ������ ����������.</p>
</div>
<div class="clr"></div>
</div>
</div>
<div align='center' style='padding:10px;border-top:1px solid #ccc;margin:50px 80px 0 80px;font-size:12px;font-family:Tahoma'> Sms-affiliate 1.3 &copy; 
<a href='http://probill.com' target=_blank style='color:#000000'>Pro-bill.com</a> 2010
 Admin panel  &mdash; Support: support@probill.com</div>
<?
}else {
       if(!empty($_POST['login']) and !empty($_POST['pass'])){
          $_POST['login'] = $this->check($_POST['login']);
          $_POST['pass'] = md5($_POST['pass']);
          $query = mysql_query("SELECT admin_pass,admin_login FROM `config` WHERE `admin_pass` = '$_POST[pass]' AND `admin_login` = '$_POST[login]'");
          if(mysql_num_rows($query)==0){
             session_register("adminka");
             $_SESSION['login']=$_POST['login'];
             $this->redirect("./?mod=index",0);
          } else _e("�������� ����� ��� ������");
       } else _e("��������� �� ��� ����");
    }
  exit();
  }
}
        
public function main(){

       if(!isset($_GET['mod']) or empty($_GET['mod'])) $_GET['mod'] = "index";
       if(is_string($_GET['mod'])) { 
           switch($_GET['mod']) {
             case "index": $this->index();
             break;
             case "settings": $this->settings();
             break;
             case "logout": $this->logout();
             break;
             case "news": $this->news();
             break;
             case "users": $this->users();
             break;
             case "links": $this->links();
             break;
             case "tickets": $this->tickets();
             break;
             case "payments": $this->payments();
             break;
             default: $this->index();
       }
  } else echo "������ �� ������.";
}
        
        private function logout()
        {
                session_destroy();
                $this->redirect("./",0);
        }
        
        public function convertDate($d)
        {
                list($year,$month,$day) = explode("-",$d);
                if(substr($day,0,1)==0) $day = substr($day,1,1);
                if(substr($month,0,1)==0) $month = substr($month,1,1);
                $names = array("1"=>"������", "2"=>"�������", "3"=>"�����", "4"=>"������", "5"=>"���", "6"=>"����", "7"=>"����", "8"=>"�������", "9"=>"��������", "10"=>"�������", "11"=>"������", "12"=>"�������");
                return $day." ".$names[$month]." ".$year;
        }
        
        private function index(){
                echo "<fieldset style=\"width:400px;padding:10px;\"><legend style='background-color:#E6F3FF;padding:0 10px 0 10px'><font size=3>����� ����������, <font color='#3273A3'>".htmlspecialchars($_SESSION['login'])."</font></font></legend>".
                "<div style='margin:10px'>�������: &nbsp;<b>".$this->convertDate(date("20y-m-d"))."</b><br>".
                "�����: &nbsp;<b>".date("H:i:s")."</b><br></div>".
                "</fieldset>";
        }
        
        private function settings(){
                $query = mysql_query("SELECT * FROM `config`");
                $r = mysql_fetch_array($query);
                echo "��������� ������� � ������������ ���������:<br><br><form action='' method=post>".
                "�����: <input class=f1 type=text name=login value='$r[admin_login]'><br><br>".
                "������: <input class=f1 type=password name=pass> (�������� ���� ������, ���� ����� ������ �� ���������)<br><br>".
                "<input type=submit  class=f2 name=submit value='���������'>".
                "</form>";
                if(isset($_POST['submit']))
                {
                        echo "<br><br>";
                        if(!empty($_POST['login']))
                        {
                                if(!empty($_POST['pass'])) $_POST['pass'] = md5($_POST['pass']);
                                $_POST['login'] = $this->check($_POST['login']);
                                if(empty($_POST['pass'])) $query = mysql_query("UPDATE `config` SET `admin_login` = '$_POST[login]'");
                                else $query = mysql_query("UPDATE `config` SET `admin_login` = '$_POST[login]', `admin_pass` = '$_POST[pass]'");
                                if($query) echo "������ ������� ��������.";
                                $this->redirect("?mod=settings",2);
                        } else echo "�� ������ �����.";
                }
                echo "<br><br><form action='' method=post>";
                echo "������� ���������� ���������: <input class=f1 type=text name=proc value='$r[proc]'> %<br><br>";
                echo "<input type=submit class=f2 name=submit2 value='���������'>";
                echo "</form>";
                if(isset($_POST['submit2'])) {
                        echo "<br><br>";
                        if(!empty($_POST['proc'])){
                                $_POST['proc'] = $this->check($_POST['proc']);
                                $query = mysql_query("UPDATE `config` SET `proc` = '$_POST[proc]'");
                                if($query) echo "������ ������� ��������.";
                                $this->redirect("?mod=settings",2);
                        } else echo "<div class=e>�� ������ ������� ����������</div>";
                }
        }
        
        private function news(){
                if(!isset($_GET['act'])){
                        echo "<a href=?mod=news&act=new>�������� ����� �������</a><br><br>";
                        if(!isset($_GET['pg']) or empty($_GET['pg'])) $_GET['pg'] = 1;
                        $_GET['pg'] = intval($_GET['pg']);
                        $from = ($_GET['pg']-1)*15;
                        $query = mysql_query("SELECT * FROM `news`");
                        $col = ceil(mysql_num_rows($query)/15);
                        $query = mysql_query("SELECT * FROM `news` ORDER BY `id` DESC LIMIT $from,15");
                        if(mysql_num_rows($query)>0){
                                echo "<table>".
                                "<tr class=header2><td align=center width=450><b>��� �������</b></td><td align=center width=400><b>��������</b></td></tr>";
                                while($r = mysql_fetch_array($query)){                                                                       
                                   echo "<tr><td align=center>".$r['title']."</td><td align=center><a href=?mod=news&act=edit&id=".$r['id'].">�������������</a> | <a href=?mod=news&act=del&id=".$r['id'].">�������</a></td></tr>";
                                }
                                echo "</table><br><br>��������: &nbsp;";
                                for($i=1; $i<=$col; $i++) {
                                        if($i==$_GET['pg']) echo "$i &nbsp;&nbsp;&nbsp;";
                                        else echo "<a href=?mod=news&pg=$i>$i</a> &nbsp;&nbsp;&nbsp;";
                                }
                        } else echo "�������� ���";
                }
                else {
                        if($_GET['act']=="new"){
                                if(!isset($_POST['submit'])){
                                        echo "<a href=?mod=news>��������� � ���������� ���������</a><br><br>".
                                        "<form action='' method=post>".
                                        "<table>".
                                        "<tr cleass=header1><td algin=left width=250><b>��� �������:</b></td><td align=left width=700><input class=f1 type=text name=title size=60></td></tr>".
                                        "<tr><td algin=left valign=top><b>����� �������:</b></td><td align=left><textarea class=f1 name=content rows=\"30\" cols=\"80\"></textarea></td></tr>".
                                        "<tr><td colspan=2><Input class=f2 type=submit name=submit value='�������� �������' class=vvod></td></tr>".
                                        "</table></form>";
                                }
                                else{
                                        if(!empty($_POST['title']) and !empty($_POST['content'])){
                                                $_POST['title'] = str_replace("\\","",$this->check($_POST['title']));
                                                $date = date("20y-m-d");
                                                $query = mysql_query("INSERT INTO `news` VALUES('','$_POST[title]','$_POST[content]','$date')");
                                                if($query) echo "������� ������� ���������.<br><a href=?mod=news&act=new>��������� �����</a>";
                                        } else echo "��� ���� ���������� ���������.<br><a href=# onClick='history.back()'>��������� �����</a>";
                                }                               
                        }
                        elseif($_GET['act']=="del"){
                                $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                                $query = mysql_query("SELECT * FROM `news` WHERE `id` = '$_GET[id]'");
                                if(mysql_num_rows($query)>0){
                                        $r = mysql_fetch_array($query);
                                        echo "<form action='' method=post>".
                                        "������� ������� {$r['title']}?<br><br><br>
<input class=f2 type=submit name=yes value='��'> &nbsp;&nbsp;
<input class=f2 type=submit name=no value='���'></form>";

                                        if(isset($_POST['yes'])){
                                                $query = mysql_query("DELETE FROM `news` WHERE `id` = '$_GET[id]'");
                                                if($query) $this->redirect("?mod=news",0);
                                        }
                                        if(isset($_POST['no'])){
                                                if($query) $this->redirect("?mod=news",0);
                                        }
                                } else echo "<div class=e>�������� ID</div>";
                        }
                        elseif($_GET['act']=="edit"){
                                if(isset($_GET['id'])){
                                        $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                                        $query = mysql_query("SELECT * FROM `news` WHERE `id` = '$_GET[id]'");
                                        if(mysql_num_rows($query)>0){
                                                $r = mysql_fetch_array($query);
                                                if(!isset($_POST['submit'])){
                                                        echo "<a href=?mod=news>��������� � ���������� ���������</a><br><br>".
                                                        "<form action='' method=post>".
                                                        "<table>".
                                                        "<tr class=header2><td algin=left width=250><b>��� �������:</b></td><td align=left width=700><input class=f1 type=text name=title size=60 value='{$r['title']}'></td></tr>".
                                                        "<tr><td algin=left valign=top><b>����� �������:</b></td><td align=left><textarea class=f1 name=content rows=\"30\" cols=\"80\">{$r['content']}</textarea></td></tr>".
                                                        "<tr><td colspan=2><Input class=f2 type=submit name=submit value='���������' class=vvod></td></tr>".
                                                        "</table>";
                                                        "</form>";                         
                                                }
                                                else {
                                                        if(!empty($_POST['title']) and !empty($_POST['content'])){
                                                                $_POST['title'] = $this->check($_POST['title']);
                                                                $date = date("20y-m-d");
                                                                $query = mysql_query("UPDATE `news` SET `title` = '$_POST[title]', `content` = '$_POST[content]', `date` = '$date' WHERE `id` = '$_GET[id]'");
                                                                if($query) echo "��������� ������� ���������.<br><a href=?mod=news>��������� �����</a>";
                                                        } else echo "<div class=e>��� ���� ���������� ���������.<br><a href='javascript:history.back()'>�����</a></div>";
                                                }
                                        }
                                } else echo "<div class=e>�� ������ ID</div>"; 
                        } else echo "<div class=e>�������� �� �������</div>";
                }
        }
        
        private function users(){
                if(!isset($_GET['act'])){
                        echo "<center><h2>������������ ��������� ���������</h2></center>".
                        "<table>
<tr class='header2'>
<td align=center width=150><b>�����</b></td>
<td align=center width=200><b>e-mail</b></td>
<td align=center width=80><b>ICQ</b></td>
<td align=center width=250><b>���������</b></td>
<td align=center width=250><b>��������</b></td></tr>";

                        $query=mysql_query("SELECT * FROM `users` WHERE `status` = 'inactive' ORDER BY `id` DESC");
                        if(mysql_num_rows($query)>0){
                                while($r = mysql_fetch_array($query)){
                                   if($r['icq']==0) $r['icq'] = "-";
                                   echo "<tr><td align=center>{$r['login']}</td><td align=center>{$r['email']}</td><td align=center>{$r['icq']}</td><td align=center>{$r['rekv']}</td><td align=center><a href=?mod=users&act=activate&id={$r['id']}>������������</a> | <a href=?mod=users&act=edit&id={$r['id']}>�������������</a> | <a href=?mod=users&act=del&id={$r['id']}>�������</a></td></tr>";
                                }
                        } else echo "<tr><td colspan=5 align=center><b>������������� ���</b></td></tr>";
                        echo "</table><br><br><center><h2>�������������� ������������</h2></center><table>".
                        "<tr class='header2'><td align=center width=150><b>�����</b></td><td align=center width=70><b>������</b></td><td align=center width=70><b>\"����.\"</b></td><td align=center width=200><b>e-mail</b></td><td align=center width=80><b>ICQ</b></td><td align=center width=250><b>���������</b></td><td align=center width=350><b>��������</b></td></tr>";
                        $query = mysql_query("SELECT * FROM `users` WHERE `status` = 'active' ORDER BY `id` DESC");
                        if(mysql_num_rows($query)>0) {
                                while($r = mysql_fetch_array($query)) {
                                        if($r['icq']==0) $r['icq'] = "-";
                                        if($r['debug']=="on") $r['debug'] = "<font color=red>on</font>"; else $r['debug'] = "<font color=green>off</font>";
                                        echo "<tr><td align=center>{$r['login']}</td>
<td align=center>{$r['balans']} ���</td>
<td align=center>{$r['debug']}</td>
<td align=center>{$r['email']}</td>
<td align=center>{$r['icq']}</td>
<td align=center>{$r['rekv']}</td>
<td align=center><a href=?mod=payments&act=new&id={$r['id']}>�������</a> 
<a href=?mod=users&act=deactivate&id={$r['id']}>��������������</a> 
<a href=?mod=users&act=edit&id={$r['id']}>�������������</a> 
<a href=?mod=users&act=del&id={$r['id']}>�������</a></td></tr>";

                                }
                        } else echo "<tr><td colspan=7 align=center>������������� ���</td></tr>";
                        echo "</table>";
                }
                else
                {
                        if($_GET['act']=="del")
                        {
                                $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                                $query = mysql_query("SELECT login,id FROM `users` WHERE `id` = '$_GET[id]'");
                                if(mysql_num_rows($query)>0)
                                {
                                        $r = mysql_fetch_array($query);
                                        echo "<form action='' method=post>".
                                        "������� ������������ {$r['login']}?<br><br><input type=submit class=f2 name=yes value='��'> &nbsp;&nbsp;<input class=f2 type=submit name=no value='���'>".
                                        "</form>";
                                        if(isset($_POST['yes']))
                                        {
                                                $query = mysql_query("DELETE FROM `users` WHERE `id` = '$_GET[id]'");
                                                $query = mysql_query("DELETE FROM `pay` WHERE `user_id` = '$_GET[id]'");
                                                $query = mysql_query("DELETE FROM `d_topics` WHERE `user_id` = '$_GET[id]'");
                                                $query = mysql_query("DELETE FROM `d_posts` WHERE `user_id` = '$_GET[id]'");
                                                if($query) $this->redirect("?mod=users",0);
                                        }
                                        if(isset($_POST['no'])){
                                                if($query) $this->redirect("?mod=users",0);
                                        }
                                } else echo "<div class=e>�������� ID</div>";
                        }
                        elseif($_GET['act']=="activate") {
                                $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                                $query = mysql_query("SELECT * FROM `users` WHERE `id` = '$_GET[id]'");
                                if(mysql_num_rows($query)>0) {                       
                                        $query = mysql_query("UPDATE `users` SET `status` = 'active' WHERE `id` = '$_GET[id]'");
                                        if($query) echo "������������ ������� �����������.<br><a href=?mod=users>�����</a>";
                                } else echo "<div class=e>�������� ID</div>";
                        }
                        elseif($_GET['act']=="deactivate")
                        {
                                $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                                $query = mysql_query("SELECT * FROM `users` WHERE `id` = '$_GET[id]'");
                                if(mysql_num_rows($query)>0)
                                {                       
                                        $query = mysql_query("UPDATE `users` SET `status` = 'inactive' WHERE `id` = '$_GET[id]'");
                                        if($query) echo "������������ ������� �������������.<br><a href=?mod=users>��������� �����</a>";
                                } else echo "<div class=e>�������� ID</div>";
                        }
   elseif($_GET['act']=="edit"){
          $_GET['id'] = intval(mysql_escape_string($_GET['id']));         
          $query = mysql_query("SELECT * FROM `users` WHERE `id` = '$_GET[id]'");
          if(mysql_num_rows($query)>0){
             $r = mysql_fetch_array($query);
             if(!isset($_POST['submit'])){                                       
                echo "<a href=?mod=users>��������� � ���������� ��������������</a><br><br>".
                "<form action='' method=post>".
                "<table>".
                "<tr><td class=header2 align=left width=250><b>�����:</b></td><td align=left width=700><input class=f1 type=text name=login size=60 value='$r[login]'></td></tr>".
                "<tr><td class=header2 align=left width=250><b>������:</b></td><td align=left width=700><input class=f1 type=text name=pass size=60> (<small>�������� ���� ������, ���� ����� ������ �� ���������</small>)</td></tr>".
                "<tr><td class=header2 align=left width=250><b>e-mail:</b></td><td align=left width=700><input class=f1 type=text name=email size=60 value='$r[email]'></td></tr>".
                "<tr><td class=header2 align=left width=250><b>ICQ:</b></td><td align=left width=700><input class=f1 type=text name=icq size=60 value='$r[icq]'></td></tr>".
                "<tr><td class=header2 align=left width=250><b>���������:</b></td><td align=left width=700><input class=f1 type=text name=rekv size=60 value='$r[rekv]'></td></tr>".
                "<tr><td class=header2 align=left width=250><b>������:</b></td><td align=left width=700><input class=f1 type=text name=balans size=60 value='$r[balans]'> $</td></tr>".
                "<tr><td class=header2 align=left width=250><b>������:</b></td><td align=left width=700><select class=f1 name=status>";
                if($r['status']=="active") echo "<option value=active selected>�����������</option><option value=inactive>�� �����������</option>";
                else echo "<option value=active>�����������</option><option value=inactive selected>�� �����������</option>";
                echo "</select></td></tr>".
                "<tr><td class=header1 algin=left width=250><b>\"����\":</b></td><td align=left width=700><select name=debug>";
                if($r['debug']=="on") echo "<option value=on selected>ON</option><option value=off>OFF</option>";
                else echo "<option value=on>ON</option><option value=off selected>OFF</option>";
                echo "</select></td></tr>".
                "<tr><td colspan=2><Input class=f2 type=submit name=submit value='���������' class=vvod></td></tr>";
                "</table>";
                "</form>"; 
              }
            else {
              if(!empty($_POST['login']) and !empty($_POST['email']) and !empty($_POST['rekv']) and !empty($_POST['status']) and !empty($_POST['debug'])) {
                 if($_POST['status']=="active" or $_POST['status']=="inactive") {
                    if($_POST['debug']=="on" or $_POST['debug']=="off") {
                         if(!empty($_POST['pass'])) {
                            $_POST['pass'] = md5($_POST['pass']);
                            $query = mysql_query("UPDATE `users` SET `login` = '$_POST[login]', `pass` = '$_POST[pass]', `email` = '$_POST[email]', `icq` = '$_POST[icq]', `rekv` = '$_POST[rekv]', `balans` = '$_POST[balans]', `status` = '$_POST[status]', `debug` = '$_POST[debug]' WHERE `id` = '$_GET[id]'");
                          } else $query = mysql_query("UPDATE `users` SET `login` = '$_POST[login]', `email` = '$_POST[email]', `icq` = '$_POST[icq]', `rekv` = '$_POST[rekv]', `balans` = '$_POST[balans]', `status` = '$_POST[status]', `debug` = '$_POST[debug]' WHERE `id` = '$_GET[id]'");
                          if($query) echo "������ ������� ���������.<br><a href=?mod=users>��������� �����</a>";
                     } else echo "<div class=e>�������� �������� �������</div>";
                 } else echo "<div class=e>�������� �������� �������</div>";
               } else echo "<div class=e>��������� �� ��� ������������ ���������</div>";
            }
        } else echo "<div class=e>ID �� ������</div>";
    } else echo "<div class=e>�������� �� �������</div>";
 }
}
        
        private function links()
        {
                if(!isset($_GET['act']))
                {
                        echo "<a href=?mod=links&act=new><b>�������� ����� ����</b></a><br><br>";
                        $query = mysql_query("SELECT * FROM `links` ORDER BY `id` DESC");
                        if(mysql_num_rows($query)>0) {
                                echo "<table>";
                                echo "<tr class=header2><td align=center width=450><b>Link</b></td><td align=center width=400><b>��������</b></td></tr>";
                                while($r = mysql_fetch_array($query)) {                                                                       
                                        echo "<tr><td align=center><a href=$r[link] target=_blank>".$r['link']."</a></td><td align=center><a href=?mod=links&act=edit&id=".$r['id'].">�������������</a> | <a href=?mod=links&act=del&id=".$r['id'].">�������</a></td></tr>";                                    
                                }
                                echo "</table>";
                        } else echo "<div class=e>������ ���</div>";
                }
                else {
                     if($_GET['act']=="new") {
                                if(!isset($_POST['submit'])) {
                                     echo "<a href=?mod=links>����� � ���������� �������</a><br><br>".
                                     "<form action='' method=post>".
                                     "<table>".
                                     "<tr class=header1><td algin=left width=250><b>Link:</b></td><td align=left width=700><input type=text name=link size=60> (������ �����: http://site.ru/from.php?id=)</td></tr>".
                                     "<tr><td colspan=2><Input class=f2 type=submit name=submit value='�������� ����' class=vvod></td></tr>".
                                     "</table>".
                                     "</form>";
                                }
                                else{
                                  if(!empty($_POST['link'])){
                                         $_POST['link'] = str_replace("\\","",$this->check($_POST['link']));
                                         $query = mysql_query("INSERT INTO `links` VALUES('','$_POST[link]')");
                                         if($query) echo "���� ������� ��������.<br><a href=?mod=links&act=new>��������� �����</a>";
                                   } else echo "<div class=e>��� ���� ���������� ���������.<br><a href='javascript:history.back()'>�����</a></div>";
                                }                               
                      }
                      elseif($_GET['act']=="del"){
                                $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                                $query = mysql_query("SELECT * FROM `links` WHERE `id` = '$_GET[id]'");
                                if(mysql_num_rows($query)>0) {
                                        $r = mysql_fetch_array($query);
                                        echo "<form action='' method=post>".
                                        "�������� �����:<br><b>{$r['link']}</b><br><br><br><input class=f2 type=submit name=yes value='��'> &nbsp;&nbsp;<input class=f2 type=submit name=no value='���'>".
                                        "</form>";
                                        if(isset($_POST['yes'])){
                                           $query = mysql_query("DELETE FROM `links` WHERE `id` = '$_GET[id]'");
                                           if($query) $this->redirect("?mod=links",0);
                                        }
                                        if(isset($_POST['no'])){
                                           if($query) $this->redirect("?mod=links",0);
                                        }
                                } else echo "<div class=e>�������� ID</div>";
                       }
                       elseif($_GET['act']=="edit") {
                           if(isset($_GET['id'])) {
                              $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                              $query = mysql_query("SELECT * FROM `links` WHERE `id` = '$_GET[id]'");
                              if(mysql_num_rows($query)>0) {
                                  $r = mysql_fetch_array($query);
                                  if(!isset($_POST['submit'])) {
                                     echo "<a href=?mod=links>��������� � ���������� �������</a><br><br>".
                                     "<form action='' method=post>".
                                     "<table>".
                                     "<tr class=header2><td algin=left width=250><b>Link:</b></td><td align=left width=700><input class=f1 type=text name=link size=60 value='$r[link]'> (������ �����: http://site.ru/from.php?id=)</td></tr>".
                                     "<tr><td colspan=2><Input class=f2 type=submit name=submit value='���������' class=vvod></td></tr>".
                                     "</table>".
                                     "</form>";                         
                                   }
                                   else {
                                      if(!empty($_POST['link'])) {
                                          $_POST['link'] = str_replace("\\","",$this->check($_POST['link']));
                                          $query = mysql_query("UPDATE `links` SET `link` = '$_POST[link]' WHERE `id` = '$_GET[id]'");
                                          if($query) echo "��������� ������� ���������.<br><a href=?mod=links>��������� �����</a>";
                                       } else echo "��� ���� ���������� ���������.<br><a href=# onClick='history.back()'>��������� �����</a>";                         
                                   }
                              }
                          } else echo "<div class=e>�� ������ ID</div>";
                        } else echo "<div class=e>�������� �� �������</div>";
                }
        }
        
private function tickets() {

 if(!isset($_GET['act'])){
    $query = mysql_query("SELECT * FROM `d_topics` ORDER BY `id` DESC, `status`");
    if(mysql_num_rows($query)>0) {
       echo "<table>";
       echo "<tr class=header1><td align=center width=450><b>��� ������</b></td><td align=center width=70><b>������</b></td><td align=center width=150><b>�����</b></td><td align=center width=60><b>������</b></td><td align=center width=200><b>��������</b></td></tr>";
       while($r = mysql_fetch_array($query)){                                                       
          $query2 = mysql_query("SELECT * FROM `users` WHERE `id` = '$r[user_id]'");
          $login = mysql_fetch_array($query2);
          if($r['status']=="open") echo "<tr><td align=center><a href=?mod=tickets&act=view&id=$r[id]>".$r['title']."</a></td><td align=center>$r[date]</td><td align=center>".$login['login']."</td><td align=center>$r[status]</td><td align=center><a href=?mod=tickets&act=close&id=".$r['id'].">�������</a> | <a href=?mod=tickets&act=edit&id=".$r['id'].">�������������</a> | <a href=?mod=tickets&act=del&id=".$r['id'].">�������</a></td></tr>";
          else echo "<tr><td align=center><a href=?mod=tickets&act=view&id=$r[id]>".$r['title']."</a></td><td align=center>$r[date]</td><td align=center>".$login['login']."</td><td align=center>$r[status]</td><td align=center><a href=?mod=tickets&act=open&id=".$r['id'].">�������</a> | <a href=?mod=tickets&act=edit&id=".$r['id'].">�������������</a> | <a href=?mod=tickets&act=del&id=".$r['id'].">�������</a></td></tr>";
       }
       echo "</table>";
    } else echo "<div class=e>������� ���</div>";
 }
 else {
    if($_GET['act']=="del") {
       $_GET['id'] = intval(mysql_escape_string($_GET['id']));
       $query = mysql_query("SELECT * FROM `d_topics` WHERE `id` = '$_GET[id]'");
       if(mysql_num_rows($query)>0) {
         $r = mysql_fetch_array($query);
         echo "<form action='' method=post>";
         echo "������� ����� $r[title]?<br><input class=f2 type=submit name=yes value='��'> &nbsp;&nbsp;<input class=f2 type=submit name=no value='���'>";
         echo "</form>";
         if(isset($_POST['yes'])) {
           $query = mysql_query("DELETE FROM `d_topics` WHERE `id` = '$_GET[id]'");
           $query = mysql_query("DELETE FROM `d_posts` WHERE `topic_id` = '$_GET[id]'");
           if($query) $this->redirect("?mod=tickets",0);
         }
         elseif(isset($_POST['no'])) {
           if($query) $this->redirect("?mod=tickets",0);
         }
         } else echo "<div class=e>�������� ID</div>";
     }
     elseif($_GET['act']=="open") {
        $_GET['id'] = intval(mysql_escape_string($_GET['id']));
        $query = mysql_query("SELECT * FROM `d_topics` WHERE `id` = '$_GET[id]'");
        if(mysql_num_rows($query)>0) {                       
               $query = mysql_query("UPDATE `d_topics` SET `status` = 'open' WHERE `id` = '$_GET[id]'");
               if($query) echo "����� ������� ������.<br><a href=?mod=tickets>��������� �����</a>";
        } else echo "�������� ID.";                             
     }
     elseif($_GET['act']=="close") {
        $_GET['id'] = intval(mysql_escape_string($_GET['id']));
        $query = mysql_query("SELECT * FROM `d_topics` WHERE `id` = '$_GET[id]'");
        if(mysql_num_rows($query)>0){                       
           $query = mysql_query("UPDATE `d_topics` SET `status` = 'close' WHERE `id` = '$_GET[id]'");
           if($query) echo "����� ������� ������.<br><a href=?mod=tickets>��������� �����</a>";
        } else echo "�������� ID.";     
     }
     elseif($_GET['act']=="edit") {
        if(isset($_GET['id'])) {
           $_GET['id'] = intval(mysql_escape_string($_GET['id']));
           $query = mysql_query("SELECT * FROM `d_topics` WHERE `id` = '$_GET[id]'");
           if(mysql_num_rows($query)>0) {
             $r = mysql_fetch_array($query);
             if(!isset($_POST['submit'])) {
                echo "<a href=?mod=tickets>��������� � ���������� ��������</a><br><br>".
                "<form action='' method=post>".
                "<table>".
                "<tr><td algin=left width=250><b>��� ������:</b></td><td align=left width=700><input type=text name=title class=f1 size=60 value='$r[title]'></td></tr>".
                "<tr><td colspan=2><Input class=f2 type=submit name=submit value='���������' class=vvod></td></tr>".
                "</table>".
                "</form>";
              }
              else {
               if(!empty($_POST['title'])) {
                  $_POST['title'] = str_replace("\\","",$this->check($_POST['title']));
                  $query = mysql_query("UPDATE `d_topics` SET `title` = '$_POST[title]' WHERE `id` = '$_GET[id]'");
                  if($query) echo "��������� ������� ���������.<br><a href=?mod=tickets>��������� �����</a>";
                  } else echo "��� ���� ���������� ���������.<br><a href=# onClick='history.back()'>��������� �����</a>";                         
              }
           }
        } else echo "�� ������ ID.";
     }
     elseif($_GET['act']=="view") {
        echo "<a href=?mod=tickets>��������� � ���������� ��������</a><br><br>";
        $_GET['id'] = intval(mysql_escape_string($_GET['id']));
        $query = mysql_query("SELECT * FROM `d_topics` WHERE `id` = '$_GET[id]'");
        $r = mysql_fetch_array($query);
        echo "<b>$r[title] �� $r[date]</b><br><br>";
        $query = mysql_query("SELECT * FROM `d_posts` WHERE `topic_id` = '$_GET[id]' ORDER BY `id`");
        while($r = mysql_fetch_array($query)){
             $query2 = mysql_query("SELECT * FROM `users` WHERE `id` = '$r[user_id]'");
             $r2 = mysql_fetch_array($query2);
             echo "$r[date], $r2[login] �����: &nbsp;&nbsp;[ <a href=?mod=tickets&act=delpost&id=$r[id]>�������</a> ]<br>$r[content]<br><br>";
        }
        echo "<br><br><form action='' method=post>".
        "����� ���������: <br><textarea class=f1 name=content cols=70 rows=20></textarea><br><br>".
        "<input class=f2 type=submit name=submit value='��������'>".
        "</form>";
        if(isset($_POST['submit'])) {
            if(!empty($_POST['content'])) {
              $_POST['content'] = $this->check($_POST['content']);
              $d = date("20y-m-d");
              $query = mysql_query("INSERT INTO `d_posts` VALUES('','1','$_GET[id]','$d','$_POST[content]')");
              if($query) $this->redirect($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'],0);
            } else echo "<div class=e>�� ������ ����� ���������</div>";
         }
     }
     elseif($_GET['act']=="delpost") {
         $_GET['id'] = intval(mysql_escape_string($_GET['id']));
         $query = mysql_query("SELECT * FROM `d_posts` WHERE `id` = '$_GET[id]'");
         $r = mysql_fetch_array($query);
         if(mysql_num_rows($query)>0){
            $query = mysql_query("DELETE FROM `d_posts` WHERE `id` = '$_GET[id]'");
            if($query) $this->redirect("?mod=tickets&act=view&id=$r[topic_id]",0);
         } else echo "<div class=e>ID �� ������</div>";
     } else echo "<div class=e>�������� �� �������</div>";
  }

}
        
        private function payments()
        {
                if(!isset($_GET['act']))
                {
                        echo "<h2>������� ������������� �� �������:</h2>";
                        echo "<table>";
                        echo "<tr class=header1 align=center><td width=200><b>������������</b></td><td width=200><b>���������</b></td><td width=110><b>������� ������</b></td><td width=110><b>����� �������</b></td><td width=100><b>���� �������</b></td><td width=230><b>��������</b></td></tr>";
                        $query = mysql_query("SELECT * FROM `pay` WHERE `status` = 'unchecked' ORDER BY `id` DESC");
                        if(mysql_num_rows($query)>0)
                        {
                                $r = mysql_fetch_array($query);
                                $query2 = mysql_query("SELECT login,balans,rekv FROM `users` WHERE `id` = '$r[user_id]'");
                                $r2 = mysql_fetch_array($query2);
                                echo "<tr align=center><td>$r2[login]</td><td>$r2[rekv]</td><td>$r2[balans] ���.</td><td>$r[sum] ���.</td><td>$r[date]</td><td><a href=?mod=payments&act=pay&id=$r[id]>���������</a> | <a href=?mod=payments&act=edit&id=$r[id]>�������������</a> | <a href=?mod=payments&act=del&id=$r[id]>�������</a></td></tr>";
                        } else echo "<tr><td align=center colspan=6>������ ���</td></tr>";
                        echo "</table><br><br><h2>������� ������:</h2><table>".
                        "<tr class=header1 align=center><td width=200><b>������������</b></td><td width=110><b>����� �������</b></td><td width=110><b>���� �������</b></td><td width=90><b>��������</b></td></tr>";
                        $query = mysql_query("SELECT * FROM `pay` WHERE `status` = 'checked' ORDER BY `id` DESC");
                        if(mysql_num_rows($query)>0){
                                while($r = mysql_fetch_array($query)){
                                        $query2 = mysql_query("SELECT login FROM `users` WHERE `id` = '$r[user_id]'");
                                        $r2 = mysql_fetch_array($query2);
                                        echo "<tr align=center><td>$r2[login]</td><td>$r[sum]</td><td>$r[date]</td><td><a href=?mod=payments&act=del&id=$r[id]>�������</a></td></tr>";
                                }
                        } else echo "<tr><td align=center colspan=4>������ ���</td></tr>";
                        echo "</table>";
                }
                else {
                   if($_GET['act']=="del"){
                       $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                       $query = mysql_query("SELECT * FROM `pay` WHERE `id` = '$_GET[id]'");
                       $r = mysql_fetch_array($query);
                       if(mysql_num_rows($query)>0){
                            $query = mysql_query("DELETE FROM `pay` WHERE `id` = '$_GET[id]'");
                            if($query) $this->redirect("?mod=payments",0);
                       } else echo "<div class=e>ID �� ������</div>";
      }
      elseif($_GET['act']=="edit"){
          if(isset($_GET['id'])){
              $_GET['id'] = intval(mysql_escape_string($_GET['id']));
              $query = mysql_query("SELECT * FROM `pay` WHERE `id` = '$_GET[id]'");
              if(mysql_num_rows($query)>0){
                 $r = mysql_fetch_array($query);
                 $query2 = mysql_query("SELECT * FROM `users` WHERE `id` = '$r[user_id]'");
                 $r2 = mysql_fetch_array($query2);
                 if(!isset($_POST['submit'])){
                    echo "<a href=?mod=payments>����� � ���������� ���������</a><br><br>".
                    "<form action='' method=post>".
                    "<table>".
                    "<tr class=header1><td algin=left width=250><b>����� �������:</b></td><td align=left width=700><input class=f1 type=text name=sum size=60 value='{$r['sum']}'> ���.</td></tr>".
                    "<tr><td colspan=2><Input class=f2 type=submit name=submit value='���������' class=vvod></td></tr>".
                    "</table></form>";                         
    } else {
      if(!empty($_POST['sum'])) {
           $_POST['sum'] = $this->check($_POST['sum']);
           if($_POST['sum']>0 and $_POST['sum']<=$r2['balans']) {       
             $query = mysql_query("UPDATE `pay` SET `sum` = '$_POST[sum]' WHERE `id` = '$_GET[id]'");
             if($query) echo "��������� ������� ���������.<br><a href=?mod=payments>��������� �����</a>";
           } else echo "�������� ����� �������.<br><a href=# onClick='history.back()'>��������� �����</a>";
       } else echo "��� ���� ���������� ���������.<br><a href=# onClick='history.back()'>��������� �����</a>";                         
    }
  }
 } else echo "<div class=e>�� ������ ID</div>";
}

elseif($_GET['act']=="pay") {
   if(isset($_GET['id'])) {
     $_GET['id'] = intval(mysql_escape_string($_GET['id']));
     $query = mysql_query("SELECT * FROM `pay` WHERE `id` = '$_GET[id]'");
     if(mysql_num_rows($query)>0) {
        $r = mysql_fetch_array($query);
        $query = mysql_query("UPDATE `pay` SET `status` = 'checked' WHERE `id` = '$_GET[id]'");
        $query = mysql_query("UPDATE `users` SET `balans` = balans - $r[sum] WHERE `id` = '$r[user_id]'");
        if($query) $this->redirect("?mod=payments",0);
     } else echo "<div class=e>�������� ID</div>";
   }
 }
elseif($_GET['act']=="new") {
   if(isset($_GET['id'])) {
      $_GET['id'] = intval(mysql_escape_string($_GET['id']));
      $query = mysql_query("SELECT * FROM `users` WHERE `id` = '$_GET[id]'");
      if(mysql_num_rows($query)>0) {
          $r = mysql_fetch_array($query);
          $query2 = mysql_query("SELECT * FROM `pay` WHERE `user_id` = '$_GET[id]'");
          $r2 = mysql_fetch_array($query2);
          if(!isset($_POST['submit'])) {
            echo "<a href=?mod=users>��������� � ���������� �������������</a><br>".
            "<a href=?mod=payments>��������� � ���������� ���������</a><br><br>".
            "����� ������� ��� <b>{$r['login']}</b> ����� ���� �� 0 �� {$r['balans']} ���.<br><br><form action='' method=post>".
            "<table>".
            "<tr class=header2><td algin=left width=250><b>����� �������:</b></td><td align=left width=700><input class=f1 type=text name=sum size=60> $</td></tr>".
            "<tr><td colspan=2><Input class=f2 type=submit name=submit value='���������' class=vvod></td></tr>".
            "</table>".
            "</form>";                         
          }
        else {
         if(!empty($_POST['sum'])) {
             $_POST['sum'] = $this->check($_POST['sum']);
             if($_POST['sum']>0 and $_POST['sum']<=$r['balans']) {       
                  $d = date("20y-m-d");
                  $query = mysql_query("INSERT INTO `pay` VALUES('','$_POST[sum]','$d','checked','$_GET[id]')");
                  $query = mysql_query("UPDATE `users` SET `balans` = balans - $_POST[sum] WHERE `id` = '$_GET[id]'");
                  if($query) echo "������� ������� ���������.<br><a href=?mod=payments>��������� �����</a>";
             } else echo "�������� ����� �������.<br><a href=# onClick='history.back()'>��������� �����</a>";
         } else echo "��� ���� ���������� ���������.<br><a href=# onClick='history.back()'>��������� �����</a>";                         
       }
     }                                               
   } else echo "<div class=e�� ������ ID</div>";
 } else echo "<div class=e>�������� �� �������</div>";
}
}
}
?>