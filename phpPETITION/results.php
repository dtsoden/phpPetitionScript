<?php require_once('Connections/petitionscript.php'); ?>
<?php
$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsResult = 10;
$pageNum_rsResult = 0;
if (isset($_GET['pageNum_rsResult'])) {
  $pageNum_rsResult = $_GET['pageNum_rsResult'];
}
$startRow_rsResult = $pageNum_rsResult * $maxRows_rsResult;

mysql_select_db($database_petitionscript, $petitionscript);
$query_rsResult = "SELECT * FROM signature";
$query_limit_rsResult = sprintf("%s LIMIT %d, %d", $query_rsResult, $startRow_rsResult, $maxRows_rsResult);
$rsResult = mysql_query($query_limit_rsResult, $petitionscript) or die(mysql_error());
$row_rsResult = mysql_fetch_assoc($rsResult);

if (isset($_GET['totalRows_rsResult'])) {
  $totalRows_rsResult = $_GET['totalRows_rsResult'];
} else {
  $all_rsResult = mysql_query($query_rsResult);
  $totalRows_rsResult = mysql_num_rows($all_rsResult);
}
$totalPages_rsResult = ceil($totalRows_rsResult/$maxRows_rsResult)-1;

$queryString_rsResult = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsResult") == false && 
        stristr($param, "totalRows_rsResult") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsResult = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsResult = sprintf("&totalRows_rsResult=%d%s", $totalRows_rsResult, $queryString_rsResult);
?><html>
<!-- DW6 -->
<head>
<!-- Copyright 2005 Macromedia, Inc. All rights reserved. -->
<title>Home Page</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="phpPetitionTemplate/mm_travel2.css" type="text/css">
<script language="javascript">
//--------------- LOCALIZEABLE GLOBALS ---------------
var d=new Date();
var monthname=new Array("January","February","March","April","May","June","July","August","September","October","November","December");
//Ensure correct for language. English is "January 1, 2004"
var TODAY = monthname[d.getMonth()] + " " + d.getDate() + ", " + d.getFullYear();
//---------------   END LOCALIZEABLE   ---------------
</script>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</head>
<body bgcolor="#C0DFFD">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr bgcolor="#3366CC">
    <td width="382" colspan="3" rowspan="2"><img src="phpPetitionTemplate/mm_travel_photo.jpg" alt="Header image" width="382" height="127" border="0"></td>
    <td width="378" height="63" colspan="3" id="logo" valign="bottom" align="center" nowrap>Tropical Petition </td>
    <td width="100%">&nbsp;</td>
  </tr>

  <tr bgcolor="#3366CC">
    <td height="64" colspan="3" id="tagline" valign="top" align="center">Where Your Opinion Counts </td>
	<td width="100%">&nbsp;</td>
  </tr>

  <tr>
    <td colspan="7" bgcolor="#003366"><img src="phpPetitionTemplate/mm_spacer.gif" alt="" width="1" height="1" border="0"></td>
  </tr>

  <tr bgcolor="#CCFF99">
  	<td colspan="7" id="dateformat" height="25">&nbsp;&nbsp;<script language="javascript">
      document.write(TODAY);	</script>	</td>
  </tr>
 <tr>
    <td colspan="7" bgcolor="#003366"><img src="phpPetitionTemplate/mm_spacer.gif" alt="" width="1" height="1" border="0"></td>
  </tr>

 <tr>
    <td width="382" valign="top" bgcolor="#E6F3FF">
	<table border="0" cellspacing="0" cellpadding="0" width="165" id="navigation">
        <tr>
          <td width="165">&nbsp;<br>
		 &nbsp;<br></td>
        </tr>
        <tr>
          <td width="165"><a href="index.php" class="navText">Sign Petition </a></td>
        </tr>
        <tr>
          <td width="165"><a href="results.php" class="navText">View Signatures </a></td>
        </tr>
        <tr>
          <td width="165"><a href="admin\" class="navText">ADMIN LOGIN </a></td>
        </tr>
      </table>
 	 
<div align="center">&nbsp;<a href="phpPETITION.zip"><img src="phpPetitionTemplate/php.gif" alt="Powered By PhpPetitionScript *** CLICK TO DOWNLOAD NOW ***" width="123" height="110" longdesc="http://thewebsiteguru.com"></a><br>
<a href="phpPETITION.zip">CLICK TO<br>
DOWNLOAD NOW</a>
	  &nbsp;<br>
 	  &nbsp;<br> 	
    </div></td>
    <td width="50">&nbsp;</td>
    <td colspan="4" valign="top"><img src="phpPetitionTemplate/mm_spacer.gif" alt="" width="305" height="1" border="0"><br>
&nbsp;	<br>
	<table border="0" cellspacing="0" cellpadding="0" width="305">
        <tr>
          <td class="pageName">Petition Results </td>
		</tr>
      </table>
	   <br>
    <br>
    <b><?php echo "There are " . $totalRows_rsResult . " Total Records" ?></b> <br>
    <table border="0">
      <tr bgcolor="#3165CE">
	  <?php 
	  if ((isset($_GET['ADMIN'])) && ($_GET['ADMIN'] != ""))
	  	echo '<td><span class="style1">Delete</span></td>';
	  ?>
        <td><span class="style1">ID</span></td>
        <td><span class="style1">FirstName</span></td>
        <td><span class="style1">LastName</span></td>
        <td><span class="style1">Date</span></td>
        <td><span class="style1"><center>Geospatial IP<br>
        <a href="http://www.hostip.info" target="_blank"><img src="http://www.hostip.info/images/button-86x18.gif"></a>
		</center></span></td>
        <td><span class="style1">Confirmation</span></td>
      </tr>
	  <?php //$row = 0+$startRow_rsResult ?>
      <?php do { ?>
	  <?php //$row == $row++ ?>
        <tr>
		  <?php 
	  if ((isset($_GET['ADMIN'])) && ($_GET['ADMIN'] = "On"))
	  	echo '<td><a href=Delete2.php?ID='.$row_rsResult['ID'].'>Delete<a></td>';
	  ?>
          <td><?php echo $row_rsResult['ID']; ?> <?php //echo $row ?> </td>
          <td><?php echo $row_rsResult['FirstName']; ?></td>
          <td><?php echo $row_rsResult['LastName']; ?></td>
          <td><?php echo $row_rsResult['TSDate']; ?></td>
          <td bgcolor="#CEFF9C">
		  <?php 
		  $no_ip= $row_rsResult['IP']; 
		  $hostip= "http://api.hostip.info/get_html.php?ip=$no_ip"."&position=true";
		  $result = file($hostip);
		  for ($i=1; $i<=count($result); $i++)
		  {
		  echo $result[$i]. "<br>" ;
		  }
		  echo "<IMG SRC='http://api.hostip.info/flag.php?ip=". $row_rsResult['IP'] ."ALT='IP Address Lookup'>" ;
		  ?>
		  </td>
          <td><?php 
		  if ($row_rsResult['Confirmation'] == 1){
		  echo "Yes";
		  }
		  Else {
		  echo "No";
		  }
		  ; ?></td>
        </tr>
        <?php } while ($row_rsResult = mysql_fetch_assoc($rsResult)); ?>
    </table>
    <table border="0" width="50%" align="center">
      <tr>
        <td width="23%" align="center"><?php if ($pageNum_rsResult > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_rsResult=%d%s", $currentPage, 0, $queryString_rsResult); ?>"><img src="Admin/Images/Begining.gif" width="21" height="21" border=0></a>
              <?php } // Show if not first page ?>
        </td>
        <td width="31%" align="center"><?php if ($pageNum_rsResult > 0) { // Show if not first page ?>
              <a href="<?php printf("%s?pageNum_rsResult=%d%s", $currentPage, max(0, $pageNum_rsResult - 1), $queryString_rsResult); ?>"><img src="Admin/Images/backward.gif" width="20" height="20" border=0></a>
              <?php } // Show if not first page ?>
        </td>
        <td width="23%" align="center"><?php if ($pageNum_rsResult < $totalPages_rsResult) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_rsResult=%d%s", $currentPage, min($totalPages_rsResult, $pageNum_rsResult + 1), $queryString_rsResult); ?>"><img src="Admin/Images/Forward.gif" width="21" height="20" border=0></a>
              <?php } // Show if not last page ?>
        </td>
        <td width="23%" align="center"><?php if ($pageNum_rsResult < $totalPages_rsResult) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_rsResult=%d%s", $currentPage, $totalPages_rsResult, $queryString_rsResult); ?>"><img src="Admin/Images/End.gif" width="22" height="21" border=0></a>
              <?php } // Show if not last page ?>
        </td>
      </tr>
    </table></td>
    <td width="100%">&nbsp;</td>
  </tr>
  <tr>
    <td width="382">&nbsp;</td>
    <td width="50">&nbsp;</td>
    <td width="305">&nbsp;</td>
    <td width="378">&nbsp;</td>
    <td width="50">&nbsp;</td>
    <td width="190">&nbsp;</td>
	<td width="100%">&nbsp;</td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($rsResult);
?>
