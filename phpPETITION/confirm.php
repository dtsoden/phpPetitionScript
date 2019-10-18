<?php require_once('Connections/petitionscript.php'); ?>
<?php
mysql_db_query("$database_petitionscript", "update signature set confirmation = '1' where Email = '" . urldecode($_GET['Email']) . "'");

echo "Thank you " . urldecode($_GET['FirstName']) . " Your email address<br>" . 
urldecode($_GET['Email']) . " has verified your signature";
echo "<br><a href='results.php'>Click Here</a> to see the signatures of this petition";
?>

