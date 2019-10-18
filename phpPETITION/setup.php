<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Setup phpPetition Script</title>
</head>

<body>
<p><img src="http://petitionscript.net/phpPETITION2/admin/images/logo_phpbb.gif" alt="" width="200" height="91" />
</p>
<?php if (!isset($_GET['MYSQL_ID1']) && !isset($_GET['MYSQL_ID2']) && !isset($_GET['MYSQL_ID3']) && !isset($_GET['MYSQL_ID4']) && !isset($_GET['MYSQL_ID5']) && !isset($_GET['EmailForm'])) 
{
?>

<h1><font color="#FF0000">After</font> you have setup an empty database called <font color="#FF0000">petitionscript</font><br>
    <a href="<?php echo $_SERVER['PHP_SELF'] . "?MYSQL_ID1=MYSQL_Script" ?> ">Click here</a> to begin setup process.
    </p></h1>
<p><br>
<?php 
}
?>  
<?php
$SQL1 = "
--
-- Table structure for table `petitionscript`.`security`
--

CREATE TABLE `security` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `User` varchar(15) NOT NULL default '',
  `Password` varchar(15) NOT NULL default '',
  `AccessLevel` int(10) unsigned NOT NULL default '1',
  PRIMARY KEY  (`ID`)
);
";
$SQL2 = "
--
-- Table structure for table `petitionscript`.`signature`
--
CREATE TABLE `signature` (
  `ID` int(10) unsigned NOT NULL auto_increment,
  `FirstName` varchar(45) NOT NULL default '',
  `LastName` varchar(45) NOT NULL default '',
  `Email` varchar(45) NOT NULL default '',
  `TSDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `IP` varchar(45) NOT NULL default '',
  `Confirmation` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
);
";
$SQL3 = "
--
-- Adding Security data for table `petitionscript`.`security`
--
INSERT INTO `security` (`ID`,`User`,`Password`,`AccessLevel`) VALUES 
 (18,'test','test',0),
 (19,'admin','admin',1);
";
$SQL4 = "
--
-- Adding Configuration Table for phpMailer Class 
--
CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `FromEmail` varchar(45) NOT NULL default '',
  `FromName` varchar(45) NOT NULL default '',
  `Subject` varchar(150) NOT NULL default '',
  `EmailServer` varchar(45) NOT NULL default '',
  `SMTPAuth` tinyint(1) NOT NULL default '0',
  `Username` varchar(45) NULL default '',
  `Password` varchar(45) NULL default '',
  PRIMARY KEY  (`id`)
);
";

IF
($_GET['MYSQL_ID1'] == 'MYSQL_Script') {

 require_once('Connections/petitionscript.php');
 mysql_select_db($database_petitionscript, $petitionscript);
 
	IF
		(mysql_query($SQL1))
		{
		echo "<font color='#FF9900' size='+2'>Security Table Create Was A Sucess</font><br>" .
			 "<a href='setup.php?MYSQL_ID2=MYSQL_Script'>Click to setup the signature table</a>";
		} Else {
		echo "<font color='#FF0000' size='+2'>Error Creating Security Table</font>" . 
			 "<font color='#FF0000' size='+2'>". 
			  mysql_error() . 
			 "</font>" ;
		}
}

IF
($_GET['MYSQL_ID2'] == 'MYSQL_Script') {

 require_once('Connections/petitionscript.php');
 mysql_select_db($database_petitionscript, $petitionscript);
 
	IF
		(mysql_query($SQL2))
		{
		echo "<font color='#FF9900' size='+2'>The Signature Table Creation Was A Sucess</font><br>" .
			 "<a href='setup.php?MYSQL_ID3=MYSQL_Script'>Click to add the default security accounts</a>";
		} Else {
		echo "<font color='#FF0000' size='+2'>Error Creating Signature Table</font>" . 
			 "<font color='#FF0000' size='+2'>". 
			  mysql_error() . 
			 "</font>" ;
		}
}

IF
($_GET['MYSQL_ID3'] == 'MYSQL_Script') {

 require_once('Connections/petitionscript.php');
 mysql_select_db($database_petitionscript, $petitionscript);
 
	IF
		(mysql_query($SQL3))
		{
		echo "<font color='#FF9900' size='+2'>Default Security Accounts Added Successful</font><br>" .
			 "<a href='setup.php?MYSQL_ID4=MYSQL_Script'>Almost Done, Click To Setup The Email Configuration Table</a>";
		} Else {
		echo "<font color='#FF0000' size='+2'>Error adding default security accounts</font>" . 
			 "<font color='#FF0000' size='+2'>". 
			  mysql_error() . 
			 "</font>" ;
		}
}  

IF
($_GET['MYSQL_ID4'] == 'MYSQL_Script') {

 require_once('Connections/petitionscript.php');
 mysql_select_db($database_petitionscript, $petitionscript);
 
	IF
		(mysql_query($SQL4))
		{
		echo "<font color='#FF9900' size='+2'>Configuration Table Added Successful</font><br>" .
			 "<a href='setup.php?EmailForm=1'>Last Step, Setup your e-Mail Server</a>";
		} Else {
		echo "<font color='#FF0000' size='+2'>Error adding default security accounts</font>" . 
			 "<font color='#FF0000' size='+2'>". 
			  mysql_error() . 
			 "</font>" ;
		}
}  

IF
($_GET['MYSQL_ID5'] == 'MYSQL_Script') {

 require_once('Connections/petitionscript.php'); 
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO config (FromEmail, FromName, Subject, EmailServer, SMTPAuth, Username, Password) VALUES (%s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['FromEmail'], "text"),
                       GetSQLValueString($_POST['FromName'], "text"),
                       GetSQLValueString($_POST['Subject'], "text"),
                       GetSQLValueString($_POST['EmailServer'], "text"),
                       GetSQLValueString($_POST['SMTPAuth'], "int"),
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['Password'], "text"));

  mysql_select_db($database_petitionscript, $petitionscript);
  $Result1 = mysql_query($insertSQL, $petitionscript) or die(mysql_error());
}

echo '<h2>Thats\'s it you\'re DONE... click <a href="index.php">here</a> to begin your first signature.</h2>';
}
?>
<?php 
if ($_GET['EmailForm'] == '1') {
?>
<h2>Now Let's setup your email server so petition confirmations can be sent.</h2>
<form method="post" name="form1" action="<?php echo $editFormAction; ?>?MYSQL_ID5=MYSQL_Script">
  <table align="center">
    <tr valign="baseline">
      <td nowrap align="right">FromEmail:</td>
      <td><input type="text" name="FromEmail" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">FromName:</td>
      <td><input type="text" name="FromName" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Subject:</td>
      <td><input type="text" name="Subject" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">EmailServer:</td>
      <td><input type="text" name="EmailServer" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">SMTPAuth:</td>
      <td valign="baseline"><table>
        <tr>
          <td><input type="radio" name="SMTPAuth" value="1" >
            on</td>
        </tr>
        <tr>
          <td><input type="radio" name="SMTPAuth" value="0" >
            off</td>
        </tr>
      </table></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Username:</td>
      <td><input type="text" name="Username" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">Password:</td>
      <td><input type="text" name="Password" value="" size="32"></td>
    </tr>
    <tr valign="baseline">
      <td nowrap align="right">&nbsp;</td>
      <td><input type="submit" value="Finish Setup"></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<?PHP
}  
?>
</p>
<p align="center"><img src="http://petitionscript.net/images/phplogo.gif" alt="" width="84" height="49" /></p>
</body>
</html>
