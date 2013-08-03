<? #sms prices form

function translit(){
list($str)=func_get_args();
return(str_replace(
array(' ','¸','æ','ö','÷','ø','ù','ş','ÿ','¨','Æ','Ö','×','Ø','Ù','Ş','ß',"ü","ú"),
array('-','yo','zh','tc','ch','sh','sh','yu','ya','YO','ZH','TC','CH','SH','SH','YU','YA','',''),
strtr($str,"ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏĞÑÒÓÔÕÚÛÜİàáâãäåçèéêëìíîïğñòóôõûı","ABVGDEZIJKLMNOPRSTUFH_I_Eabvgdezijklmnoprstufhye")
));
}

require_once("../config.php");

$q=mysql_query("select * from `sms_tarifs`");
if(mysql_num_rows($q)==0) exit("NO TARIFS");

#countries:operators:price
$c=array(); 

while($r=mysql_fetch_array($q)) {
  $i=translit($r['country']);
  if(!isset($c[$r['country']])) $c[$r['country']]=array('','','','');

  $o=translit($r['operator']);
  #operators:
  $c[$r['country']][0][]="'{$i}_{$o}':'{$r['operator']}'";
  #number
  $c[$r['country']][1][]="'{$i}_{$o}':{'{$o}_{$r['number']}':'{$r['number']}'}";
  #price:
  $c[$r['country']][2][]="'{$o}_{$r['number']}':{'{$r['price']} {$r['currency']}':'{$r['price']} {$r['currency']}'}";

  $c[$r['country']][3]="'{$i}':{";
  $c[$r['country']][4]=$i;

}

$operators='';
$country='';
$numbers='';
$prices='';

foreach($c as $n=>$s) {
  $country.="<option value=\"{$s[4]}\">{$n}</option>\n";
  $operators.="{$s[3]}\n".join(",\n",$s[0])."\n},\n";
  $numbers.=join(",\n",$s[1]).",\n";
  $prices.=join(",\n",$s[2]).",\n";
}

$prices=chop(str_replace(array('rur','uah'),array('ğóá','ãğí'),$prices),",\n");

mysql_close();
require_once("sms.php");?>