<? #sms tarifs (for cron)

function utf8_cp1251($str){
$table=array("\xD0\x81"=>"\xA8","\xD1\x91"=>"\xB8","\xD0\x8E"=>"\xA1","\xD1\x9E"=>"\xA2",
"\xD0\x84"=>"\xAA","\xD0\x87"=>"\xAF","\xD0\x86"=>"\xB2","\xD1\x96"=>"\xB3","\xD1\x94"=>"\xBA","\xD1\x97"=>"\xBF","\xD3\x90"=>"\x8C","\xD3\x96"=>"\x8D",
"\xD2\xAA"=>"\x8E","\xD3\xB2"=>"\x8F","\xD3\x91"=>"\x9C","\xD3\x97"=>"\x9D","\xD2\xAB"=>"\x9E","\xD3\xB3"=>"\x9F");
return preg_replace('#([\xD0-\xD1])([\x80-\xBF])#se','isset($table["$0"]) ? $table["$0"] : chr(ord("$2")+("$1" == "\xD0" ? 0x30 : 0x70))',$str);
}

require_once("../config.php");

$id=array(1163);

$tarifs=array();

$q=mysql_query("drop table `sms_tarifs`");
$q=mysql_query("create table `sms_tarifs` (`id` int(11) NOT NULL auto_increment,`country` text NOT NULL,`operator` text NOT NULL,`number` text NOT NULL,`message` text NOT NULL,`price` text NOT NULL,`currency` text NOT NULL,PRIMARY KEY  (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1");
if(!$q) exit(mysql_error());

foreach($id as $i) {
  $text=file_get_contents("http://profit-bill.com/api/get_api_tariffs_2?smsapi_id={$i}&secret={$sms_api}");
  if($text===false||strlen($text)<1) continue;

  $text=str_replace(array('<br />',"\r"),'',chop(utf8_cp1251($text)));

  $a=explode("\n",$text);
  unset($a[0]);

  foreach($a as $str) {
     $v=explode(";",$str);
     if(!is_array($v)||count($v)<3) continue;
     $q=mysql_query("insert into `sms_tarifs` values('','{$v[0]}','{$v[1]}','{$v[2]}','{$v[3]}','{$v[4]}','{$v[5]}')");
  }
}
mysql_close();

?>