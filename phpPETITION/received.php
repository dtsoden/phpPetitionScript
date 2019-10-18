<?php
//Get External Resources
require_once('Connections/petitionscript.php'); 
require("phpmailer/class.phpmailer.php"); 

//GET config Record Set
mysql_select_db($database_petitionscript, $petitionscript);
$query_config = "SELECT * FROM config";
$config = mysql_query($query_config, $petitionscript) or die(mysql_error());
$row_config = mysql_fetch_assoc($config);
$totalRows_config = mysql_num_rows($config);

# Switch Type Function
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

# Check For Duplicate Signatures -------------- > 
	
	mysql_select_db($database_petitionscript, $petitionscript);

	$query_rsEmailDupChk = 'SELECT count(signature.Email) as DupChk FROM signature WHERE signature.Email = ' . GetSQLValueString($_POST['Email'], "text");

	$rsEmailDupChk = mysql_query($query_rsEmailDupChk, $petitionscript) or die(mysql_error());

	$row_rsEmailDupChk = mysql_fetch_assoc($rsEmailDupChk);

	$totalRows_rsEmailDupChk = mysql_num_rows($rsEmailDupChk);

if ($row_rsEmailDupChk['DupChk'] > 0 ) {
		echo " <center><h1> Sorry you have already signed this petition </h1></center> <br />";

# No Duplicate Signatures... Begin on screen confirmation and aend email ----------->
} ELSE {

$text = str_replace("\n.", "\n..", $text);
$mail = new PHPMailer(); //Create PHPmailer class 
$mail->SetLanguage('en', 'phpmailer/language/'); // Set error message and language
$mail->From = $row_config['FromEmail']; //Sender address 
$mail->FromName = $row_config['FromName']; //The name that you'll see as Sender
$mail->Host = $row_config['EmailServer']; //Your SMTP mail server 
$mail->AddAddress($_POST['Email']); //The address you are sending mail to 
if($row_config['SMTPAuth']==1)
	{
	$mail->SMTPAuth = "true";
	$mail->Mailer = "smtp"; 
	$mail->Username = $row_config['Username'];
	$mail->Password = $row_config['Password'];
	}
elseif($row_config['SMTPAuth']==0)
	{
	$mail->SMTPAuth = "false";
	$mail->Mailer = "mail"; 
	}
$mail->Subject = $row_config['Subject']; //Subject of the mail 
$mail->Body = "Thanks for filling out our petition " . $_POST['FirstName']. ", you're almost done! \n" .
			  "Please confirm your signature by clicking on the link below \n \n" .
			  "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .
			  "/confirm.php?Email=" . urlencode($_POST['Email']) . "&FirstName=" . urlencode($_POST['FirstName']) ."\n \n" .
			  "Sincerely \n".
			  "Your Petition Support Team";

if(!$mail->Send()){ //Check for result of sending mail 
   echo "OOPS... There was an error sending the message:<br>"//Write an error message if mail isn't sent 
        . $mail->ErrorInfo;
   exit; } //Exit the script without executing the rest of the code
else {
		echo '<p>Your Petition Signature was received on ';
		echo date('l, F d') . ' at ' . date('g:i:s A') . '<br />';
		echo $_POST['FirstName'] ?> , by law, for your electronic signature to count, we have to verify that you really signed and that someone did not use your email address frauduently<br />

We have sent you an email, please click on the link in the email to verify your signature</p>

<b> In the mean time why not refer someone else to sign this petition? Click <a href="Referral/index.php">HERE</a> to refer someone else.
<?php
}
//CLEAR CLEAN UP DB CONNECTION AND RS BEFORE WRITING
mysql_free_result($rsEmailDupChk);
mysql_free_result($config);

// Record The Signature in the  ------------------>


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO signature (FirstName, LastName, Email, `TSDate`, IP) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['Date'], "date"),
                       GetSQLValueString($_POST['IP'], "text"));

  mysql_select_db($database_petitionscript, $petitionscript);
  $Result1 = mysql_query($insertSQL, $petitionscript) or die(mysql_error());
}
}

?>