<? #sms-affilliate install script (c) Pro-Bill.com 2010

#errors:
function _e($n){
exit("<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=windows-1251\">
<title>SMS-Affiliate Installer &mdash; ERROR!</title>
</head><body><div style='text-align:center;padding:10px;margin:50px 0 10px 0;font-size:15pt;font-family:Tahoma,Arial;border:2px solid #894641;background-color:#FFF3F2'>
������ ���������!<br>{$n}</div>
<br><br><center><a style='color:#000000;font-size:14pt;font-family:Arial' href=\"javascript:history.back()\">��������� �����</a>
</center></body></html>");
}
#checks:
if(!is_dir("soft")) _e("������� 'soft' �� ��� ������ � ����� ��������!");
if(!file_exists("soft/sql.sql")) _e("���� sql.sql �� ������ � ����� 'soft'");

$e='';
$m='';
#start (show form):
if(!isset($_GET)||!isset($_GET['start'])) $_POST=array('db_name'=>'','db_login'=>'','db_password'=>'','db_table'=>'','sms_api'=>'');

#install:
if(isset($_POST['db_name'])&&$_POST['db_name']!=''){

   foreach(array('db_name','db_password','db_login','sms_api') as $p) {
      if(!isset($_POST[$p])|| $_POST[$p]=='' || (strpos($_POST[$p],'@!$%&*()][|\/,^:')!==false))
      $e.="<span class=r>�� ������ �������� ��� ��������� '{$p}' ��� ������������ �������</span><br>";
   }
   #setup:
   if($e==''){
      #read dump from file:
      $dump=trim(@file_get_contents("soft/sql.sql"));
      if($dump===false||strlen($dump)<100) _e("<b>MYSQL</b>: ������ ������ ����� 'soft/sql.sql'");
      $dump=preg_replace("/(^-- .+$|\n)/Ums",'',$dump);
      #set prefix:
      #$dump=preg_replace("/CREATE TABLE `(.+)`/mUsi","CREATE TABLE `{$_POST['db_table']}_$1",$dump);
      $dump=explode(";",$dump);
      if(!is_array($dump)||count($dump)<10) _e("<b>MYSQL:</b> ������ � ����� ���� ������");
      #try to connect:
      $q=@mysql_connect('127.0.0.1',$_POST['db_login'],$_POST['db_password']);
      if(!$q) _e("<b>MYSQL</b>: ������ ����������� � ���� ������<br><br>".mysql_error());
      #init:
      foreach(array("SET NAMES cp1251","SET collation_connection='cp1251_general_ci'",
                    "drop database {$_POST['db_name']}") as $s){
         $q=@mysql_query($s);
      }
      #create database:
      $q=@mysql_query("create database {$_POST['db_name']} default charset=cp1251");
      if(!$q) _e("<b>MYSQL:</b> ������ �������� ���� '{$_POST['db_name']}'<br><br>".mysql_error());
      #try to select db:
      if(!@mysql_select_db($_POST['db_name'])) _e("MYSQL: ������ � ������ ���� '{$_POST['db_name']}'<br><br>".mysql_error());
      #load dump:
      foreach($dump as $s){
        $s=trim($s);
        if($s=='') continue;
        $q=@mysql_query($s);
        if(!$q) _e("<b>MYSQL:</b> ������ ��� �������� ����� � ����!<br><br>".mysql_error());
      }
      #close:
      $q=@mysql_close();
      $m="<b>SMS-Affliate Installer</b><hr noshade>���� ������ '{$_POST['db_name']}' �������<br>������� �������<br><br>\n";
      #store to config.php
      $str="<?
\$sms_api=\"{$_POST['sms_api']}\";
\$title=\"SMS-AFFILIATE\";
\$subtitle=\"������� Pro-bill.com\";
\$server=\"localhost\";
\$user=\"{$_POST['db_login']}\"; #login
\$pass=\"{$_POST['db_password']}\"; #password
\$db=\"{$_POST['db_name']}\"; #db_name
\$q=@mysql_connect(\$server,\$user,\$pass);
if(!\$q) die('������ ����������� � ���� ������');
if(!@mysql_select_db(\$db,\$q)) die('������ ��� ������ ���� ������');
\$dir=\"/\";
\$q=@mysql_query(\"SET NAMES cp1251\");
?>";
      $z=@fopen("config.php","w");
      $n=1;
      if($z){
        if(@fputs($z,$str)) {
           @fclose($z);
           $m.="������������ �������� � <b>config.php</b><br>\n";
           $n=2;
        }
      }
      #failed!
      if($n==1) {
        $m.="<b>������ ��� ������ �����!</b><br>�� ���� �������� � config.php<br>����� �������?<br>
���������� ���� ����������� ����� � �������� �������������� � ���� <b>./config.php</b><br><br>
<textarea cols=90 rows=15 style='font-size:12px;font-family:Tahoma,Arial;padding:4px;border:1px solid #9DB5CE;'>
{$str}</textarea>";
      }
      #finished:
?><html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>SMS-Affiliate Installer</title><style type="text/css">
body {font-family: Tahoma, Arial;font-size: 15pt;color: #000000;margin: 20px;}
</style></head><body><? exit("{$m}<br><br><a style='color:#00000;font-size:13pt;' href=\"/admin/index.php\">���� � ��������� �����</a></body></html>");
   }
}
#show form:
else $e="���������� �� ���������. �������� ������ �� �������� ���������.<br><br>
<b>��������!</b> ��� ��������� ������� ��������� ��������� ���� � ����� �� ������";

?><html><head><meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>SMS-Affiliate Installer</title><style type="text/css">
body {font-family: Tahoma, Arial;font-size: 15pt;color: #000000;margin-top: 20px;margin-right: 0px;margin-bottom: 10px;margin-left: 0px;}
.f1 {font-size: 14px;color: #000000;border: 1px solid #ACADCA;}
.f0 {color: #000000;cursor: hand;border-bottom-width: 2px;border-bottom-style: dashed;border-bottom-color: #FFCBC4;}
.f2 {font-family: Arial, Helvetica, sans-serif;font-size: 14px;color: #000000;background-color: #EAF8FF;padding: 2px;border: 2px solid #95A8C6;}
.f3 {border: 2px solid #9DB5CE;}
.f4 {font-size: 12px;color: #000033;background-color: #EEF3F7;margin-top: 100px;margin-right: 10px;margin-bottom: 10px;margin-left: 10px;border-top-width: 1px;border-top-style:solid;border-top-color: #9DB5CE;padding: 4px 10px;text-align: center;}
.f5 {color: #234FC2;text-decoration:none;}
.r {color:#B40C07;font-weight:bold;}
#i {font-size: 12px;color: #000000;text-align: center;padding: 10px 4px;margin-top: 50px;margin-right: 80px;margin-bottom: 0px;margin-left: 80px;border: 1px solid #BBC9D9;height: 60px;}</style>
<script language="Javascript">
var info=new Array(
'<b>������� ��������� ��� ������ � ����� MySQL ������� SMS-Affiliate</b>',
'<b>��� ���� ������</b> - ������� ��� ��� ���� ������ ��� ������� ����� ���� mysql<br>��������: ���� ���� ����������, ��� ����� �������!',
'<b>�����</b> - ������� ����� ��� ������� � ���� ������',
'<b>������</b> - ������� ������ ��� ������� � ���� ������',
'<b>SMS API</b> - ��������� ���� ������� � SMS_API Profit-bill.com',
''
);
function h(n){if(n==undefined) {alert('�� ������ �������������');return;}document.getElementById('i').innerHTML=info[n];}
</script></head><body><form name="form1" method="post" action="install.php?start=1">
<table border="0" align="center" cellpadding="4" cellspacing="0" bgcolor="#F9FDFF" class="f3">
<tr align="center" valign="middle" bgcolor="#C0E6FE">
<td colspan="2">SMS-Affiliate Installer </td>
</tr>
<tr align="center" valign="middle">
<td height="50" colspan="2"><span class=f0 onmouseover="h(0)">Database information</span></td>
</tr>
<tr align="left" valign="top">
<td width="150" align="right"><span class=f0 onmouseover="h(1)">Name</span></td>
<td width="250"><input name="db_name" type="text" class="f1" value="<?=$_POST['db_name']?>" size="30" maxlength="60">
</td>
</tr>
<tr align="left" valign="top">
<td align="right"><span class=f0 onmouseover="h(2)">Login</span></td>
<td><input name="db_login" type="text" class="f1" value="<?=$_POST['db_login']?>" size="30" maxlength="60">
</td>
</tr>
<tr align="left" valign="top">
<td align="right"><span class=f0 onmouseover="h(3)">Password</span></td>
<td><input name="db_password" type="text" class="f1" value="<?=$_POST['db_password']?>" size="30" maxlength="60">
</td>
</tr>

<tr align="left" valign="top">
<td align="right"><span class=f0 onmouseover="h(4)">SMS API Key</span></td>
<td><input name="sms_api" type="text" class="f1" value="<?=$_POST['sms_api']?>" size="30" maxlength="60"></td>
</tr>

<tr align="left" valign="top"><td colspan="2">&nbsp;</td></tr>
<tr align="center" valign="middle"><td height="50" colspan="2"><input name="ok" type="submit" class="f2" value="INSTALL"></td></tr>
</table></form>
<div id="i"><?=$e;?></div>
<div class="f4">
SMS-Affiliate 1.3 Scripts &copy; <strong><a class=f5 target=_blank href="http://profit-bill.com">Profit-Bill.com</a></strong> 2009,2010 &middot; <a href="http://gm-group.biz/index.php" target=_blank class="f5">GM-Group Forum for webmasters</a></div>
</body></html>