<?php 

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

require_once('../Connections/petitionscript.php'); 

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

$maxRows_PetitionResults = 25;
$pageNum_PetitionResults = 0;
if (isset($_GET['pageNum_PetitionResults'])) {
  $pageNum_PetitionResults = $_GET['pageNum_PetitionResults'];
}
$startRow_PetitionResults = $pageNum_PetitionResults * $maxRows_PetitionResults;

mysql_select_db($database_petitionscript, $petitionscript);

// ********************************  Begin Complex SQL Query *****************************

// Run this SQL statement if Search is provided
if (isset($_GET['Search']) && !isset($_GET['orderby']))
{
if (urldecode($_GET['S_FirstName']) == '')
$S_FirstName = '~';
else
$S_FirstName = urldecode($_GET['S_FirstName']);
	
if (urldecode($_GET['S_LastName']) == '')
$S_LastName = '~';
else
$S_LastName = urldecode($_GET['S_LastName']);

if (urldecode($_GET['S_Email']) == '')
$S_Email = '~';
else
$S_Email = urldecode($_GET['S_Email']);

$query_PetitionResults = "SELECT * FROM signature where FirstName like '%".$S_FirstName."%' or LastName like '%".$S_LastName."%' or Email like '%".$S_Email."%'";
}
// Run this SQL statement if Search is provided AND any column is ordered by
elseif (isset($_GET['Search']) && isset($_GET['orderby']))
{
if (urldecode($_GET['S_FirstName']) == '')
$S_FirstName = '~';
else
$S_FirstName = urldecode($_GET['S_FirstName']);
	
if (urldecode($_GET['S_LastName']) == '')
$S_LastName = '~';
else
$S_LastName = urldecode($_GET['S_LastName']);

if (urldecode($_GET['S_Email']) == '')
$S_Email = '~';
else
$S_Email = urldecode($_GET['S_Email']);

$query_PetitionResults = "SELECT * FROM signature where FirstName like '%".$S_FirstName."%' or LastName like '%".$S_LastName."%' or Email like '%".$S_Email."%' order by ".$_GET['orderby']." desc";
}
// Run this SQL statement if any column is ordered by
elseif (isset($_GET['orderby']) && !isset($_GET['Search']))
{
$query_PetitionResults = "SELECT * FROM signature order by ".$_GET['orderby']." asc";
}
// Run this SQL statement if nothing is provided
else
{
$query_PetitionResults = "SELECT * FROM signature";
}
// ********************************  END Complex SQL Query *****************************

$query_limit_PetitionResults = sprintf("%s LIMIT %d, %d", $query_PetitionResults, $startRow_PetitionResults, $maxRows_PetitionResults);
$PetitionResults = mysql_query($query_limit_PetitionResults, $petitionscript) or die(mysql_error());
$row_PetitionResults = mysql_fetch_assoc($PetitionResults);

if (isset($_GET['totalRows_PetitionResults'])) {
  $totalRows_PetitionResults = $_GET['totalRows_PetitionResults'];
} else {
  $all_PetitionResults = mysql_query($query_PetitionResults);
  $totalRows_PetitionResults = mysql_num_rows($all_PetitionResults);
}
$totalPages_PetitionResults = ceil($totalRows_PetitionResults/$maxRows_PetitionResults)-1;

$queryString_PetitionResults = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_PetitionResults") == false && 
        stristr($param, "totalRows_PetitionResults") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_PetitionResults = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_PetitionResults = sprintf("&totalRows_PetitionResults=%d%s", $totalRows_PetitionResults, $queryString_PetitionResults);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>Petition Administrator</title><link href="Admin.css" rel="stylesheet" type="text/css" /><style type="text/css">
<!--
a:link {
	color: #000000;
}
a:visited {
	color: #000000;
}
a:hover {
	color: #000099;
}
a:active {
	color: #FFFFFF;
}
-->
</style></head><body><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td rowspan="2"><p><img src="Images/logo_phpBB.gif" width="200" height="91" border="0" /></p></td><td align="center" valign="middle" class="HomeLink"><div align="center"><a href="admin.php">Home<img src="Images/Home.gif" alt="Home" width="21" height="21" border="0" /></a></div></td></tr><tr><td><div align="center" class="PS_Title"><p><strong>PETITION RESULTS </strong></p></div><div align="center">Too Many Results? <a href="search.php"><img src="Images/search.gif" alt="Search" width="21" height="20" border="0" /></a><a href="search.php">SEARCH RECORDS</a> </div></td></tr></table><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><div class="TopperHeader" align="left">There are <?php echo $totalRows_PetitionResults ?> Petition Signatures </div></td><td>&nbsp; <div class="TopperHeader" align="right">Records <?php echo ($startRow_PetitionResults + 1) ?> to <?php echo min($startRow_PetitionResults + $maxRows_PetitionResults, $totalRows_PetitionResults) ?></div></td></tr></table><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><th>Delete</th><th><?php if (isset($_GET['Search']))
	echo '<a href="'. $_SERVER['PHP_SELF'].'?S_FirstName='.urldecode($_GET['S_FirstName']).'&S_LastName='.urldecode($_GET['S_LastName']).'&S_Email='.urldecode($_GET['S_Email']).'&Search=Submit&orderby=ID">ID</a>';
		else
	echo '<a href="'.$_SERVER['PHP_SELF'].'?orderby=ID">ID</a>' ;?> </th><th><?php if (isset($_GET['Search']))
	echo '<a href="'. $_SERVER['PHP_SELF'].'?S_FirstName='.urldecode($_GET['S_FirstName']).'&S_LastName='.urldecode($_GET['S_LastName']).'&S_Email='.urldecode($_GET['S_Email']).'&Search=Submit&orderby=FirstName">FirstName</a>';
		else
	echo '<a href="'.$_SERVER['PHP_SELF'].'?orderby=FirstName">FirstName</a>' ;?> </th><th><?php if (isset($_GET['Search']))
	echo '<a href="'. $_SERVER['PHP_SELF'].'?S_FirstName='.urldecode($_GET['S_FirstName']).'&S_LastName='.urldecode($_GET['S_LastName']).'&S_Email='.urldecode($_GET['S_Email']).'&Search=Submit&orderby=LastName">LastName</a>';
		else
	echo '<a href="'.$_SERVER['PHP_SELF'].'?orderby=LastName">LastName</a>' ;?> </th><th><?php if (isset($_GET['Search']))
	echo '<a href="'. $_SERVER['PHP_SELF'].'?S_FirstName='.urldecode($_GET['S_FirstName']).'&S_LastName='.urldecode($_GET['S_LastName']).'&S_Email='.urldecode($_GET['S_Email']).'&Search=Submit&orderby=TSDate">TSDate</a>';
		else
	echo '<a href="'.$_SERVER['PHP_SELF'].'?orderby=TSDate">TSDate</a>' ;?> </th><th><?php if (isset($_GET['Search']))
	echo '<a href="'. $_SERVER['PHP_SELF'].'?S_FirstName='.urldecode($_GET['S_FirstName']).'&S_LastName='.urldecode($_GET['S_LastName']).'&S_Email='.urldecode($_GET['S_Email']).'&Search=Submit&orderby=Email">Email</a>';
		else
	echo '<a href="'.$_SERVER['PHP_SELF'].'?orderby=Email">Email</a>' ;?> </th><th><?php if (isset($_GET['Search']))
	echo '<a href="'. $_SERVER['PHP_SELF'].'?S_FirstName='.urldecode($_GET['S_FirstName']).'&S_LastName='.urldecode($_GET['S_LastName']).'&S_Email='.urldecode($_GET['S_Email']).'&Search=Submit&orderby=IP">IP</a>';
		else
	echo '<a href="'.$_SERVER['PHP_SELF'].'?orderby=IP">IP</a>' ;?> </th><th><?php if (isset($_GET['Search']))
	echo '<a href="'. $_SERVER['PHP_SELF'].'?S_FirstName='.urldecode($_GET['S_FirstName']).'&S_LastName='.urldecode($_GET['S_LastName']).'&S_Email='.urldecode($_GET['S_Email']).'&Search=Submit&orderby=Confirmation">Confirmation</a>';
		else
	echo '<a href="'.$_SERVER['PHP_SELF'].'?orderby=Confirmation">Confirmation</a>' ;?> </th><th>Edit</th></tr><?php do { ?> <tr><td><div align="center"><a href="DeletePetitionRecord.php?ID=<?php echo $row_PetitionResults['ID']; ?>"><img src="Images/Delete.gif" alt="Delete" border="0"></a></div></td><td><?php echo $row_PetitionResults['ID']; ?></td><td><?php echo $row_PetitionResults['FirstName']; ?></td><td><?php echo $row_PetitionResults['LastName']; ?></td><td><?php echo $row_PetitionResults['TSDate']; ?></td><td><?php echo $row_PetitionResults['Email']; ?></td><td><?php echo $row_PetitionResults['IP']; ?></td><td><?php echo $row_PetitionResults['Confirmation']; ?></td><td><a href="EditPetitionRecord.php?ID=<?php echo $row_PetitionResults['ID']; ?>"><img src="Images/Edit2.gif" alt="Edit Record" border="0"></a></td></tr><?php } while ($row_PetitionResults = mysql_fetch_assoc($PetitionResults)); ?> </table><table border="0" width="50%" align="center"><tr><td width="23%" align="center"><?php if ($pageNum_PetitionResults > 0) { // Show if not first page ?> <a href="<?php printf("%s?pageNum_PetitionResults=%d%s", $currentPage, 0, $queryString_PetitionResults); ?>"><img src="Images/Begining.gif" alt="Begining" border="0" /></a> <?php } // Show if not first page ?> </td><td width="31%" align="center"><?php if ($pageNum_PetitionResults > 0) { // Show if not first page ?> <a href="<?php printf("%s?pageNum_PetitionResults=%d%s", $currentPage, max(0, $pageNum_PetitionResults - 1), $queryString_PetitionResults); ?>"><img src="Images/backward.gif" alt="Backward" width="20" height="20" border="0" /></a> <?php } // Show if not first page ?> </td><td width="23%" align="center"><?php if ($pageNum_PetitionResults < $totalPages_PetitionResults) { // Show if not last page ?> <a href="<?php printf("%s?pageNum_PetitionResults=%d%s", $currentPage, min($totalPages_PetitionResults, $pageNum_PetitionResults + 1), $queryString_PetitionResults); ?>"><img src="Images/Forward.gif" alt="Forward" width="21" height="20" border="0" /></a> <?php } // Show if not last page ?> </td><td width="23%" align="center"><?php if ($pageNum_PetitionResults < $totalPages_PetitionResults) { // Show if not last page ?> <a href="<?php printf("%s?pageNum_PetitionResults=%d%s", $currentPage, $totalPages_PetitionResults, $queryString_PetitionResults); ?>"><img src="Images/End.gif" alt="End" width="22" height="21" border="0" /></a> <?php } // Show if not last page ?> </td></tr></table><div align="center" class="TopperHeader">&copy; 2006 The WebSite Guru Company </div></body></html><?php
mysql_free_result($PetitionResults);
?>