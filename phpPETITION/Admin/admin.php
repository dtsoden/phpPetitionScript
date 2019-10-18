<?php require_once('../Connections/petitionscript.php'); ?><?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "Index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?> <?php
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
?> <?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_UserList = 10;
$pageNum_UserList = 0;
if (isset($_GET['pageNum_UserList'])) {
  $pageNum_UserList = $_GET['pageNum_UserList'];
}
$startRow_UserList = $pageNum_UserList * $maxRows_UserList;

mysql_select_db($database_petitionscript, $petitionscript);
$query_UserList = "SELECT * FROM security";
$query_limit_UserList = sprintf("%s LIMIT %d, %d", $query_UserList, $startRow_UserList, $maxRows_UserList);
$UserList = mysql_query($query_limit_UserList, $petitionscript) or die(mysql_error());
$row_UserList = mysql_fetch_assoc($UserList);

if (isset($_GET['totalRows_UserList'])) {
  $totalRows_UserList = $_GET['totalRows_UserList'];
} else {
  $all_UserList = mysql_query($query_UserList);
  $totalRows_UserList = mysql_num_rows($all_UserList);
}
$totalPages_UserList = ceil($totalRows_UserList/$maxRows_UserList)-1;

$queryString_UserList = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_UserList") == false && 
        stristr($param, "totalRows_UserList") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_UserList = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_UserList = sprintf("&totalRows_UserList=%d%s", $totalRows_UserList, $queryString_UserList);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>phpPETITION Power Panel</title><link href="Admin.css" rel="stylesheet" type="text/css" /><style type="text/css">
<!--
.style1 {font-size: 12px}
a:link {
	color: #000000;
}
a:visited {
	color: #000000;
}
a:hover {
	color: #FFFFFF;
}
a:active {
	color: #FFFFFF;
}
-->
</style>
</head><body><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><img src="Images/logo_phpBB.gif" width="200" height="91" border="0" /></td><td align="center" valign="middle" class="HomeLink"><div align="center" class="PS_Title"><p><strong>phpPETITION POWER PANEL</strong></p></div></td></tr></table><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td class="TopperHeader">Welcome <?php echo $_SESSION['MM_Username']; ?></td><td align="left" valign="middle" class="TopperHeader"><div align="right"><span class="HomeLink style1"><a href="<?php echo $logoutAction ?>">LOGOUT </a></span><a href="<?php echo $logoutAction ?>"><img src="Images/Logout.gif" alt="Logout" width="22" height="22" border="0" align="absmiddle" /></a></div></td></tr></table><table width="500" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td colspan="5"><div align="center" class="PS_Title"><strong>OPTIONS</strong></div></td></tr><tr><th><a href="config.php">Email Configuration</a> </th><th><div align="center"><a href="ExcelExport.php">Export Petition Data</a></div></th><th><a href="BatchDelete.php">Batch Delete</a> </th><th><div align="center"><a href="PetitionResults.php">View Petitions</a></div></th><th><div align="center"><a href="AddUser.php">Add Users </a></div></th></tr><tr><td><div align="center"><a href="config.php"><img src="Images/email.gif" alt="Dissabled on Demo Site" width="41" height="32" border="0" /></a></div></td><td><div align="center"><a href="ExcelExport.php"><img src="Images/excel_ico.gif" alt="Excel Report" width="40" height="40" border="0" /></a></div></td><td><div align="center"><a href="BatchDelete.php"><img src="Images/batchDelete.gif" alt="Batch Delete Unconfirmed Signatures" width="35" height="34" border="0" /></a></div></td><td><div align="center"><a href="PetitionResults.php"><img src="Images/view.gif" alt="View Petition Report" width="53" height="54" border="0" /></a></div></td><td><div align="center"><a href="AddUser.php"><img src="Images/AddUser.gif" alt="AddUser" width="40" height="40" border="0" /></a></div></td></tr></table><table width="500" border="0" align="center" cellpadding="1" cellspacing="1"><tr><td colspan="6"><div align="center"><img src="Images/PeopleLogin.gif" width="26" height="26" /><span class="PS_Title"><strong> ADMIN USERS</strong></span></div></td></tr><tr><th><div align="center">Edit</div></th><th><div align="center">ID</div></th><th><div align="center">User</div></th><th><div align="center">Password</div></th><th><div align="center">AccessLevel</div></th><th><div align="center">Delete</div></th></tr><?php do { ?> <tr><td align="center" valign="middle"><a href="UserUpdate.php?ID=<?php echo $row_UserList['ID']; ?>"><img src="Images/Edit2.gif" alt="Edit User" width="29" height="30" border="0" /></a></td><td><div align="center"><?php echo $row_UserList['ID']; ?></div></td><td><div align="center"><?php echo $row_UserList['User']; ?></div></td><td><div align="center"><?php 
	  				if ($_SESSION['MMSecID'] == 1)
					echo $row_UserList['Password']; 
					else if ($_SESSION['MMSecID'] == 0 && $_SESSION['MMUserID'] == $row_UserList['ID'])
					echo $row_UserList['Password']; 
					else
					echo "******" ?></div></td><td><div align="center"><?php 
	  				switch ($row_UserList['AccessLevel'])
					  {
						  Case 0:
							  echo "Report Admin";
						  break;
						  case 1:
							  echo "Super Admin";
						  break;
					  }
		  ?></div></td><td align="center" valign="middle"><a href="UserDelete.php?ID=<?php echo $row_UserList['ID']; ?>"><img src="Images/Delete.gif" alt="Delete User" width="18" height="22" border="0" /></a></td></tr><?php } while ($row_UserList = mysql_fetch_assoc($UserList)); ?> </table><table border="0" width="50%" align="center"><tr><td width="23%" align="center"><?php if ($pageNum_UserList > 0) { // Show if not first page ?> <a href="<?php printf("%s?pageNum_UserList=%d%s", $currentPage, 0, $queryString_UserList); ?>"><img src="Images/Begining.gif" width="21" height="21" border=0></a> <?php } // Show if not first page ?> </td><td width="31%" align="center"><?php if ($pageNum_UserList > 0) { // Show if not first page ?> <a href="<?php printf("%s?pageNum_UserList=%d%s", $currentPage, max(0, $pageNum_UserList - 1), $queryString_UserList); ?>"><img src="Images/backward.gif" width="20" height="20" border=0></a> <?php } // Show if not first page ?> </td><td width="23%" align="center"><?php if ($pageNum_UserList < $totalPages_UserList) { // Show if not last page ?> <a href="<?php printf("%s?pageNum_UserList=%d%s", $currentPage, min($totalPages_UserList, $pageNum_UserList + 1), $queryString_UserList); ?>"><img src="Images/Forward.gif" width="21" height="20" border=0></a> <?php } // Show if not last page ?> </td><td width="23%" align="center"><?php if ($pageNum_UserList < $totalPages_UserList) { // Show if not last page ?> <a href="<?php printf("%s?pageNum_UserList=%d%s", $currentPage, $totalPages_UserList, $queryString_UserList); ?>"><img src="Images/End.gif" width="22" height="21" border=0></a> <?php } // Show if not last page ?> </td></tr></table></p><div align="center" class="TopperHeader">&copy; 2006 The WebSite Guru Company </div><p>&nbsp;</p></body></html><?php
mysql_free_result($UserList);
?>