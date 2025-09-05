<?php require 'headerAdmin.php'; ?>
    <?php 
    include 'globalsettings.php';
    require 'navigationAdmin.php'; 
    require_once 'master/classes/CMS/Component/Encryption/class.proCrypt.php';
    require 'functions.php';

	if (isset($_GET['removeprofilenow']))
	{
		$useraccountid = $_GET['removeprofilenow'];
		$result = database("SELECT * FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1");
		$salt = $crypt->decrypt($result['pin']);

		database("DELETE FROM useraccounts WHERE useraccountid='$useraccountid'");
		database("DELETE FROM useraccounts_keydates WHERE useraccountid='$useraccountid'");
		database("UPDATE bookings SET useraccountid='0' WHERE useraccountid='$useraccountid'");

		$MODULE_ID = 'profile_locks';
		$path = SERVER.'master/'; $file = $MODULE_ID.'.txt';
		pw_file_update($path.$file, $useraccountid, '', $salt);
		
		header('Location: admin.php');
		exit();
	}

	if (isset($_GET['sendresetemail']))
	{
		$senderemail = 'info@lighthouserestaurant.co.uk';
		$business = 'Lighthouse Restaurant';

		$reset_id = $useraccountid = $_GET['sendresetemail'];
		$result = database("SELECT * FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1");
		$firstname = $result['firstname'];
		if ($firstname == '') {$cur_name = $result['name'];}
		else {$cur_name = $firstname;}
		$salt = $crypt->decrypt($result['pin']);
		$username = $crypt->decrypt($result['username']);
        $email = (strpos($username, '@')?$username:$result['email']);

		// ***** SEND EMAIL *****
		$reset_pin = "";  for ($pincount=0;$pincount<5;$pincount++) {$reset_pin.=chr(mt_rand(65,90));} 
		$reset_pin_hash = $crypt->hash($reset_pin,$salt);

		$data_array = array(
            'reset_id' => $reset_id,
            'date_str' => strtotime('now'),
            'reset_pin_hash' => $reset_pin_hash,
            'user_ip' => $_SERVER['REMOTE_ADDR']
		);

		$LINK = '<a href="'.(strpos(FULLDOMAIN, 'www.')&&strpos(FULLDOMAIN, 'bpweb.net')?str_replace('www.', '', FULLDOMAIN):FULLDOMAIN).str_replace(' ', '%20', SERVER_PATH).'youraccount.php?reset='.urlencode($crypt->encrypt(json_encode($data_array))).'">Reset Password Now</a>';

		$MODULE_ID = 'profile_locks';
		$temp_pw = "R:".$reset_pin_hash.":".$reset_id."";

		$title = $business." - Password Reset";

        $messagebody = email_template(
            'Dear '.$cur_name.',' . "<br />" .
          	'Please use this pin with the link below and follow on screen instructions to reset your password:' . "<br />" .
          	'<h2>PIN: ' . $reset_pin . "</h2>" .
          	$LINK . "<br />" .
          	'<h3>Please note that this link will expire if not used within 24 hours.</h3>' . "<br />" .
          	'<hr />' . 
	        'Once your password has been changed you will be able to log in with your username:' . "<br />" .
          	'<h3>'.$email . '</h3> and the password you supply' . "<br />" . "<br />" .
          	$business .
          	"<br />"
        );
          
        // SEND VIA PHP MAIL FUNCTION \\
        /*$headers = 'X-Mailer: PHP/' . phpversion() ."\n" ; 
        $headers .= 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
        $headers .= 'From: ' . $business . '<'.$senderemail.'>'."\n";
        $headers .= 'Reply-To: ' . $business . '<'.$senderemail.'>' . "\n";

        $sent_mail = mail($email, $title, $messagebody, $headers);*/
        $email_setup = array(
		    //'debug' => true,
			'from' => array(
				'email' => $senderemail,
				'name' => $business
			),
			'to' => array(
				array('email' => $email),
			),
			'subject' => $title,
			'body' => $messagebody,
			'html_email' => true
		);
		$sent_mail = phpmailer($email_setup);
        if ($sent_mail)
        {
          	$path = SERVER.'master/'; $file = $MODULE_ID.'.txt';
          	pw_file_update($path.$file, $reset_pin, $temp_pw, $salt);
          	?>
          	<div style="background-color: green;color: #fff;padding: 5px;text-align: center;">
            	<h2 style="margin: 0px;">Reset password email sent successfully to <?php echo $email; ?></h2>
          	</div>
          	<?php
        }
        $_GET['viewprofile'] = $useraccountid;
	}

	if(isset($_POST['prefrencesubmit']))
	{ 
		$useraccountid = $_GET['viewprofile'];
		$allergyinfo = $_POST['allergyinfo'];
		$seatingpref = $_POST['seatingpref'];
		$winepref  = $_POST['winepref'];

		$sql = database("UPDATE useraccounts SET 
			allergyinfo = '$allergyinfo',
			seatingpref = '$seatingpref',
			winepref = '$winepref' WHERE useraccountid='$useraccountid'");

			?>
			<div class="alert alert-success">
				Users preferences has been updated
			</div>  				
			<?php 
	}

	if(isset($_GET['editprefrences']))
	{  
		$useraccountid = $_GET['viewprofile'];
		$preferences = database("SELECT allergyinfo, seatingpref, winepref FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1"); 
		$allergyinfo = $preferences['allergyinfo'];
		$seatingpref = $preferences['seatingpref'];
		$winepref = $preferences['winepref'];
		?>
		<div class="background--white max-width--1000 margin-auto--left margin-auto--right padding--normal">
			<div class="row">
				<div class="col-sm-12">
					<form action="<?php echo $_SERVER['PHP_SELF'].'?viewprofile='.$useraccountid.''; ?>" method="POST">
						<input type="hidden" name="prefrencesubmit">
						<div class="form-group col-sm-4">
							<label>Allergy Information</label>
							<textarea class="form-control" style="height:150px" name="allergyinfo"><?php echo $allergyinfo ?></textarea>
						</div>
						<div class="form-group col-sm-4">
							<label>Preferred Seating Area</label>
							<textarea class="form-control" style="height:150px" name="seatingpref"><?php echo $seatingpref ?></textarea>
						</div>
						<div class="form-group col-sm-4">
							<label>Wine Preferences</label>
							<textarea class="form-control" style="height:150px" name="winepref"><?php echo $winepref ?></textarea>
						</div>
						<input type="submit" class="btn btn-default">
					</form>
				</div>
			</div>
		</div>
		<?php
	}
	else
	{
	    $useraccountid = $_GET['viewprofile'];
	    $result = database("SELECT * FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1");
	    $name = $result['name'];
	    $telephone = $result['telephone'];
	    $email = $crypt->decrypt($result['email']);
	    $lastlogin = $result['lastlogin'];
	    $allergyinfo = $result['allergyinfo'];
		$seatingpref = $result['seatingpref'];
		$winepref = $result['winepref'];	
    ?>
    <div class="background--white max-width--1000 margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--normal padding-right--normal">
    	<?php if (!isset($_GET['removeprofile'])) { ?>
	    	<p>
	    		<a class="background--primary padding--normal padding-top--tiny padding-bottom--tiny" style="float:right;" href="../index.php?useraccountid=<?php echo $useraccountid; ?>" target="_blank">Add a Booking</a>
	    		<a class="background--primary padding--normal padding-top--tiny padding-bottom--tiny" href="admin.php">Back</a>
	    	</p>
	    <?php } elseif (isset($_GET['removeprofile'])) { ?>
	    	<p style="border-bottom: 2px solid #000;padding-bottom: 20px;">
	    		<b>Are you sure you want to delete this profile, it is not recoverable if you click yes</b><br /><br />
	    		<a class="background--primary padding--normal padding-top--tiny padding-bottom--tiny" style="float:right;background-color: red;" href="<?php echo $_SERVER['PHP_SELF']; ?>?removeprofilenow=<?php echo $useraccountid; ?>" target="_blank">Yes, remove profile</a>
	    		<a class="background--primary padding--normal padding-top--tiny padding-bottom--tiny" href="<?php echo $_SERVER['PHP_SELF']; ?>?viewprofile=<?php echo $useraccountid; ?>">Cancel</a>
	    	</p>
		<?php } ?>
	    <div style="display:block;width: 100%;">
	    	<h2><?php echo $name; ?></h2>
	    	<div style="display:inline-block;width: 49%;vertical-align: top;">	    		
	    		<h4><b>Tel:</b> <a style="color:#000;text-decoration: underline;" href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a></h4>
	    		<h4><b>Email:</b> <a style="color:#000;text-decoration: underline;" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></h4>
	    	</div>
	    	<div style="display:inline-block;width: 49%;vertical-align: top;">
				<?php 
	            echo '<ul>';
	            	echo '<li><b>Allergy Info:</b> '.$allergyinfo.'</li><br />' . "\n";
	            	echo '<li><b>Seating Preference:</b> '.$seatingpref.'</li><br />' . "\n";
	            	echo '<li><b>Wine Preference:</b> '.$winepref.'</li><br />' . "\n";
	            echo '</ul>'; 
	            ?>
	    	</div>
	    	<?php if (!isset($_GET['removeprofile'])) { ?>
    			<a class="background--primary padding--normal padding-top--tiny padding-bottom--tiny" style="background-color: red;font-size: 14px" href="<?php echo $_SERVER['PHP_SELF']; ?>?viewprofile=<?php echo $useraccountid; ?>&amp;removeprofile=<?php echo $useraccountid; ?>">Remove Profile</a>
    			<a class="background--primary padding--normal padding-top--tiny padding-bottom--tiny" style="background-color: #faae18;font-size: 14px" href="<?php echo $_SERVER['PHP_SELF']; ?>?viewprofile=<?php echo $useraccountid; ?>&amp;editprefrences=<?php echo $useraccountid; ?>">Edit Prefrences</a>
    			<a class="background--primary padding--normal padding-top--tiny padding-bottom--tiny" style="background-color: #363636;font-size: 14px" href="<?php echo $_SERVER['PHP_SELF']; ?>?sendresetemail=<?php echo $useraccountid; ?>">Send Reset Password</a>
    		<?php } ?>
	    </div>
        <div class="bookings" style="margin-top: 40px;">
        	<h2>Bookings:</h2>
			<div class="table-container">
				<table>
		            <thead>
	                    <tr>
	                        <th>Time</th>
	                        <th>Party Size</th>                  
	                        <th>Requests</th>
	                        <th>Edit</th>
	                        <th>Cancel</th>
	                    </tr>
	                </thead>
	            <tbody>
		<?php
		if ($nomore <> 'true') 
		{ 
		  	$results = database("SELECT * FROM bookings WHERE useraccountid ='$useraccountid' $searchsql ORDER BY party_date DESC, party_time DESC, booking_id DESC");
		  	foreach ($results as $result)
		  	{
		  		$booking_id = $result['booking_id'];
		  		$party_date = date('dS M Y',strtotime($result['party_date']));
			  	$party_time = $result['party_time'];
			  	$party_duration = $result['duration'];
			  	$party_size = $result['party_size'];
			  	$requests = $result['requests'];

			  	echo '<tr>';
				  	echo '<td>' . $party_date.' From '. $party_time .'</td>';
			  		echo '<td>'.$party_size.'</td>';
			  		echo '<td>'.$requests.'</td>';
			  		if (strtotime($result['party_date'])>strtotime($today))
			  		{
			  			echo '<td><a class="icon icon--small icon--edit" href="adminAmendBooking.php?booking_id='.$booking_id.'&amp;returnurl=profile.php?viewprofile='.$useraccountid.'"></a></td>';
			  			echo '<td><a class="icon icon--small icon--cancel" href="adminAmendBooking.php?booking_id='.$booking_id.'&amp;returnurl=profile.php?viewprofile='.$useraccountid.'"></a></td>';
			  		}
			  		else
			  		{
			  			echo '<td></td>';
			  			echo '<td></td>'; 
			  		}
				echo '</tr>';
		  	}
		} 
		?>
				</tbody>
			  </table>
			</div>
        </div> 
    </div>
<?php 
	}
require 'footer.php'; 
?>
