<?php 
include_once("includes/beginnings.php");
include 'includes/secure.inc.php';
$pagetitle = 'Home - '.BUSINESS; $pageurl = "index.php"; 
$breadcrumbtitle = 'Home'; $pageid = '20';
$title = 'Home';
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
</head>

<body>
<div class="main">
	<?php include 'includes/navigation.php'; ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
			<h1>Settings</h1>
		<?php // ***** EDIT CONTACT DEATILS ******
		if (isset($_GET['editdetails'])): $nomore = 'true';
  			$result = database("SELECT * FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1");
			?>
			<h3>Update Contact details:</h3>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="editcontactnow" value="1" />
 				<div class="row">
					<div class="col-sm-6 col-xs-12">
						<div class="form-group">
							<label>Firstname <span class="required">*</span></label>
							<input class="form-control" type="text" name="firstname" required="true" value="<?php echo $result['firstname']; ?>" />
						</div>
					</div>
					<div class="col-sm-6 col-xs-12">
						<div class="form-group">
							<label>Surname <span class="required">*</span></label>
							<input class="form-control" type="text" name="surname" required="true" value="<?php echo $result['surname']; ?>" />
						</div>
					</div>
				</div>
				<?php /*				
				<div class="form-group">
					<label>Address 1 <span class="required">*</span></label>
					<input class="form-control" type="text" name="address1" required="true" value="<?php echo $result['address1']; ?>" />
				</div>
				<div class="form-group">
					<label>Address 2</label>
					<input class="form-control" type="text" name="address2" value="<?php echo $result['address2']; ?>" />
				</div>

				<div class="form-group">
					<label>Address 3</label>
					<input class="form-control" type="text" name="address3" value="<?php echo $result['address3']; ?>" />
				</div>
				
				<div class="form-group">
					<label>Town <span class="required">*</span></label>
					<input class="form-control" type="text" name="town" required="true" value="<?php echo $result['town']; ?>" />
				</div>
				<div class="form-group">
					<label>County <span class="required">*</span></label>
					<input class="form-control" type="text" name="county" required="true" value="<?php echo $result['county']; ?>" />
				</div>
				<div class="form-group">
					<label>Postcode <span class="required">*</span></label>
					<input class="form-control" type="text" name="postcode" required="true" value="<?php echo $result['postcode']; ?>" />
				</div>
				*/ ?>
				<div class="form-group">
					<label>Telephone</label>
					<input class="form-control" type="text" name="telephone" value="<?php echo $result['telephone']; ?>" />
				</div>
				<div class="form-group">
					<label>Email</label>
					<input class="form-control" type="text" name="email" value="<?php echo $crypt->decrypt($result['email']); ?>" />
				</div>
				<div class="form-group">
					<input class="btn btn-default" type="submit" value="Update" />
				</div>
			</form>
			<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>">cancel</a></p>

		<?php // ***** EDIT CONTACT DETAILS NOW *****
		elseif (isset($_POST['editcontactnow'])):
			$firstname = $_POST['firstname'];
		    $surname = $_POST['surname'];
		    $address1 = $_POST['address1'];
		    $address2 = $_POST['address2'];
		    $address3 = $_POST['address3'];
		    $town = $_POST['town'];
		    $county = $_POST['county'];
		    $postcode = $_POST['postcode'];
		    $telephone = $_POST['telephone'];
		    $email = $crypt->encrypt($_POST['email']);

		  	$_SESSION['accountname'] = $name;
		  	$_SESSION['cust_name'] = $name;
		  	$_SESSION['cust_address1'] = $address1;
		  	$_SESSION['cust_address2'] = $address2;
		  	$_SESSION['cust_town'] = $town;
		  	$_SESSION['cust_county'] = $county;
		  	$_SESSION['cust_postcode'] = $postcode;
		  	$_SESSION['cust_telephone'] = $telephone;
		  	$_SESSION['cust_email'] = $email;

		   	$sql = "UPDATE useraccounts SET
		   		firstname='$firstname',
		   		surname='$surname',
		   		name='$firstname $surname',
				address1='$address1',
				address2='$address2',
				address3='$address3',
				town='$town',
				county='$county',
				postcode='$postcode',
				telephone='$telephone',
				email='$email'
		    WHERE useraccountid = '$useraccountid'";
		   	database($sql);
		?>

		<?php // ***** ADD CONTACT NOW *****
		elseif (isset($_GET['changepassword'])): $nomore='true';
			?>
			<h3>Change Password</h3>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
				<input type="hidden" name="changepasswordnow" size="15" />
				<div class="form-group">
					<label>Old Password <span class="required">*</span></label>
					<input class="form-control" type="text" name="oldpassword" required="true" />
				</div>				
				<div class="form-group">
					<label>New Password <span class="required">*</span></label>
					<input class="form-control" type="password" name="password1" required="true" />
				</div>				
				<div class="form-group">
					<label>Confirm Password <span class="required">*</span></label>
					<input class="form-control" type="password" name="password2" required="true" />
				</div>				
  				<div class="form-group">
					<input class="btn btn-default" type="submit" value="Update" />
				</div>
			</form>
			<p><a href="<?php echo $_SERVER['PHP_SELF']; ?>">cancel</a></p>

		<?php // ***** ADD CONTACT NOW *****
		elseif (isset($_POST['changepasswordnow'])): 
  			$MODULE_ID = 'profile_locks'; $nomore = 'true';
  
    		$result = database("SELECT * FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1");
    		$salt = $crypt->decrypt($result['pin']);
    
    		$oldpassword = $crypt->hash($_POST['oldpassword'], $salt);
    		$password1 = $_POST['password1'];
    		$password2 = $_POST['password2'];

  			$passwords = pw_file_get(SERVER.'master/'.$MODULE_ID.'.txt');
  			$password = $passwords[$useraccountid];

  			if ($password1 <> $password2) 
  			{
  				$error_message = 'The two new passwords you typed are not identical';
  			}
  			elseif ($password1 == '')
  			{
  				$error_message = 'You must type a new password';
  				  			}
  			elseif ($password <> $oldpassword) 
  			{
  				$error_message = 'The old password you typed is not correct';
  			}

  			if ($error_message > '')
  			{
  				?>
				<div class="alert alert-danger">
					<strong>Error: </strong> <?php echo $error_message; ?> - <a href="<?php echo $_SERVER['PHP_SELF']; ?>?changepassword">Click here</a> to try again
				</div>  				
  				<?php
  			}
  			else 
  			{
    			$path = SERVER.'master/'; $file = $MODULE_ID.'.txt';
    			pw_file_update($path.$file, $useraccountid, $password1, $salt);
    			?>
    			<div class="alert alert-success">
					Your password has been changed
				</div>  				
    			<?php
  			}
			?>

		<?php else: endif; 
		if ($nomore <> 'true') 
		{
			?>
			<div class="row">
				<div class="col-sm-6 col-xs-12">
  					<h3>Your Contact details:</h3>
  					<?php
		  			$result = database("SELECT * FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1");
		    		$name = $result['name'];
		    		$address1 = $result['address1'];
		    		$address2 = $result['address2'];
		    		$address3 = $result['address3'];
		    		$address = $address1;
					if ($address2 > '') {$address .= '<br />' . $address2;}
					if ($address3 > '') {$address .= '<br />' . $address3;}
		    		$town = $result['town'];
		    		$county = $result['county'];
		    		$postcode = $result['postcode'];
		    		$telephone = $result['telephone'];
		    		$email = $crypt->decrypt($result['email']);
		   			
					echo 	'<p>' . $name . '<br />' . "\n";
					//echo	$address . '<br />' . "\n";
					//echo	$town . '<br />'. "\n";
					//echo	$county . '<br />'. "\n";
					//echo	$postcode . '<br />'. "\n";
					echo	$telephone . '<br />'. "\n";
					echo	' <a href="mailto:' .$email . '">' .$email . '</a></p>' . "\n";
					?>
				</div>
				<div class="col-sm-6 col-xs-12">
  					<h3>Edit your details:</h3>
  					<ul>
    					<li style="margin-bottom: 5px;"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?editdetails">Click here</a> to edit your contact details</b></li>
    					<li style="margin-bottom: 5px;"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?changepassword">Click here</a> to edit your password</b></li>
  					</ul>
  				</div>
  			</div>
  			<?php
		} 
		?>
			</div>
	</div>
</div>
</body>
<?php include 'includes/script.php'; ?>
</html>