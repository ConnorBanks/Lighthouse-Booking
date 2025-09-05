<?php
$emailtitle = 'Booking at Lighthouse Restaurant';
$senderemail = 'info@lighthouserestaurant.co.uk';
$domainname = 'lighthouserestaurant.co.uk';
$business = 'Lighthouse Restaurant';

$customer_email = "Thank you for your reservation, on the <b>".date("jS F Y", $rd)."</b> at <b>".date('H:i',strtotime($tm))."</b> for <b>".$ps." ".($ps==1?'Person':'People')."</b><br />".
($rq>''?"<b>Your notes with this booking:</b><br />".$rq:"")."<br />".
($password>''||$_useraccountid>0?"To amend this booking or view your other bookings, please visit <a href='http://bookings.lighthouserestaurant.co.uk/account_login.php'>MyAccount</a>":"");

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
					'<p>'. $customer_email .'</p>'.
				'</div>'.
			'</div>'.
		'</div>'.
	'</body>'.
'</html>';

$email = $cust_email;
/*
$headers = 'X-Mailer: PHP/' . phpversion() ."\n" ; 
$headers .= 'MIME-Version: 1.0' . "\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
$headers .= 'From: ' . $business . '<'.$senderemail.'>'."\n";
$headers .= 'Reply-To: ' . $business . '<'.$senderemail.'>' . "\n";

mail($email, $emailtitle, $messagebody, $headers, 'info@lighthouserestaurant.co.uk');
*/

$email_setup = array(
    //'debug' => true,
	'from' => array(
		'email' => $senderemail,
		'name' => $business
	),
	'to' => array(
		array('email' => $email),
	),
	'subject' => $emailtitle,
	'body' => $messagebody,
	'html_email' => true
);
$sent_mail = phpmailer($email_setup);
?>