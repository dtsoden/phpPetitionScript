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

if (isset($_GET['Year']) && isset($_GET['Month']) && isset($_GET['Day']) && !$_GET['Year']=='' && !$_GET['Month']=='' && !$_GET['Day']==''){
  
  $deleteSQL = "Delete FROM signature where TSDate <'".$_GET['Year']."-".$_GET['Month']."-".$_GET['Day']."' and confirmation='0' ";
  mysql_select_db($database_petitionscript, $petitionscript);
  $Result1 = mysql_query($deleteSQL, $petitionscript) or die(mysql_error());
  
  $deleteGoTo = "BatchDelete.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
    header(sprintf("Location: %s", $deleteGoTo));
  } 
}
elseif (isset($_GET['DeleteAll']) && !$_GET['DeleteAll']==''){  
  $deleteSQL = "TRUNCATE signature";
  mysql_select_db($database_petitionscript, $petitionscript);
  $Result1 = mysql_query($deleteSQL, $petitionscript) or die(mysql_error());

  $deleteGoTo = "BatchDelete.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
    header(sprintf("Location: %s", $deleteGoTo)); 
  }
}
?>