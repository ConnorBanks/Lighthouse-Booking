<?php require 'header.php'; 
require __DIR__ . '/bootstrap.php';?>

<?php 
if ($_SESSION['bookingcompleted'] == 'true')
{
	$booking_id = $_SESSION['booking_id'];
	$result = database("SELECT * FROM bookings WHERE booking_id='$booking_id' LIMIT 1");
	$ps = $result['party_size'];
	$rd = strtotime($result['party_date']);
	$tm = $result['party_time'];

	$message = "Thank you for booking, we look forward to seeing you on the " . date("jS F Y", $rd) . " for " . $ps . ' ' . ($ps==1?'Guest':'Guests') . " at " . date('H:i',strtotime($tm)) . ".";
}
else
{
	$message = "Sorry, there was an error processing your booking, please try again or use a contact method below to book manually.";
}
?>
<div class="container background-image--cover" <?php if (!$_SESSION['admin_access'] OR isset($_GET['useraccountid'])) { ?> style="background-image:url('images/wine.jpg')" <?php } ?>>

    <div class="modal background--grey">

		<p class="position--relative margin-bottom--normal text-align--center padding-right--large padding-left--large" style="line-height:1.5;">
			Booking Processed
		</p>

		<hr class="margin-bottom--large" />

		<p class="padding-left--large padding-right--large" style="line-height:1.5;"><?php echo $message; ?></p>

		<p class="padding-left--large padding-right--large" style="line-height:1.5;">If you need to contact us in the mean time, call us on <a href="tel:01728453377">01728 453377</a>, email us at <a href="mailto:info@lighthouserestaurant.co.uk">info@lighthouserestaurant.co.uk</a> or follow us on the social media channels shown below.</p>

		<p class="margin-bottom--large padding-left--large padding-right--large" style="line-height:1.5;"><a href="/">Click here to return to the homepage</a>, if you want to read more about the <a href="http://www.lighthouserestaurant.co.uk">lighthouse restaurant click here</a>.</p>

		<ul class="padding-left--large padding-right--large" style="text-align: center;">
			<a href="https://twitter.com/AldeLighthouse/"><li class="icon icon--twitter"></li></a>
			<a href="https://www.facebook.com/AldeLighthouse/"><li class="icon icon--instagram margin-left--normal"></li></a>
			<a href="https://www.instagram.com/aldelighthouse/"><li class="icon icon--facebook margin-left--normal"></li></a>
		</ul>
		<a href="index.php" class="submit-a-tag">Book Another Table</a>

	</div>

</div>

<?php if (isset($_SESSION['admin_access'])) {unset($_SESSION['admin_access']);} ?>

<?php if (isset($_SESSION['bookingcompleted'])) {unset($_SESSION['bookingcompleted']);} ?>

<?php require 'footer.php'; ?>