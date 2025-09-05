<?php 
/*
//if (strpos($_SERVER['PHP_SELF'], 'admin'))
//{
	$TKR_SITE_ID = "214"; // ADD ID OF CUSTOM TRACKER SITE ID \\

	//require_once SERVER.'admin/includes/functions.php';
	
	if (!isset($loginid)) {$loginid = $_SESSION['loginid'];}

	if ($_SERVER['HTTP_HOST'] <> 'localhost')
	{
		if ($login_attempt == true)
		{
			$curl = curl_init('https://jamestest.bpweb.net/cmshub/api_login.php');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_USERPWD, "affordable:forgotten");
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'api_str='.json_encode(array(
				"TKR_SITE_ID" => $TKR_SITE_ID,
				"TKR_DATA" => array(
					array($_SESSION['loginid'],$_SESSION['username']),
					array(),
					$_SERVER['REMOTE_ADDR'],
					$_SERVER['PHP_SELF'],
					$_SERVER['REQUEST_URI']
				)
			)));
			$response = curl_exec($curl);
			$response = json_decode($response,true);
			curl_close($curl);
		}
		else
		{
			$curl = curl_init('https://jamestest.bpweb.net/cmshub/api_tracker.php');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_USERPWD, "affordable:forgotten");
			curl_setopt($curl, CURLOPT_POSTFIELDS, 'api_str='.json_encode(array(
				"TKR_SITE_ID" => $TKR_SITE_ID,
				"TKR_DATA" => array(
					array($_SESSION['loginid'],$_SESSION['username']),
					array(),
					$_SERVER['REMOTE_ADDR'],
					$_SERVER['PHP_SELF'],
					$_SERVER['REQUEST_URI']
				)
			)));
			$response = curl_exec($curl);
			$response = json_decode($response,true);
			curl_close($curl);
		}
	}
//}
*/
?>