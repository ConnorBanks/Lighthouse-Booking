<?php
$showechos = 'N';

if (isset($_GET['returnurl']))
{
	$returnurl = str_replace('_', '&', $_GET['returnurl']);

	$end_datetime = date('Y-m-d H:i:s');
	$start_datetime = date('Y-m-d H:i:s',strtotime(date("Y-m-d H:i:s") . " -10 hours"));

	$query = "SELECT DISTINCT shopaccounts.* FROM shopbasket, shopaccounts WHERE 
		shopbasket.accountid=shopaccounts.accountid AND shopaccounts.sessionid = '$sessionid' 
		ORDER BY shopaccounts.date DESC";
	if ($showechos == 'Y') {echo '<p>'.$query.'</p>';}
	$results = database($query);
	$accountrecord_num = count($results);
	if ($accountrecord_num > 0)
	{
		$proceed = 'no';
		foreach($results as $result)
		{
			$accountid = $result['accountid'];
			$date = $result['date'];

			if ($showechos == 'Y') {echo "<p>$accountid: $date > $start_datetime && $date < $end_datetime | $accountid</p>";}

			if (strtotime($date) > strtotime($start_datetime) && strtotime($date) < strtotime($end_datetime))
			{
				$proceed = 'yes';
				break;
			}
		}

		if ($proceed == 'yes')
		{
			$_SESSION['accountid'] = (int) $accountid;

			$results = database("SELECT * FROM shoporders WHERE accountid='$accountid' AND ordernumber='0' ORDER BY orderid DESC");
			if (count($results) > 0)
			{
				$result = $results[0];

				$_SESSION['cust_name']      = $result['name'];
				$_SESSION['cust_address1']  = $result['address1'];
				$_SESSION['cust_address2']  = $result['address2'];
				$_SESSION['cust_town']      = $result['town'];
				$_SESSION['cust_county']    = $result['county'];
				$_SESSION['cust_postcode']  = $result['postcode'];
				$_SESSION['cust_country']   = $result['buyercountry'];
				$_SESSION['cust_telephone'] = $result['telephone'];
				$_SESSION['cust_email']     = $result['email'];

				$_SESSION['recipient_name']       = $result['recipient'];
				$_SESSION['recipient_address1']   = $result['recipientaddress1'];
				$_SESSION['recipient_address2']   = $result['recipientaddress2'];
				$_SESSION['recipient_town']       = $result['recipienttown'];
				$_SESSION['recipient_county']     = $result['recipientcounty'];
				$_SESSION['recipient_postcode']   = $result['recipientpostcode'];
				$_SESSION['countryid']            = $_SESSION['recipient_country'] = $result['country'];
				$_SESSION['recipient_telephone']  = $result['recipienttelephone'];
			}

			header('Location: '.$returnurl);
			exit();
		}
		else
		{
			header('Location: timedout.php');		
			exit();
		}
	}
	else
	{
		header('Location: timedout.php');
		exit();
	}
}
?>