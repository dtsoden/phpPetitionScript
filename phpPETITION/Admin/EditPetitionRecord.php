<?php 

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}


$MM_authorizedUsers = "1";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "error.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}

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

require_once('../Connections/petitionscript.php'); 

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE signature SET FirstName=%s, LastName=%s, Email=%s, TSDate=%s, IP=%s, Confirmation=%s WHERE ID=%s",
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['Email'], "text"),
                       GetSQLValueString($_POST['TSDate'], "date"),
                       GetSQLValueString($_POST['IP'], "text"),
                       GetSQLValueString($_POST['Confirmation'], "int"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_petitionscript, $petitionscript);
  $Result1 = mysql_query($updateSQL, $petitionscript) or die(mysql_error());

  $updateGoTo = "PetitionResults.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_petitionscript, $petitionscript);
$query_Update = "SELECT * FROM signature where ID='". $_GET['ID']."'";
$Update = mysql_query($query_Update, $petitionscript) or die(mysql_error());
$row_Update = mysql_fetch_assoc($Update);
$totalRows_Update = mysql_num_rows($Update);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Edit Petition Record</title><link href="Admin.css" rel="stylesheet" type="text/css" /><style type="text/css">
<!--
.style1 {font-size: 12px}
-->
</style>
</head><body><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><img src="Images/logo_phpBB.gif" width="200" height="91" border="0" /></td><td align="center" valign="middle" class="HomeLink"><div align="center" class="PS_Title"><p><strong><img src="Images/Edit2.gif" width="29" height="30" />EDIT PETITION RECORD </strong></p></div></td></tr></table><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="TopperHeader">Welcome <?php echo $_SESSION['MM_Username']; ?></td><td align="left" valign="middle" class="TopperHeader"><div align="right" class="HomeLink"><a href="PetitionResults.php">BACK</a><a href="PetitionResults.php"><img src="Images/Begining.gif" alt="BACK" width="21" height="21" border="0" /></a></div></td></tr></table><form method="post" name="form1" action="<?php echo $editFormAction; ?>"><table align="center"><tr valign="baseline"><td nowrap align="right">ID:</td><td><?php echo $row_Update['ID']; ?></td></tr><tr valign="baseline"><td nowrap align="right">FirstName:</td><td><input type="text" name="FirstName" value="<?php echo $row_Update['FirstName']; ?>" size="32"></td></tr><tr valign="baseline"><td nowrap align="right">LastName:</td><td><input type="text" name="LastName" value="<?php echo $row_Update['LastName']; ?>" size="32"></td></tr><tr valign="baseline"><td nowrap align="right">Email:</td><td><input type="text" name="Email" value="<?php echo $row_Update['Email']; ?>" size="32"></td></tr><tr valign="baseline"><td nowrap align="right">TSDate:</td><td><input type="text" name="TSDate" value="<?php echo $row_Update['TSDate']; ?>" size="32"></td></tr><tr valign="baseline"><td nowrap align="right" valign="top">IP:</td><td><?php echo $row_Update['IP']; ?> <input name="IP" type="hidden" 
	  		 value="<?php echo $row_Update['IP']; ?>" /></td></tr><tr valign="baseline"><td nowrap align="right">Confirmation:</td><td><select name="Confirmation" id="Confirmation"><?php
	  		switch ($row_Update['Confirmation'])
			{
				case 0:
				echo '<option value="0" selected="selected">Not Confirmed</option>';
				echo '<option value="1">Confirmed</option>';
				break;
				case 1:
				echo '<option value="0">Not Confirmed</option>';
				echo '<option value="1" selected="selected">Confirmed</option>';
				break;
			}
	  ?></select> </td></tr><tr valign="baseline"><td nowrap align="right">&nbsp;</td><td><input type="submit" value="Update record"></td></tr></table><input type="hidden" name="MM_update" value="form1"><input type="hidden" name="ID" value="<?php echo $row_Update['ID']; ?>"></form><p align="center"><span class="TopperHeader">&copy; 2006 The WebSite Guru Company </span></p></body></html><?php
mysql_free_result($Update);
?>