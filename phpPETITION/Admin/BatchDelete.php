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

require_once('../Connections/petitionscript.php'); 

mysql_select_db($database_petitionscript, $petitionscript);
$query_Count = "SELECT * FROM signature where confirmation='0'";
$Count = mysql_query($query_Count, $petitionscript) or die(mysql_error());
$row_Count = mysql_fetch_assoc($Count);
$totalRows_Count = mysql_num_rows($Count);
?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>BatchFile Delete</title><link href="Admin.css" rel="stylesheet" type="text/css" /><style type="text/css">
<!--
.style1 {color: #FF6600}
.style2 {
	color: #000000;
	font-weight: bold;
}
-->
</style>
</head><body><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><img src="Images/logo_phpBB.gif" width="200" height="91" border="0" /></td><td align="center" valign="middle" class="HomeLink"><div align="center" class="PS_Title"><p><strong><img src="Images/PeopleLogin.gif" width="26" height="26" />Batch Delete Petition Records </strong></p></div></td></tr></table><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="TopperHeader">Welcome <?php echo $_SESSION['MM_Username']; ?></td><td align="left" valign="middle" class="TopperHeader"><div align="right"><span class="HomeLink"><a href="admin.php">Home</a></span><a href="admin.php"><img src="Images/Home.gif" alt="Home" width="21" height="21" border="0" /></a></div></td></tr></table><form id="form1" name="form1" method="get" action="BatchDeleteProcess.php"><p align="center" class="style1">You currently have<span class="style2"> <?php echo $totalRows_Count ?></span> unconfirmed records</p><table width="550" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td rowspan="3" valign="top"><input type="submit" value="Delete" /> all prior unconfirmed signatures except those received after...</td><td>(Year)</td><td><select name="Year" class="BatchField" id="Year"><option value="2006">2006</option><option value="2007">2007</option><option value="2008">2008</option><option value="2009">2009</option><option value="2010">2010</option><option value="2011">2011</option><option value="2012">2012</option><option value="2013">2013</option><option value="2014">2014</option></select> </td></tr><tr><td>(Month)</td><td><select name="Month" class="BatchField" id="Month"><option value="01" selected="selected">January</option><option value="02">February</option><option value="03">March</option><option value="04">April</option><option value="05">May</option><option value="06">June</option><option value="07">July</option><option value="08">August</option><option value="09">September</option><option value="10">October</option><option value="11">November</option><option value="12">December</option></select> </td></tr><tr><td>(Day)</td><td><select name="Day" class="BatchField" id="Day"><option value="01" selected="selected">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select> </td></tr><tr valign="middle" bgcolor="#FADFC5"><td colspan="3"><img src="Images/Unauthorized.gif" width="35" height="31" /> Warning Changes Are Permanent - Make Sure You Have A Backup Prior To Running</td></tr></table></form></form><p>&nbsp;</p><form id="form2" name="form2" method="get" action="BatchDeleteProcess.php"><div align="center" class="BatchField">OR DELETE EVERYTHING? <input name="DeleteAll" type="submit" id="DeleteAll" value="DeleteAll" /> </div></form><div align="center" class="TopperHeader">&copy; 2006 The WebSite Guru Company </div></body></html><?php
mysql_free_result($Count);
?>