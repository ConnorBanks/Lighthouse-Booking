<?php 
include_once("includes/beginnings.php");
include 'includes/secure.inc.php';
$pagetitle = 'Bookings - My Account - '.BUSINESS; $pageurl = "index.php"; 
$breadcrumbtitle = 'Bookings - My Account';
$title = 'Bookings - My Account';
$canonicalurl = '';

if (isset($_GET['cancelbooking']))
{
	$booking_id = $_GET['cancelbooking'];

	database("DELETE FROM bookings_tables WHERE booking_id='$booking_id'");
	database("DELETE FROM bookings WHERE booking_id='$booking_id'");

	header('Location: '.$_SERVER['PHP_SELF'].'?bookingdeleted');
	exit();
}

if (isset($_POST['selected_date'])) 
{ 
	if($_POST['selected_date'] == '') 
	{
		 $searchsql = ''; 
	}
	else
	{
		$selected_date = date('Y-m-d',strtotime($_POST['selected_date'])); 
		$searchsql = "AND party_date='$selected_date'"; 
	}
}
else 
{ 
	$selected_date = ''; 
}
?>
<!DOCTYPE html>
<html>
<head>
<?php include 'includes/meta.php'; ?>
<?php include 'includes/css.php'; ?>
<!--[if IE ]><meta http-equiv="X-UA-Compatible" content="IE=Edge"><![endif]-->
<!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lt IE 9]>
	<script src="js/modernizr.custom.11889.js" type="text/javascript"></script>
	<![endif]-->
<!-- HTML5 Shiv events (end)-->
</head>

<body>
<div class="main">
	<?php include 'includes/navigation.php'; ?>
	<div class="container">
		<?php
		if ($_SESSION['bookingcompleted'] == 'true')
		{
			$booking_id = $_SESSION['booking_id'];
			$result = database("SELECT * FROM bookings WHERE booking_id='$booking_id' LIMIT 1");
			$ps = $result['party_size'];
			$rd = strtotime($result['party_date']);
			$tm = $result['party_time'];

			$message = "Thank you for booking, we look forward to seeing you on the " . date("jS F Y", $rd) . " for " . $ps . ' ' . ($ps==1?'Guest':'Guests') . " at " . date('H:i',strtotime($tm)) . ".";

			?>
			<h4 style="color:green;"><?php echo $message; ?></h4>
			<?php 
			unset($_SESSION['bookingcompleted']);
		}
		?>
		<div class="col-sm-12">
			<h1>Your Bookings</h1>
		</div>		
		<?php include 'includes/modules/mybookings.php'; ?>
	</div>
</div>
</body>
<?php include 'includes/script.php'; ?>
<?php
if (isset($_GET['bookingamended']))
{
	?>
	<div id="bookingamended" class="bookingamended">
		Booking Amended
	</div>
	<script type="text/javascript">
		setInterval(function(){ 
			$('#bookingamended').hide('slow');
		}, 4000);
	</script>
	<?php
}
if (isset($_GET['bookingdeleted']))
{
	?>
	<div id="bookingdeleted" class="bookingdeleted">
		Booking Cancelled
	</div>
	<script type="text/javascript">
		setInterval(function(){ 
			$('#bookingdeleted').hide('slow');
		}, 4000);
	</script>
	<?php
}
?>
</html>