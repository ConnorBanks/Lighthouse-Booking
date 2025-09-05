<?php
session_start();
$_username = $_POST["Username"];
$_password = $_POST["Password"];

include ("connect.php");

//$conn = new PDO("mysql:host=localhost;dbname=lighthouse-booking","jack", "hack528618");

$query = database("SELECT * FROM users WHERE username='$_username' AND password=md5('$_password')");
$row = count($query);
$client_id = $query[0]['id'];
/*
// MASTER IP CHECK \\
if ($_username == 'master' && $_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['REMOTE_ADDR'] <> '88.97.47.220')
{
	$pass = false;

	if (file_exists('loginoverride.php'))
	{
  		include 'loginoverride.php';
  		if ($override_datetime_str > strtotime('now'))
  		{
    		$pass = true;
  		}
	}
	if ($pass == false)
	{
  		$loginid = $client_id;
  		$login_attempt = true;
  		include 'tracker.php';
	  	exit('<h1>Access Denied</h1>');
	}
}

// LOGIN ATTEMPT CHECKER/TRACKER \\
if ($client_id > 0)
{
	$loginid = $client_id;
	$login_attempt = true;
	include 'tracker.php';
	if (!isset($response['pass']))
	{
  		$response = array();
  		$curtime = date('H:i:s');
  		if ($curtime < '07:00:00' OR $curtime > '22:00:00')
  		{
    		$pass = false;
  		}
	}
	else
	{
  		$pass = $response['pass'];
	}
	if ($pass == false)
	{
  		exit('<h1>Access Denied</h1>');
	}
}  
*/
if ($row == true)
{
	$_SESSION['loginid'] = $client_id;
    $_SESSION["username"] = $query[0]["username"];
    $_SESSION["permissions"] = $query[0]["permission_level"];
    $_SESSION["loggedin"] = "true";

    // Redirect to the main menu
    header ("Location: admin.php");
    exit();

} else {
    //echo "<div id='logout'>Incorrect Password! Try again</div>";
    session_destroy();
    header('Location: control_panel.php?loginerror=true');
    exit();
}
?>
