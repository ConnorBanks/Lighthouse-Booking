<?php 
// PLEASE KEEP COMMENTS, THEY ARE USED IN ADMIN \\

// WEBSITE SETTINGS \\
if ($_SERVER['DOCUMENT_ROOT'] == '/home/lighthouserest/X4TK534P/htdocs/' OR $_SERVER['HTTP_HOST'] == 'localhost')
{
	define('DOMAIN', 'lighthouserest.bpweb.net/');
	define('FULLDOMAIN', 'http://lighthouserest.bpweb.net/');
}
else
{
	define('DOMAIN', 'bookings.lighthouserestaurant.co.uk/');
	define('FULLDOMAIN', 'https://bookings.lighthouserestaurant.co.uk/');	
}
define('LOCALHOST_PATH', '/L/Lighthouse Restaurant/');
define('SERVER_PATH', '/');
// END WEBSITE SETTINGS \\

// CLIENT DETAILS \\
define('BUSINESS', 'Lighthouse Restaurant');
define('ADDRESS1', '77 High St');
define('ADDRESS2', '');
define('TOWN', 'Aldeburgh');
define('COUNTY', 'Suffolk');
define('POSTCODE', 'IP15 5AU');
define('TELEPHONE', '01728 453377');
define('EMAIL', 'support@worldwidewebdesign.co.uk');
// END CLIENT DETAILS \\

// SOCIAL MEDIA \\
define('FACEBOOK', '');
define('TWITTER', '');
define('YOUTUBE', '');
define('INSTAGRAM', '');
define('LINKEDIN', '');
// END SOCIAL MEDIA \\

// LICENCE KEY \\
define('LICENCE_KEY', '');
// END LICENCE KEY \\

// GOOGLE RECAPTCHA \\
define('GOOGLE_RECAPTCHA_KEY', '');
define('GOOGLE_RECAPTCHA_SECRET', '');
// END GOOGLE RECAPTCHA \\

// CUSTOM SETTINGS \\
define('SERVER', $_SERVER['DOCUMENT_ROOT'].($_SERVER['HTTP_HOST']=='localhost'?LOCALHOST_PATH:SERVER_PATH));
// END CUSTOM SETTINGS \\


// IP UPDATE \\
$curl = curl_init('https://www.worldwidewebdesign.co.uk/admin/master/masterIP/masterIP.txt');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
curl_setopt($curl, CURLOPT_USERPWD, "M4k1ngSyst3m5W0rk:UIZH1sGTHvd2s6J7imUr");

define('MASTER_IP', curl_exec($curl));

curl_close($curl);
?>