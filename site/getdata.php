<?php
error_reporting(0);
require_once("../config.php"); #указать путь до файла

if($secret_key && $_GET['key']!=md5($secret_key.$_GET['id'])) die('hacking attempt');

if(isset($_GET['cost_rur']) and !empty($_GET['cost_rur'])) {
    if($_GET['cost_rur']>0) {
       $m=array('b','d','f','g','h','j','k','m','n','q','r','s','t','v','w','x','z','1','2','3','4','5','6','7','8','9');
       $pass="";
       for($i=0; $i<=4; $i++) {
         $pass.=$m[rand(0,count($m)-1)];
       }

       $pass2=md5($pass);
       $q=mysql_query("INSERT INTO `passlog` VALUES('','".date("Y-m-d")."','{$_GET['cost_rur']}','{$pass2}');");
       mysql_close();
       echo "reply\n{$pass}";
    }
}
?> 