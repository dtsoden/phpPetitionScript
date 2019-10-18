<?php require_once('../Connections/petitionscript.php'); ?> <?php
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
?> <?php
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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE security SET `User`=%s, Password=%s, AccessLevel=%s WHERE ID=%s",
                       GetSQLValueString($_POST['User'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['AccessLevel'], "int"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_petitionscript, $petitionscript);
  $Result1 = mysql_query($updateSQL, $petitionscript) or die(mysql_error());

  $updateGoTo = "admin.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_petitionscript, $petitionscript);
$query_UserUpdate = "SELECT * FROM security where ID=".$_GET['ID'];
$UserUpdate = mysql_query($query_UserUpdate, $petitionscript) or die(mysql_error());
$row_UserUpdate = mysql_fetch_assoc($UserUpdate);
$totalRows_UserUpdate = mysql_num_rows($UserUpdate);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Update User</title><link href="Admin.css" rel="stylesheet" type="text/css" /><style type="text/css">
<!--
.style1 {font-size: 12px}
-->
</style>
</head><body><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><img src="Images/logo_phpBB.gif" width="200" height="91" border="0" /></td><td align="center" valign="middle" class="HomeLink"><div align="center" class="PS_Title"><p><strong><img src="Images/PeopleLogin.gif" width="26" height="26" />UPDATE USER </strong></p></div></td></tr></table><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="TopperHeader">Welcome <?php echo $_SESSION['MM_Username']; ?></td><td align="left" valign="middle" class="TopperHeader"><div align="right"><span class="HomeLink"><a href="admin.php">Home</a></span><a href="admin.php"><img src="Images/Home.gif" alt="Home" width="21" height="21" border="0" /></a></div></td></tr></table><form method="post" name="form1" action="<?php echo $editFormAction; ?>"><table align="center"><tr valign="baseline"><td nowrap align="right">ID:</td><td><?php echo $row_UserUpdate['ID']; ?></td></tr><tr valign="baseline"><td nowrap align="right">User:</td><td><input type="text" name="User" value="<?php echo $row_UserUpdate['User']; ?>" size="32"></td></tr><tr valign="baseline"><td nowrap align="right">Password:</td><td><input type="text" name="Password" value="<?php echo $row_UserUpdate['Password']; ?>" size="32"></td></tr><tr valign="baseline"><td nowrap align="right">AccessLevel:</td><td><p><select name="AccessLevel" id="AccessLevel"><?php
		  switch ($row_UserUpdate['AccessLevel'])
		  {
		  case 0:
        	echo '<option value="0" selected="selected">Report Admin</option>';
        	echo '<option value="1">Super Admin</option>';
		  break;
		  case 1:
		   	echo '<option value="0">Report Admin</option>';
        	echo '<option value="1" selected="selected">Super Admin</option>';
		  break;
		  }
		  ?></select></p></p></td></tr><tr valign="baseline"><td nowrap align="right">&nbsp;</td><td><input type="submit" value="Update record"></td></tr></table><input type="hidden" name="MM_update" value="form1"><input type="hidden" name="ID" value="<?php echo $row_UserUpdate['ID']; ?>"></form><div align="center" class="TopperHeader">&copy; 2006 The WebSite Guru Company </div><p>&nbsp;</p></body></html><?php
mysql_free_result($UserUpdate);
?>