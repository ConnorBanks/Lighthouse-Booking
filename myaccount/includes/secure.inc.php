<?php //require_once 'includes/access.inc.php';
if (!isset($_SESSION['login']))	
{
    header("Location: ../account_login.php");
	exit();
}
else 
{
	include_once 'includes/beginnings.php';
}
?>