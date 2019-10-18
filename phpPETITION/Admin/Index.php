<?php 

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

require_once('../Connections/petitionscript.php'); 

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['User'])) {
  $loginUsername=$_POST['User'];
  $password=$_POST['Password'];
  $MM_fldUserAuthorization = "AccessLevel";
  $MM_redirectLoginSuccess = "admin.php";
  $MM_redirectLoginFailed = "error.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_petitionscript, $petitionscript);
  	
  $LoginRS__query=sprintf("SELECT User, Password, AccessLevel, ID FROM security WHERE User='%s' AND Password='%s'",
  get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password)); 
   
  $LoginRS = mysql_query($LoginRS__query, $petitionscript) or die(mysql_error());
  $row_SecID = mysql_fetch_assoc($LoginRS);
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
    
    $loginStrGroup  = mysql_result($LoginRS,0,'AccessLevel');
	
	//declare four session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      
    $_SESSION['MMSecID'] = $row_SecID['AccessLevel'];
    $_SESSION['MMUserID'] = $row_SecID['ID'];
	if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /><title>PetitionScript Power Panel Login</title><style type="text/css">
<!--
.style1 {font-size: 12px}
-->
</style>
<link href="Admin.css" rel="stylesheet" type="text/css" /></head><body><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td><img src="Images/logo_phpBB.gif" width="200" height="91" border="0" /></td><td align="center" valign="middle" class="HomeLink"><div align="center" class="PS_Title"><p><strong><img src="Images/Security.gif" alt="Secure Area" width="15" height="21" /> phpPETITION POWER PANEL</strong></p></div></td></tr></table><form id="SecurityLogin" name="SecurityLogin" method="POST" action="<?php echo $loginFormAction; ?>"><table width="200" border="0" align="center" cellpadding="0" cellspacing="3"><tr><td rowspan="3" align="center" valign="middle"><img src="Images/PeopleLogin.gif" alt="Login" width="26" height="26" /> <div align="right"></div></td><td>User</td><td><label for="textfield"></label><input name="User" type="text" id="User" value="admin" /></td></tr><tr><td>Password</td><td><input name="Password" type="password" id="Password" value="admin" /></td></tr><tr><td colspan="2"><div align="right"><input name="Login" type="submit" id="Login" value="Login" /> <img src="Images/Security.gif" alt="Secure Area" width="15" height="21" /> </div></td></tr></table></form><div align="center"><span class="TopperHeader">&copy; 2006 The WebSite Guru Company </span></div></body></html>