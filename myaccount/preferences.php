<?php 
include_once("includes/beginnings.php");
include 'includes/secure.inc.php';
$pagetitle = 'Preferences - My Account - '.BUSINESS; $pageurl = "index.php"; 
$breadcrumbtitle = 'Preferences - My Account'; $pageid = '20';
$title = 'Preferences - My Account';
$canonicalurl = '';

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
		<script src="../js/jquery-3.1.1.min.js"></script>
        <script src="../js/jquery-ui.min.js"></script>
        <script src="../js/source.min.js"></script>
</head>

<body>
<div class="main">
	<?php include 'includes/navigation.php'; ?>
	<div class="container">
		<?php
		if(isset($_POST['prefrencesubmit']))
		{ 
			$allergyinfo = $_POST['allergyinfo'];
			$seatingpref = $_POST['seatingpref'];
			$winepref  = $_POST['winepref'];

			$sql = database("UPDATE useraccounts SET 
				allergyinfo = '$allergyinfo',
				seatingpref = '$seatingpref',
				winepref = '$winepref' WHERE useraccountid='$useraccountid'");

				?>
				<div class="alert alert-success">
					Your preferences has been updated
				</div>  				
				<?php 
		}

		if(isset($_POST['keydate']))
		{ 
			$keydate = date('Y-m-d',strtotime($_POST['keydate']));
			$keydate_name = $_POST['keydate_name'];

			$sql = database("INSERT INTO useraccounts_keydates SET 
				keydate = '$keydate',
				keydate_name = '$keydate_name',
				useraccountid='$useraccountid'");
			?>
			<div class="alert alert-success">
				A key date has been added
			</div>  				
			<?php 
		}
		elseif (isset($_GET['removedate']))
		{
			$keydateid = $_GET['removedate'];
			database("DELETE FROM useraccounts_keydates WHERE useraccountid='$useraccountid' AND keydateid='$keydateid'");
			?>
			<div class="alert alert-danger">
				A key date has been removed
			</div>  				
			<?php 
		}
		?>
		<div class="row">
			<div class="col-sm-12">
				<h1>Preferences</h1>
			</div>
			<?php include 'includes/modules/userpreferences.php'; ?>
			<hr />
			<?php include 'includes/modules/userpreferences_keydates.php'; ?>
		</div>
	</div>
</div>
</body>
<?php include 'includes/script.php'; ?>
</html>