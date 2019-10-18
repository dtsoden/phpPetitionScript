<?php
require_once('Connections/petitionscript.php');

mysql_select_db($database_petitionscript, $petitionscript);
$query_rs = "SELECT count(*) as RecordCount FROM signature";
$rs = mysql_query($query_rs, $petitionscript) or die(mysql_error() . " <a href='setup.php'>Click Here</a> to Setup the Table");
$row_rs = mysql_fetch_assoc($rs);
$totalRows_rs = mysql_num_rows($rs);

mysql_free_result($rs);
?>

<?php echo  "&nbsp; Total Signatures to Date = " . $row_rs['RecordCount']; ?>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />


<form action="received.php" method="post" name="form1">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">FirstName:</td>
      <td><span id="spryFirstName">
      <input type="text" name="FirstName" value="" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMaxCharsMsg">Exceeded max # of characters.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">LastName:</td>
      <td><span id="spryLastName">
      <input type="text" name="LastName" value="" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldMaxCharsMsg">Exceeded max # of characters.</span></span></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Email:</td>
      <td><span id="spryEmail">
      <input type="text" name="Email" value="" size="32" />
      <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span><span class="textfieldMaxCharsMsg">Exceeded max # of characters.</span></span><br />
	  <font size="-3">Use a real address if you want it to work</font></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="SIGN PETITION"></td>
    </tr>
  </table>
  <input type="hidden" name="IP" value="<?php Echo $_SERVER['REMOTE_ADDR'] ?>" size="32">
<input type="hidden" name="Date" value="<?php Echo  date('Y-m-d H:i:s') ?>" size="32">
<input type="hidden" name="MM_insert" value="form1">
</form>
<p>&nbsp;</p>
<script type="text/javascript">
<!--
var sprytextfield1 = new Spry.Widget.ValidationTextField("spryFirstName", "none", {validateOn:["blur"], maxChars:32});
var sprytextfield2 = new Spry.Widget.ValidationTextField("spryLastName", "none", {validateOn:["blur"], maxChars:32});
var sprytextfield3 = new Spry.Widget.ValidationTextField("spryEmail", "email", {validateOn:["blur"], maxChars:32});
//-->
</script>
