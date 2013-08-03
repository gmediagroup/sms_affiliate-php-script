<?
#error_reporting(0);
require_once "config.php";
if(isset($_GET['id']) and isset($_GET['sum'])) {
    $_GET['id']=intval(mysql_escape_string($_GET['id']));
    $query=mysql_query("SELECT * FROM `users` WHERE `id` = '$_GET[id]'");
    if(mysql_num_rows($query)>0) {
         $r=mysql_fetch_array($query);
         if($r['status']=="active" and $r['debug']=="off") {
             $query2 = mysql_query("SELECT * FROM `config`");
             $r2 = mysql_fetch_array($query2);
             $real = $_GET['sum']/100*$r2['proc'];
             $d = date("20y-m-d");
             $query=mysql_query("UPDATE `users` SET `balans` = balans + $real WHERE `id` = '$_GET[id]'");
             $query=mysql_query("INSERT INTO `logs` VALUES('','{$d}','{$real}','{$_GET['id']}')");
         }
    } 
}
?>