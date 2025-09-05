<?php 
include 'connect.php';
include 'globalsettings.php';
require_once 'master/classes/CMS/Component/Encryption/class.proCrypt.php';
require 'functions.php';

$date1 = date('Y-m-d',strtotime('today +1 week'));
$date2 = date('Y-m-d',strtotime('today +1 day'));

// 1 WEEEK BEFORE \\
$results = database("SELECT * FROM bookings WHERE party_date='$date1' ORDER BY booking_id");
foreach ($results as $result) 
{
	$_useraccountid = $result['useraccountid'];
    $rd = strtotime($result['party_date']);
    $tm = $result['party_time'];
    $ps = $result['party_size'];
    $rq = $result['requests'];
    $em = $result['email_address'];
    include 'bookingReminderEmail.php';    
}

// 1 DAY BEFORE \\
$results = database("SELECT * FROM bookings WHERE party_date='$date2' ORDER BY booking_id");
foreach ($results as $result) 
{
	$_useraccountid = $result['useraccountid'];
    $rd = strtotime($result['party_date']);
    $tm = $result['party_time'];
    $ps = $result['party_size'];
    $rq = $result['requests'];
    $em = $result['email_address'];
    include 'bookingReminderEmail.php';
}
?>