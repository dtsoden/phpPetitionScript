<?php 
session_start();



if( isset($_POST['submit'])) {
   if( $_POST['security_code'] == '3y63c' && !empty($_POST['security_code'] ) ) {
// Insert you code for processing the form here. 

?>
<center><h3>Thank you, had this been the actual add-on module you would have seen real CAPTCHA<br /> functionality and an email would have been sent as you would expect, this is only a DEMO</h3><p>To see a live demo visit <a href="http://petitionscript.net/phpPETITION2/Referral/index.php">PetitionScript Online</a></center><p></p>
<div align="center" style="background:#FFCC99; border:outset #0066CC">
<p class="subHeader"><br />
Get this add-on module for your phpPETITION Script, only USD $15.00. <br  />To install simply unzip the Referral folder and upload to your phpPETITION directory and link to it. <br />Its just that simple.</p>
<form class="subHeader" action="https://www.paypal.com/cgi-bin/webscr" method="post">
<a href="http://www.petitionscript.net/Orders/index.php"><img src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0"></a><br /></form></div>
<?php



   } elseif( $_POST['security_code'] != '3y63c' && !empty($_POST['security_code'] ) ) {
		// Insert your code for showing an error message here
		echo '<center><h3>Captcha entered incorrectly</h3></center><p></p>';
   } elseif ( empty($_POST['security_code'] ) ) {
	   // Insert your code for showing an error message here
		echo '<center><h3>Oops, you forgot the Captcha Code!!!</h3></center><p></p>';
   }
} else {
?>
<link rel="stylesheet" type="text/css" href="../phpPetitionTemplate/mm_travel2.css">
<div align="center" class="pageName">Recomend This Petition To Someone Else</div>
<form class="smallText" action="demo.php" method="post">
<p align="center"><em><strong>NOTE THIS PAGE IS AN ADD-ON MODULE AND IS NOT INCLUDED WITH THE FREE SCRIPT</strong></em></p>
<table border="0" align="center" cellspacing="3">
<tr>
            <td width="150"><label for="label">Your Name: </label></td>
      <td width="200"><input type="text" name="yname" id="yname" /></td>
    </tr>
          <tr>
            <td width="150"><label for="label">Your Email: </label></td>
            <td width="200"><input type="text" name="yemail" id="yemail" /></td>
    </tr>
          <tr>
            <td width="150">Recipients Name</td>
            <td width="200"><input type="text" name="rname" id="rname" /></td>
    </tr>
          <tr>
            <td width="150">Recipients Email</td>
            <td width="200"><input type="text" name="remail" id="remail" /></td>
    </tr>
          <tr>
            <td width="150"><label for="label">Message: </label></td>
            <td width="200" class="subHeader"><?php echo $Line1 ?><br /> 
              <textarea rows="5" cols="30" name="rmessage" id="rmessage"></textarea><br />
            <?php echo $Line2 ?> <br />
			<?php echo $URL ?>
			<p></p>
            </td>
    </tr>
          <tr>
            <td width="150">Captcha Image:</td>
            <td width="200"><img src="CAPTCHA.JPG"></td>
    </tr>
          <tr>
            <td width="150"><label for="label">Captcha Code: </label></td>
            <td width="200"><input id="security_code" name="security_code" type="text" /></td>
    </tr>
          <tr>
            <td width="150">&nbsp;</td>
            <td width="200"><input type="submit" name="submit" value="Submit Demo" /></td>
    </tr>
        </table>
  <label for="yname"><br />
  </label>
        <label for="yemail"></label>
        <label for="rmessage"></label>
		<label for="security_code"></label>
</form>
<p>
<?php
	}
?>

