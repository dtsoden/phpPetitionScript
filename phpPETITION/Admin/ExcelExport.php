<?php 

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

switch ($_SESSION['MM_UserGroup'])
{
case 0:
$MM_authorizedUsers = "0";
break;
case 1:
$MM_authorizedUsers = "1";
break;
}
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

require_once('../Connections/petitionscript.php'); 
mysql_select_db($database_petitionscript, $petitionscript);
$query_ExpExcel = "SELECT signature.TSDate, signature.FirstName, signature.LastName, signature.Email, signature.IP, signature.Confirmation FROM signature";
$ExpExcel = mysql_query($query_ExpExcel, $petitionscript) or die(mysql_error());
$row_ExpExcel = mysql_fetch_assoc($ExpExcel);
$totalRows_ExpExcel = mysql_num_rows($ExpExcel);

//Export to Excel Server Behavior
if (isset($_GET['Export'])&&($_GET['Export']=="Yes")){
$output="";
$include_hdr="1";
if($include_hdr=="1"){
	$totalColumns_ExpExcel=mysql_num_fields($ExpExcel);
	for ($x=0; $x<$totalColumns_ExpExcel; $x++) {
		if($x==$totalColumns_ExpExcel-1){$comma="";}else{$comma=",";}
		$output = $output.(ereg_replace("_", " ",mysql_field_name($ExpExcel, $x))).$comma;
	}
	$output = $output."\r\n";
}

do{$fixcomma=array();
    foreach($row_ExpExcel as $r){array_push($fixcomma,ereg_replace(",","¸",$r));}
    $line = join(",",$fixcomma);
    $line=ereg_replace("\r\n", " ",$line);
    $line = "$line\n";
    $output=$output.$line;}while($row_ExpExcel = mysql_fetch_assoc($ExpExcel));
header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=report.csv");
header("Content-Type: application/force-download");
header("Cache-Control: post-check=0, pre-check=0", false);
echo $output;
die();
}
 
?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Export 2 Excel</title><link href="Admin.css" rel="stylesheet" type="text/css" /><style type="text/css">
<!--
.style1 {font-size: 12px}
.style2 {
	color: #FF0000;
	font-size: 14px;
}
-->
</style>
</head><body><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><img src="Images/logo_phpBB.gif" width="200" height="91" border="0" /></td><td align="center" valign="middle" class="HomeLink"><div align="center" class="PS_Title"><p><strong>Export 2 Excel</strong><br /><strong><img src="Images/excel_ico.gif" alt="Export 2 Excel" width="40" height="40" /></strong></p></div></td></tr></table><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="TopperHeader">Welcome <?php echo $_SESSION['MM_Username']; ?></td><td align="left" valign="middle" class="TopperHeader"><div align="right"><span class="HomeLink"><a href="admin.php">Home</a></span><a href="admin.php"><img src="Images/Home.gif" alt="Home" width="21" height="21" border="0" /></a></div></td></tr></table><form id="form1" name="form1" method="post" action="ExcelExport.php?Export=Yes"><div align="center"><input name="Export Report" type="submit" id="Export Report" value="Export Report" /> </div></form><div align="center"><p><a href="admin.php" class="HomeLink"><span class="style2">Home</span><img src="Images/Home.gif" alt="Home" width="21" height="21" border="0" /></a></p><p><span class="TopperHeader">&copy; 2006 The WebSite Guru Company </span></p></div></body></html><?php
mysql_free_result($ExpExcel);
?>