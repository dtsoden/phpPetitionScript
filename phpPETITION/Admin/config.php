<?php require_once('../Connections/petitionscript.php'); 

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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE config SET FromEmail=%s, FromName=%s, Subject=%s, EmailServer=%s, SMTPAuth=%s, Username=%s, Password=%s WHERE id=%s",
                       GetSQLValueString($_POST['FromEmail'], "text"),
                       GetSQLValueString($_POST['FromName'], "text"),
                       GetSQLValueString($_POST['Subject'], "text"),
                       GetSQLValueString($_POST['EmailServer'], "text"),
					   GetSQLValueString($_POST['SMTPAuth'], "int"),
                       GetSQLValueString($_POST['Username'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_petitionscript, $petitionscript);
  $Result1 = mysql_query($updateSQL, $petitionscript) or die(mysql_error());

  $updateGoTo = "config.php?update=Y";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

mysql_select_db($database_petitionscript, $petitionscript);
$query_config = "SELECT * FROM config";
$config = mysql_query($query_config, $petitionscript) or die(mysql_error());
$row_config = mysql_fetch_assoc($config);
$totalRows_config = mysql_num_rows($config);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Email Configuration</title><link href="Admin.css" rel="stylesheet" type="text/css" /><script type="text/JavaScript">
<!--
function MM_findObj(n, d) { 
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_showHideLayers() { 
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v=='hide')?'hidden':v; }
    obj.visibility=v; }
}

function MM_openBrWindow(theURL,winName,features) { 
  window.open(theURL,winName,features);
}
//-->
</script>
</head><?php if ($row_config['SMTPAuth']==0)
		echo '<body onload="MM_showHideLayers(\'Auth\',\'\',\'hide\')">';
	 elseif ($row_config['SMTPAuth']==1) 
	  	echo '<body>';
?> <table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><img src="Images/logo_phpBB.gif" width="200" height="91" border="0" /></td><td align="center" valign="middle" class="HomeLink"><div align="center" class="PS_Title"><p><strong><img src="Images/email.gif" alt="Email Configuration" width="41" height="32" />EMAIL CONFIGURATION</strong></p></div></td></tr></table><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="TopperHeader">Welcome <?php echo $_SESSION['MM_Username']; ?></td><td align="left" valign="middle" class="TopperHeader"><div align="right"><span class="HomeLink"><a href="admin.php">Home</a></span><a href="admin.php"><img src="Images/Home.gif" alt="Home" width="21" height="21" border="0" /></a></div></td></tr></table><?php 
if (isset($_GET['update']))
echo '<p align="center" class="style1">CONFIGURATION UPDATE SUCESSFULL</p>';
elseif (!isset($_GET['update']))
{
?> <form method="post" name="form1" action="<?php echo $editFormAction; ?>"><table width="325" align="center"><tr valign="baseline"><td nowrap align="right">FromEmail:</td><td><input type="text" name="FromEmail" value="<?php echo $row_config['FromEmail']; ?>" size="32"><img src="Images/Help.gif" alt="Help About This Field" width="22" height="22" onclick="MM_openBrWindow('help.php#FromEmail','phpPetitionScript','width=400,height=200')" /></td></tr><tr valign="baseline"><td nowrap align="right">FromName:</td><td><input type="text" name="FromName" value="<?php echo $row_config['FromName']; ?>" size="32"> <img src="Images/Help.gif" alt="Help About This Field" width="22" height="22" onclick="MM_openBrWindow('help.php#FromName','phpPetitionScript','width=400,height=200')" /></td></tr><tr valign="baseline"><td nowrap align="right">Subject:</td><td><input type="text" name="Subject" value="<?php echo $row_config['Subject']; ?>" size="32"> <img src="Images/Help.gif" alt="Help About This Field" width="22" height="22" onclick="MM_openBrWindow('help.php#Subject','phpPetitionScript','width=400,height=200')" /></td></tr><tr valign="baseline"><td nowrap align="right">EmailServer:</td><td><input type="text" name="EmailServer" value="<?php echo $row_config['EmailServer']; ?>" size="32"> <img src="Images/Help.gif" alt="Help About This Field" width="22" height="22" onclick="MM_openBrWindow('help.php#EmailServer','phpPetitionScript','width=400,height=200')" /></td></tr><tr valign="baseline"><td align="right" nowrap bgcolor="#F1F2E3" onfocus="MM_openBrWindow('help.php#SMTPAuth','phpPetitionScript','width=400,height=200');MM_openBrWindow('help.php#SMTPAuth','phpPetitionScript','width=400,height=200')">SMTPAuth:</td><td bgcolor="#F1F2E3"><input name="SMTPAuth" type="radio" id="SMTPAuth" onclick="MM_showHideLayers('Auth','','show')" value="1" <?php if (!(strcmp($row_config['SMTPAuth'],"1"))) {echo "checked=\"checked\"";} ?> /> On <input name="SMTPAuth" type="radio" id="SMTPAuth" onclick="MM_showHideLayers('Auth','','hide')" value="0" <?php if (!(strcmp($row_config['SMTPAuth'],"0"))) {echo "checked=\"checked\"";} ?> /> Off &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <img src="Images/Help.gif" alt="Help About This Field" width="22" height="22" onclick="MM_openBrWindow('help.php#SMTPAuth','phpPetitionScript','width=400,height=200')" /></td></tr></table><layer name="Auth" z-index="1" visibility="hide"><table width="325" align="center" id="Auth"><tr valign="baseline"><td align="right" nowrap bgcolor="#F1F2E3">Username:</td><td bgcolor="#F1F2E3"><input type="text" name="Username" value="<?php echo $row_config['Username']; ?>" size="32"> <img src="Images/Help.gif" alt="Help About This Field" width="22" height="22" onclick="MM_openBrWindow('help.php#Username','phpPetitionScript','width=400,height=200')" /></td></tr><tr valign="baseline"><td align="right" nowrap bgcolor="#F1F2E3">Password:</td><td bgcolor="#F1F2E3"><input type="text" name="Password" value="<?php echo $row_config['Password']; ?>" size="32"> <img src="Images/Help.gif" alt="Help About This Field" width="22" height="22" onclick="MM_openBrWindow('help.php#Password','phpPetitionScript','width=400,height=200')" /></td></tr></table></layer><table width="325" align="center"><tr valign="baseline"><td nowrap align="right">&nbsp;</td><td><input type="submit" value="Update Email Config"></td></tr></table><input type="hidden" name="MM_update" value="form1"><input type="hidden" name="id" value="<?php echo $row_config['id']; ?>"></form><?php 
}
?> <div align="center" class="TopperHeader">&copy; 2006 The WebSite Guru Company</div><p>&nbsp;</p></body></html><?php
mysql_free_result($config);
?>