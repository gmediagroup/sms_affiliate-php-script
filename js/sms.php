<!--- sms form --->
<script type="text/javascript" src="linkedselect.js"></script>
<script type="text/javascript" src="pass.js"></script>
<style type="text/css">
.select_country{width:115px; font-size:12px; color:#000000; height:250px;border:1px solid #77A6F2;}
.select_oper{width:260px; font-size:12px; color:#000000; height:250px;border:1px solid #5F8EDE;}
.select_number{width:100px; font-size:12px; color:#000000; height:250px;border:1px solid #3C77BF;}
.select_cost{width:80px; font-size:12px; color:#000000;height:250px;border:1px solid #74B8DC;}
.select_table {font-family: Tahoma, Arial;font-size: 12px;color: #000000;border:1px solid #8FD0FC;}
.sms_form_s {border: 1px solid #6C8FBB;font-size: 12px;color: #000000;background-color: #D8F8FE;padding: 1px;font-weight: bold;}
.sms_form_f {color: #000000;border: 1px solid #6F8BC6;}
</style>
<table border="0" align="center" cellpadding="3" cellspacing="0" bgcolor="#F2FBFF" class="select_table">
  <tr align="left" valign="middle"><td width="115" height="26" bgcolor="#8FD0FC" class="tdheader"><strong>&nbsp;Страна</strong></td>
  <td width="265" height="26" bgcolor="#BCE3FE" class="tdheader"><strong>&nbsp;Оператор</strong></td>
  <td width="105" height="26" bgcolor="#CDEBFE" class="tdheader"><strong>&nbsp;Номер</strong></td>
  <td width="85" height="26" bgcolor="#DEF1FE" class="tdheader"><strong>&nbsp;Стоимость</strong></td>
</tr>

<tr>
<td><select size="4" class="select_country" id="op"><?=$country;?></select></td>
<td><select size="4" class="select_oper" id="List2"></select></td>
<td><select size="4" class="select_number" id="List3"></select></td>
<td><select size="4" class="select_cost" id="cost4"></select></td>
</tr>

<tr>
<td colspan="4" class="login">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td height="40" align="right" valign="middle">
<FORM NAME="pform">Введите полученный пароль: <input NAME="pword" type="text" class="sms_form_f">
<input NAME="button1" TYPE="button" class="sms_form_s"  onclick="checkWord(document.pform.pword.value)" VALUE="Ввести">&nbsp;&nbsp;      
</form>
</td>
</tr>
</table>
</td>
</tr>
</table>  
<script type="text/javascript">
var syncList1 = new syncList;
syncList1.dataList = {
<? echo "{$operators}\n\n{$numbers}\n\n{$prices}"; ?>
}
syncList1.sync("op","List2","List3", "cost4");
</script>
<!--- sms form --->