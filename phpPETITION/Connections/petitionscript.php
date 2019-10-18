<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_petitionscript = "localhost"; //<-- This setting as is usually works best
$database_petitionscript = "petitionscript"; //<-- LEAVE THIS ALONE unless... you want to host multiple petitions on the same server, then change this name and create a blank database on your MySQL server to match this name
$username_petitionscript = "User_Name"; //<-- Replace "User_Name" with your username
$password_petitionscript = "PASSWORD"; //<-- Replace "PASSWORD" with your password
$petitionscript = mysql_pconnect($hostname_petitionscript, $username_petitionscript, $password_petitionscript) or trigger_error(mysql_error(),E_USER_ERROR); 
?>