<?php 
include 'globalsettings.php';
require_once SERVER.'master/classes/CMS/Component/Encryption/class.proCrypt.php';
session_start();

function emailvalidation($email)
{   $emailcorrect='yes';
$findme1='@';
$findme2='.';
$pos1 = strpos($email, $findme1);
if ($pos1 < '2'){$emailcorrect='no';}
$domain = strstr($email, '@');
if ($domain==''){$emailcorrect='no';}
$pos2 = strpos($domain, $findme2);
if ($pos2 < '3'){$emailcorrect='no';}
return $emailcorrect;
}

if (isset($_POST['FirstName']))
{
	//print_r($_POST);
	//print_r($_SESSION);

	if (isset($_SESSION['booking_id'])) {unset($_SESSION['booking_id']);}

	$fn = str_replace("'", '’', $_POST["FirstName"]);
	$ln = str_replace("'", '’', $_POST["LastName"]);
	$em = str_replace("'", '’', $_POST["Email"]);
	$cust_email = $em;
	$te = str_replace("'", '’', $_POST["Telephone"]);
	$ai = str_replace("'", '’', $_POST["AllergyInfo"]);
	$rq = str_replace("'", '’', $_POST["SpecialRequests"]);
	$ps = $_POST["PartySize"];
	$ml = $_POST["Meal"];
	$tm = $_POST['Time'];
	$tids = explode('|', $_POST['Tables']);
	$password = $_POST['password'];

	$pd = $_POST["PartyDate"];

	$staffname = str_replace("'", '’', $_POST['staffname']);
	$staffcomments = str_replace("'", '’', $_POST['staffcomments']);

	$rd = strtotime($pd);

	if ($ps == '') {$proceed = 'no';}
	elseif ($ml == '') {$proceed = 'no';}
	elseif ($ps == '') {$proceed = 'no';}
	elseif ($pd == '') {$proceed = 'no';}
	elseif ($tm == '') {$proceed = 'no';}
	elseif ($ln == '') {$proceed = 'no';}
	elseif ($te == '') {$proceed = 'no';}
	elseif (count($tids) == 0 && !isset($_SESSION['admin_access'])) {$proceed = 'no';}
	if (!$_SESSION['admin_access'] && $cust_email == '')		{$proceed='no';$emailerror='true';}
	elseif ($cust_email > '' && emailvalidation($cust_email) == 'no'){$proceed='no';$errornotvalidemail='true';}
	elseif ($cust_email > '' && $password > '')
	{
		$numemails = '0';
		$results = database("SELECT * FROM useraccounts ORDER BY useraccountid"); 
		foreach ($results as $result) 
		{
			if ($cust_email == $crypt->decrypt($result['username'])) 
			{
				$numemails = '1';
			}
		}
		if ($numemails > 0)
		{
			$proceed = 'no';
			$emailtaken = 'true';
		}
	}

	if ($proceed <> 'no' && $_SESSION['bookingcompleted'] <> 'true') 
	{
		$en_email = ($cust_email>''?$crypt->encrypt($cust_email):'');

		if (isset($_SESSION['useraccountid']) && $_SESSION['useraccountid'] > 0)
		{
			$_useraccountid = $_SESSION['useraccountid'];
		}
		elseif (isset($_POST['useraccountid']) && $_POST['useraccountid'] > 0)
		{
			$_useraccountid = $_POST['useraccountid'];
		}
		elseif ($_SESSION['login'] == false)
		{
			$_useraccountid = 0;
	      /*$results = database("SELECT * FROM useraccounts WHERE username > '' ORDER BY useraccountid"); 
	      foreach ($results as $result) 
	      {
	        if ($cust_email == $crypt->decrypt($result['username'])) 
	        {
	          $_useraccountid = $result['useraccountid'];
	          break;
	        }
	    }*/
	    if ($password > '' && $_useraccountid == 0)
	    {
	    	$username = $crypt->encrypt($cust_email);

	    	$sql1 = "INSERT INTO useraccounts SET
	    	username='$username',
	    	`date`=NOW(),
	    	accountstatus='2',
	    	name = '$fn $ln',
	    	firstname = '$fn',
	    	surname = '$ln',
	    	telephone='$te',
	    	email='$en_email',
	    	allergyinfo='$ai'";
	    	$_useraccountid = database($sql1);

	    	$salt_raw = str_shuffle(openssl_random_pseudo_bytes(30).$_useraccountid);
	    	$salt = $crypt->encrypt($salt_raw);

	    	$sql = "UPDATE useraccounts SET
	    	pin='$salt'
	    	WHERE useraccountid='$_useraccountid'";
	    	database($sql); 

	    	$path = SERVER.'master/'; $file = 'profile_locks.txt';
	    	pw_file_update($path.$file, $_useraccountid, $password, $salt_raw);
	    }
	}

	$table_location = database("SELECT * FROM tables_single WHERE table_id='".$tids[0]."' LIMIT 1");
	$location = $table_location['location'];

	$duration_result = database("SELECT * FROM slots_default_duration WHERE slot_time='$tm' LIMIT 1");
	$duration = $duration_result['slot_duration'];
	if ($duration == '') {$duration = $default_duration;}

	$sql = "INSERT INTO bookings SET
	first_name='$fn',
	last_name='$ln',
	email_address='$em',
	telephone='$te',
	requests='".($ai>''?$ai."\n".$rq:$rq)."',
	party_size='$ps',
	meal='$ml',
	party_date='$pd',
	party_time='$tm',
	duration='$duration',
	location='$location',
	notified='false',
	staffcomments='$staffcomments',
	staffname='$staffname',
	useraccountid='$_useraccountid'";
		//echo '<p>'.$sql.'</p>';
		//exit();
	$booking_id = database($sql);

	if (count($tids) > 0)
	{
		foreach ($tids as $table_id) 
		{
			$sql = "INSERT INTO bookings_tables SET
			booking_id='$booking_id',
			table_id='$table_id'";
				//echo '<p>'.$sql.'</p>';
			database($sql);
		}
	}

	$_SESSION['bookingcompleted'] = 'true';
	
	if (!$_SESSION['admin_access']) 
	{
			// customer email \\
		include 'bookingCustomerEmail.php';
		//mail('james@worldwidewebdesign.co.uk', $emailtitle, $messagebody, $headers, 'info@lighthouserestaurant.co.uk');
		$email_setup = array(
		    //'debug' => true,
			'from' => array(
				'email' => $senderemail,
				'name' => $business
			),
			'to' => array(
				array('email' => 'james@worldwidewebdesign.co.uk'),
			),
			'subject' => $emailtitle,
			'body' => $messagebody,
			'html_email' => true
		);
		$sent_mail = phpmailer($email_setup);
		//mail('kj@masshosting.co.uk', $emailtitle, $messagebody, $headers, 'info@lighthouserestaurant.co.uk');
		$email_setup = array(
		    //'debug' => true,
			'from' => array(
				'email' => $senderemail,
				'name' => $business
			),
			'to' => array(
				array('email' => 'kj@masshosting.co.uk'),
			),
			'subject' => $emailtitle,
			'body' => $messagebody,
			'html_email' => true
		);
		$sent_mail = phpmailer($email_setup);
		//mail('info@bestbookings.co.uk', $emailtitle, $messagebody, $headers);

			// client email \\
		$emailtitle = 'Booking Alert';
		$senderemail = 'info@lighthouserestaurant.co.uk';
		$domainname = 'lighthouserestaurant.co.uk';
		$business = 'Lighthouse Restaurant';

		$client_email = "There has been a new reservation made on the <b>".date("jS F Y", $rd)."</b> at <b>" . date('H:i',strtotime($tm)). "</b> for <b>" . $ps . " " . ($ps==1?'Guest':'Guests')."</b><br /><br />".
		"<b>Name:</b> ".$fn." ".$ln."<br />".
		"<b>Email Address:</b> ".$em."<br />".
		($rq>''?"<b>Notes</b> ".$rq:"")."<br />".
		"To view this booking, please visit  <a href='http://bookings.lighthouserestaurant.co.uk/bookings/admin'>http://bookings.lighthouserestaurant.co.uk/bookings/admin</a>";
		
		$messagebody = 	'<html>'.
		'<head>'.
		'<title>'.$emailtitle.'</title>'.
		'<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />'.
		'<style type="text/css">'.
		'body {
			margin: 0;
			font-family: Roboto, sans-serif;
			width: 100%;
		}
		.container {

		}
		.page {

		}
		.textbox {
			background-color: #fff;
			color: rgba(54, 54, 54, 1);
			padding: 30px;
		}
		.textbox p {
			margin: 0px;
		}'.
		'</style>'.
		'</head>'.
		'<body bgcolor="#c9c9c9">'.
		'<div class="container">'.
		'<div class="page">'.
		'<div class="textbox">'.
		'<img src="http://bookings.lighthouserestaurant.co.uk/images/lighthouse-restaurant-logo-dark.png" style="padding-bottom:30px;">'.
		'<p>'. $client_email .'</p>'.
		'</div>'.
		'</div>'.
		'</div>'.
		'</body>'.
		'</html>';
/*
		$headers = 'X-Mailer: PHP/' . phpversion() ."\n" ; 
		$headers .= 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		$headers .= 'From: ' . $business . '<'.$senderemail.'>'."\n";
		$headers .= 'Reply-To: ' . $business . '<'.$senderemail.'>' . "\n";
*/
		if ($error <> true)
		{
			//mail('info@lighthouserestaurant.co.uk', $emailtitle, $messagebody, $headers);
			$email_setup = array(
			    //'debug' => true,
				'from' => array(
					'email' => $senderemail,
					'name' => $business
				),
				'to' => array(
					array('email' => 'info@lighthouserestaurant.co.uk'),
				),
				'subject' => $emailtitle,
				'body' => $messagebody,
				'html_email' => true
			);
			$sent_mail = phpmailer($email_setup);
			//mail('kj@masshosting.co.uk', $emailtitle, $messagebody, $headers, 'info@lighthouserestaurant.co.uk');
			$email_setup = array(
			    //'debug' => true,
				'from' => array(
					'email' => $senderemail,
					'name' => $business
				),
				'to' => array(
					array('email' => 'kj@masshosting.co.uk'),
				),
				'subject' => $emailtitle,
				'body' => $messagebody,
				'html_email' => true
			);
			$sent_mail = phpmailer($email_setup);
			//mail('info@bestbookings.co.uk', $emailtitle, $messagebody, $headers, 'info@lighthouserestaurant.co.uk');
		}

	}
}

$_SESSION['booking_id'] = $booking_id;

if (isset($_SESSION['useraccountid']) && $_SESSION['useraccountid'] > 0)
{
	header('Location: myaccount/index.php');	
}
else
{
	header('Location: bookingSubmit.php');
}
exit();
}
?>