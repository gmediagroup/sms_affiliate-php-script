<?
if(eregi("class.class.php", $_SERVER['PHP_SELF'])) die();

class core{
        public function redirect($where,$time){echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"$time; URL=$where\">";}

        public function check($what){return mysql_escape_string(htmlspecialchars($what));}
        
        public function main(){
            if(!isset($_GET['mod']) or empty($_GET['mod'])) $_GET['mod'] = "index";
            if(is_string($_GET['mod'])){               
                        switch($_GET['mod']){
                          case "index": $this->index();
                          break;
                          case "logout": $this->logout();
                          break;
                          case "promo": $this->promo();
                          break;
                          case "desk": $this->desk();
                          break;
                          case "profile": $this->profile();
                          break;
                          case "stat": $this->stat();
                          break;
                          default: $this->index();
                        }
             } else echo "������ �� ������.";
        }
        
        private function logout(){
                session_destroy();
                $this->redirect("./",0);
        }
        
        public function convertDate($d) {
                list($year,$month,$day) = explode("-",$d);
                if(substr($day,0,1)==0) $day = substr($day,1,1);
                $names = array("1"=>"������", "2"=>"�������", "3"=>"�����", "4"=>"������", "5"=>"���", "6"=>"����", "7"=>"����", "8"=>"�������", "9"=>"��������", "10"=>"�������", "11"=>"������", "12"=>"�������");
                return $day." ".$names[$month]." ".$year;
        }
        
  private function index() {
    $query = mysql_query("SELECT * FROM `users` WHERE `id` = '$_SESSION[login_id]'");
      if(mysql_num_rows($query)>0) {
          $r = mysql_fetch_array($query);
          echo "������������, <b>$r[login]</b>.<br>�������, ".$this->convertDate(date("20y-m-d"))." ����. ��� ������: <span style='font-weight:bold;color:red'>$r[balans]���.</span><div class=nav></div>";
      } else echo "<div class=e>������� �� ���������� ��� ��� ������������</div>";

      echo "<h2>�������:</h2>";
      if(!isset($_GET['pg']) or empty($_GET['pg'])) $_GET['pg'] = 1;
      $_GET['pg'] = intval($_GET['pg']);
      $from = ($_GET['pg']-1)*5;
      $query = mysql_query("SELECT * FROM `news`");
      $col = ceil(mysql_num_rows($query)/5);
      $query = mysql_query("SELECT * FROM `news` ORDER BY `id` DESC LIMIT $from,5");
      if(mysql_num_rows($query)>0) {
         while($r = mysql_fetch_array($query)) {
         echo "<b>$r[title]</b> �� $r[date]<br>$r[content]<br><br>";
      }
         echo "<br>��������: &nbsp;";
         for($i=1; $i<=$col; $i++) {
            if($i==$_GET['pg']) echo "$i &nbsp;&nbsp;&nbsp;";
            else echo "<a href=?mod=index&pg=$i>$i</a> &nbsp;&nbsp;&nbsp;";
         }
      } else echo "�������� ���.";
      echo "<div class=nav></div>";
  }
        
  private function promo() {
     echo "���� ����� ��� �����:<br><br><form class=reg method=get>";
     $query = mysql_query("SELECT * FROM `links`");
     if(mysql_num_rows($query)>0) {
        while($r = mysql_fetch_array($query)) {
           echo "<input class=i type=text value=".$r['link'].$_SESSION['login_id']." size=50><br><br>";
        }
      } else echo "<div class=e>�� ������ ������ ������ ���</div>";

      echo "</form>";
  }
        


  private function desk() {
    if(!isset($_GET['act'])) {
       echo "<a href=?mod=desk&act=new>������� �����</a><br><br><div class=nav></div>".
       "<h2>�������� ������:</h2>";
       $query = mysql_query("SELECT * FROM `d_topics` WHERE `user_id` = '$_SESSION[login_id]' AND `status` = 'open'");
       if(mysql_num_rows($query)>0) {
            while($r = mysql_fetch_array($query)) {
                echo "<a href=?mod=desk&act=view&id=$r[id]>$r[title]</a><br>";
            }
       } else echo "<div class=e1>������� ���</div>";


      echo "<div class=nav></div><h2>�������� ������:</h2><br>";
      $query = mysql_query("SELECT * FROM `d_topics` WHERE `user_id` = '$_SESSION[login_id]' AND `status` = 'close'");
      if(mysql_num_rows($query)>0) {
          while($r = mysql_fetch_array($query)){
             echo "$r[title] &nbsp;&nbsp;&nbsp;<a href=?mod=desk&act=open&id=$r[id]>�������</a><br>";
          }
      } else echo "<span class=e1>������� ���</span>";
    }


    else{
        if($_GET['act']=="new") {
           if(!isset($_POST['submit'])) {
               echo "<form class=reg action='' method=post>".
               "<div><b>��������</b></div><input class=i type=text name=title size=40><br>".
               "<div><b>�����</b><br><small>(html-���� ���������)</small></div><br><textarea class=i name=content cols=50 rows=10></textarea><br><br>".
               "<div>&nbsp;</div><img src=\"kcaptcha/kcaptcha.php?".session_name()."=".session_id()."\"><br><br>".
               "<div><b>��� �� ��������</b></div><input class=i type=text name=keystring maxlength=6 size=6><br><br><br>".
               "<input class=s type=submit name=submit value='�������'> &nbsp;&nbsp;<input class=s type=reset value='��������'>".
               "</form>";
           }
           else {
              if(!empty($_POST['title']) and !empty($_POST['content']) and !empty($_POST['keystring'])) {
                 if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring']) {
                      unset($_SESSION['captcha_keystring']);
                      unset($_POST['keystring']);
                      $_POST['title'] = $this->check($_POST['title']);
                      $_POST['content'] = $this->check($_POST['content']);
                      $hash = md5(time().$_POST['title']);
                      $d = date("20y-m-d");
                      $query = mysql_query("INSERT INTO `d_topics` VALUES('','$_POST[title]','$d','open','$_SESSION[login_id]','$hash')");
                      $query = mysql_query("SELECT * FROM `d_topics` WHERE `md5sum` = '$hash'");
                      $r = mysql_fetch_array($query);
                      $query = mysql_query("INSERT INTO `d_posts` VALUES('','$_SESSION[login_id]','$r[id]','$d','$_POST[content]')");
                      if($query) echo "����� ������� ������. � ������� 24 ����� ������������� ������� �� ��� ������.<br><a href=?mod=desk>�����</a>";
                      } else echo "<div class=e>��� �� �������� ������ �������.<br><a href=?mod=desk&act=new>�����</a></div>";
                  } else echo "<div class=e>�� ��� ���� ���������</div><br><a href='javascript:history.back()'>�����</a>";
             }
          }
          elseif($_GET['act']=="view") {
              if(isset($_GET['id']) and is_string($_GET['id'])) {       
                 $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                 $query = mysql_query("SELECT * FROM `d_topics` WHERE `id` = '$_GET[id]' AND `status` = 'open' AND `user_id` = '$_SESSION[login_id]'");
                 if(mysql_num_rows($query)>0) {
                     $r = mysql_fetch_array($query);
                     echo "<b>$r[title] �� $r[date]</b> &nbsp;&nbsp;[ <a href=?mod=desk&act=close&id=$r[id]>�������</a> ]<br><br>";
                     $query = mysql_query("SELECT * FROM `d_posts` WHERE `topic_id` = '$_GET[id]' ORDER BY `id`");
                     while($r = mysql_fetch_array($query)){
                         $query2 = mysql_query("SELECT * FROM `users` WHERE `id` = '$r[user_id]'");
                         $r2 = mysql_fetch_array($query2);
                         echo "$r[date], $r2[login] �����:<br>$r[content]<div class=nav></div>";
                     }       
                     echo "<form action='' method=post class=reg>".
                     "<div>����� ���������</div><textarea name=content cols=50 rows=10 class=i></textarea><br><br>".
                     "<div>��� �� ��������</div><input class=i type=text name=keystring maxlength=6 size=6><br><br>".
                     "<div>&nbsp;</div><img src=\"kcaptcha/kcaptcha.php?".session_name()."=".session_id()."\"><br><br>".
                     "<input class=s type=submit name=submit value='��������'></form>";

                   if(isset($_POST['submit'])) {
                       if(!empty($_POST['content']) and !empty($_POST['keystring'])) {
                          if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring']) {
                             unset($_SESSION['captcha_keystring']);
                             unset($_POST['keystring']);
                             $_POST['content'] = $this->check($_POST['content']);
                             $d = date("20y-m-d");
                             $query = mysql_query("INSERT INTO `d_posts` VALUES('','$_SESSION[login_id]','$_GET[id]','$d','$_POST[content]')");
                             if($query) $this->redirect($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING'],0);
                             } else echo "<div class=e>��� �� �������� ������ �������.<br><a href=?mod=desk&act=view&id=$_GET[id]>�����</a></div>";
                        } else echo "<div class=e>�� ������ ����� ���������</div>";
                    }                                               
                  } else echo "<div class=e>�������� ID</div><br><a href='javascript:history.back()'>�����</a>";
               } else echo "<div class=e>�� ������ ID</div><br><a href='javascript:history.back()'>�����</a>";
            }
          elseif($_GET['act']=="open") {
               if(isset($_GET['id']) and is_string($_GET['id'])) {
                  $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                  $query = mysql_query("SELECT * FROM `d_topics` WHERE `id` = '$_GET[id]' AND `status` = 'close' AND `user_id` = '$_SESSION[login_id]'");
                  if(mysql_num_rows($query)>0) {                       
                      $query = mysql_query("UPDATE `d_topics` SET `status` = 'open' WHERE `id` = '$_GET[id]'");
                      if($query) echo "����� ������� ������.<br><a href=?mod=desk>��������� �����</a>";
                  } else echo "<div class=e>�������� ID</div><br><a href='javascript:history.back()'>�����</a>";
                } else echo "<div class=e>�� ������ ID</div><br><a href='javascript:history.back()'>�����</a>";
          }
          elseif($_GET['act']=="close") {
               if(isset($_GET['id']) and is_string($_GET['id'])) {
                  $_GET['id'] = intval(mysql_escape_string($_GET['id']));
                  $query = mysql_query("SELECT * FROM `d_topics` WHERE `id` = '$_GET[id]' AND `status` = 'open' AND `user_id` = '$_SESSION[login_id]'");
                  if(mysql_num_rows($query)>0) {                       
                       $query = mysql_query("UPDATE `d_topics` SET `status` = 'close' WHERE `id` = '$_GET[id]'");
                       if($query) echo "����� ������� ������.<br><a href=?mod=desk>�����</a>";
                  } else echo "<div class=e>�������� ID</div><br><a href='javascript:history.back()'>�����</a>";
               } else echo "<div class=e>�� ������ ID</div><br><a href='javascript:history.back()'>�����</a>";
          } else echo "<div class=e>�������� �� �������</div><br><a href='javascript:history.back()'>�����</a>";
     }
 }
        
 private function profile(){
     $query = mysql_query("SELECT * FROM `users` WHERE `id` = '$_SESSION[login_id]'");
     if(mysql_num_rows($query)>0) {
     $r = mysql_fetch_array($query);
     if(!isset($_POST['submit'])) {                                       
        echo "<form class=reg action='' method=post>".
        "<div><b>�����</b></div><b>{$_SESSION['name']}</b><br><br>".
        "<div><b>������</b></div><input class=i type=text name=pass> <small>(�������� ���� ������, ���� ����� ������ �� ���������)</small><br><br>".
        "<div><b>e-mail</b></div><b>{$r['email']}</b><br><br>".
        "<div><b>ICQ</b></div><input class=i type=text name=icq size=10 value='$r[icq]'><br><br>".
        "<div><b>���������</b></div><input class=i type=text name=rekv size=50 value='$r[rekv]'><br><br><br>".
        "<Input class=s type=submit name=submit value=' ��������� '>".
        "</form>"; 
     }
     else {
         if(!empty($_POST['rekv'])) {
            $_POST['rekv'] = $this->check($_POST['rekv']);
            if(empty($_POST['icq'])) $_POST['icq'] = 0;
            else $_POST['icq'] = intval($this->check($_POST['icq']));
            if(!empty($_POST['pass'])) {
                $_POST['pass'] = md5($_POST['pass']);
                $query = mysql_query("UPDATE `users` SET `pass` = '$_POST[pass]', `icq` = '$_POST[icq]', `rekv` = '$_POST[rekv]' WHERE `id` = '$_SESSION[login_id]'");
            } else $query = mysql_query("UPDATE `users` SET `icq` = '$_POST[icq]', `rekv` = '$_POST[rekv]' WHERE `id` = '$_SESSION[login_id]'");
            if($query) echo "������ ������� ���������.<br><a href='javascript:history.back()'>�����</a>";
          } else echo "<div class=e>��������� �� ��� ������������ ����<br><a href='javascript:history.back()'>�����</a></div>";
     }
    } else echo "<div class=e>ID �� ������</div><br><a href='javascript:history.back()'>�����</a>";
 }
        
 private function stat() {
  if(!isset($_GET['act'])) {
    echo "<a href=?mod=stat&act=newpay>��������� �������</a><br><a href=?mod=stat&act=payhist>������� ������</a>";
    $q=mysql_query("SELECT * FROM `logs` WHERE `user_id` = '{$_SESSION['login_id']}' ORDER BY `date` DESC");

    $a=array();
    $traf=0;
    $sum=0;
    $ratio=0;

    while($r=mysql_fetch_array($q)){
       $r['sum']=round($r['sum'],2);

       if(!isset($a[$r['date']])) {
          $a[$r['date']]=array($r['date'],0,0);
       }

       $q2=mysql_query("select * from `traf` where `user_id`='{$_SESSION['login_id']}' and `date`='{$r['date']}'");
       if(mysql_num_rows($q2)>0) {
         $r2=mysql_fetch_row($q2);
         $a[$r['date']][1]=$r2[2];
         $traf+=$r2[2];
       }
       else $a[$r['date']][1]=0;

       $a[$r['date']][2]+=$r['sum'];
       $sum+=$r['sum'];
    }
    echo "<div class=nav></div><table cellpadding=4 cellspacing=1 bgcolor='#9BA0B9' border=0>
<tr align=left valign=middle bgcolor=#CBE2FE height=30><td width=130>&nbsp;<b>����</b></td><td width=200>&nbsp;<b>������</b></td><td width=120>&nbsp;<b>�����</b></td><td width=200>&nbsp;<b>����� (���)</b></td></tr>";

    $l=count($a);
    foreach($a as $date=>$v){

      $r=round($v[1]/$v[2]);
      $ratio+=$r;
      echo "<tr align=left valign=middle bgcolor='#ffffff'><td>&nbsp;{$date}</td>
<td>&nbsp;{$v[1]}</td>
<td>&nbsp;1:{$r}</td>
<td>&nbsp;{$v[2]}</td>
</tr>";
    }
    echo "<tr align=left valign=middle bgcolor=#ffffff><td>&nbsp;<b>�����</b></td><td>&nbsp;<b>{$traf}</b></td><td>&nbsp;<b>1:{$ratio}</b></td><td>&nbsp;<b>{$sum} ���.</td></tr></table>";

  }
  else {
    if($_GET['act']=="newpay") {
       $query=mysql_query("SELECT * FROM `users` WHERE `id` = '$_SESSION[login_id]'");
                   $r = mysql_fetch_array($query);
                   if($r['balans']>0) {
                       if(!isset($_POST['submit'])) {
                          echo "����� ����� ������� ����� ����������: �� <b>0 ���</b> �� <b>$r[balans]���.</b><form class=reg action='' method=post>".
                               "<br><br><div>����� �������</div><input class=i type=text name=sum><br><br>".
                               "<div>��� �� ��������</div><input class=i type=text name=keystring maxlength=6 size=6><br><br>".
                               "<div>&nbsp;</div><img src=\"kcaptcha/kcaptcha.php?".session_name()."=".session_id()."\"><br><br>".
                               "<input class=s type=submit name=submit value='���������'>".
                               "</form>";
                        }
                        else {
                           if(!empty($_POST['sum']) and !empty($_POST['keystring'])) {
                               if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] == $_POST['keystring']) {
                                   unset($_SESSION['captcha_keystring']);
                                   unset($_POST['keystring']);
                                   $_POST['sum'] = $this->check($_POST['sum']);
                                   if($_POST['sum']>0 and $_POST['sum']<=$r['balans']) {
                                       $d = date("20y-m-d");
                                       $query = mysql_query("INSERT INTO `pay` VALUES('','$_POST[sum]','$d','unchecked','$_SESSION[login_id]')");
                                       if($query) echo "��� ������ �� ������� ������ � ����� ���������� � ��������� �����.";
                                    } else echo "<div class=e>�������� ����� �������</div><br><a href='javascript:history.back()'>�����</a>";
                                } else echo "<div class=e>��� �� �������� ������ �������</div><br><a href='javascript:history.back()'>�����</a>";
                            } else echo "<div class=e>��������� �� ��� ������������ ����</div><br><a href='javascript:history.back()'>�����</a>";
                         }
                     } else echo "<div class=e>��� ������� ������� ������ ������ ���� ������ 0 ���</div>";
                }
                elseif($_GET['act']=="payhist") {
                       $query = mysql_query("SELECT * FROM `pay` WHERE `user_id` = '$_SESSION[login_id]' AND `status` = 'checked' ORDER BY `id` DESC");
                       if(mysql_num_rows($query)>0) {
                         while($r = mysql_fetch_array($query)){
                               echo "������� �� {$r['date']} �� ����� {$r['sum']} ���<br>";
                         }
                       } else echo "<div class=e>������ ���</div><br><a href='javascript:history.back()'>�����</a>";
                } else echo "<div class=e>�������� �� �������</div><br><a href='javascript:history.back()'>�����</a>";
            }
     }
}
?>