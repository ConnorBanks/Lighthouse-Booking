<?php 
$emailtitle = $title;
$senderemail = EMAIL;
$domainname = DOMAIN;
$business = BUSINESS;
		
$messagebody = 	'<html>
<head>
  <title>'.$emailtitle.'</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<style type="text/css">
body {
	margin: 0;
    font-family: Roboto, sans-serif;
    width: 100%;
    min-height: 100vh;
    background-image: url("https://bookings.lighthouserestaurant.co.uk/images/wine.jpg");
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
.container {
	padding: 80px 0px;
}
.page {
	min-width: 550px;
    max-width: 100%;
    background-color: rgba(54, 54, 54, .95);
    max-width: 820px;
    margin: 0 auto;
    padding: 40px;
    color: #fff;
    text-align: center;
}
.textbox {
	background-color: #fff;
	color: rgba(54, 54, 54, 1);
	margin: 30px;
}
.textbox p {
	padding: 30px;
	margin: 0px;
}

</style>

</head>
<body bgcolor="#c9c9c9">
	<div class="container">
		<div class="page">
			<img src="https://bookings.lighthouserestaurant.co.uk/images/lighthouse-restaurant-logo.png">
			<div class="textbox">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
				tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
				quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
				consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
				cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
				proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
			</div>
		</div>
	</div>
</body>
</html>'
;

	$email = 'tom@worldwidewebdesign.co.uk';
    $headers = 'X-Mailer: PHP/' . phpversion() ."\n" ; 
	$headers .= 'MIME-Version: 1.0' . "\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
	$headers .= 'From: ' . BUSINESS . '<'.$senderemail.'>'."\n";
	$headers .= 'Reply-To: ' . BUSINESS . '<'.$senderemail.'>' . "\n";

	mail($email, $emailtitle, $messagebody, $headers);

?>
