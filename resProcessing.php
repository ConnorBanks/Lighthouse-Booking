<?php
//**** START Database connection script
// Connect to database script
include ("connect.php");
//**** END Database connection script

//**** START Create numbered seat buttons
if (isset($_POST['getSeatBtns'])){
	// Initialize our display output to NULL
	$spots = "";
	// Clean incoming POST to only allow numbers 0-9
	// This will be what table id we are using to build seat buttons
	$tid = preg_replace('#[^0-9]#', '', $_POST['getSeatBtns']);
	// If POST is empty after cleaning, exit script
	if($tid == ""){
		exit();
	}
	// Build our query and run it
	$sql = "SELECT * FROM available WHERE id='$tid'";
	$query = database($connect, $sql) or die (mysqli_error($connect));
	// Run a quick check to verify there are any results
	$quick_check = mysqli_num_rows($query);
	// If there are results to be had, get them in a loop
	if ($quick_check != 0){
		//while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC))
		foreach ($query as $row => MYSQLI_ASSOC) 
		{
			// Assign values from query
			$id = $row['id'];
			$tablenum = $row['tablenum'];
			$avail = $row['avail'];
			$price = $row['price'];

			$stBtn = "";
			// Build display output
			// VIP TABLE 1 has 7 seats available - $20.00 per seat
			$spots .= $tablenum.' now has '.$avail.' seat(s) available - '.$price.' per seat<br />';
			$spots .= "Click number of seats you want to reserve, will be held for 3 minutes once you click.<br />";
			// Construct our buttons for each seat that is available
			for ($k = 0 ; $k < $avail; $k++){
				$k2 = $k+1;
				// Give each button the id of tbid_ and the table id and seat number
				// tbid_3_7
				$stBtn .= '<button id="tbid_'.$id.'_'.$k2.'" onClick="reserveSeats(this.id)">'.$k2.'</button>';
			}
			// Add our seat buttons to the display output
			$spots .= "$stBtn<br />";
		}
	} else {
		// If they sat there too long without picking a table
		// Someone could have already reserved all the seats at that table
		$spots = "Sorry, seats are no longer available for that table.";
	}

	// Return our display output to ajax and exit
	echo $spots;
	exit();
}
//**** END Create numbered seat buttons

//**** START reserving number of seats
if (isset($_POST['reserve'])){
	// Initialize our display output to NULL
	$spots = "";
	// Clean incoming POST
	// $tid will hold the id of the table they are reserving
	// $num willl hold the number of seats they are reserving
	$tid = preg_replace('#[^0-9]#', '', $_POST['reserve']);
	$num = preg_replace('#[^0-9]#', '', $_POST['num']);
	// If POSTS are empty after cleaning, exit
	if($tid == "" || $num == ""){
		exit();
	}
	// Build our query and run it
	$sql = "SELECT * FROM available WHERE id='$tid' AND avail >='$num'";
	$query = database($connect, $sql) or die (mysqli_error($connect));
	// Run a quick check to verify there are any results
	$quick_check = mysqli_num_rows($query);
	// If there are results to be had, get them in a loop
	if ($quick_check != 0){
		//while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC))
		foreach ($query as $row => MYSQLI_ASSOC) 
		{
			// Assign values from query
			$id = $row['id'];
			$tablenum = $row['tablenum'];
			$avail = $row['avail'];
			$price = $row['price'];
			// Build display output
			// You just reserved 8 seats at table VIP Table 1
			$spots .= 'You just reserved '.$num.' seats at table '.$tid.'<br />';
			$spots .= 'You only have 3 minutes to finish or your reservation expires and the seats open up to other people.<br />';
		}
		// Math to change available number of seats for other visitors
		$availNow = $avail - $num;
		// Update the database to reflect new number of seats for that table
		$sql = "UPDATE available SET avail='$availNow' WHERE id='$id' LIMIT 1";
		$query = database($connect, $sql);
		// Insert the reserved table and number of seats into the reserves table
		$sql = "INSERT INTO reserves(tablenumber,numseats,restime) VALUES ('$tablenum','$num',now())";
		$query = database($connect, $sql);
		// Get the insert id
		$reserveID = mysqli_insert_id($connect);
		/////////////////////////////////////////////////////
		////////// If you want paypal do it here ////////////
		/////////////////////////////////////////////////////
		$spots .= 'Add paypal form here if wanted<br />';
		// Just a simple name/email form to "buy" the seats they reserved
		// We are cancelling the submit to handle with ajax
		$spots .= '<form name="confirmform" id="confirmform" onSubmit="return false;">';
    	$spots .= 'Name:<input id="name" type="text"><br />';
		// hidden field holds the table name
		$spots .= '<input id="tableNumber" type="hidden" value="'.$tablenum.'">';
		// hidden field holds the number of seats
		$spots .= '<input id="numSeats" type="hidden" value="'.$num.'">';
		// hidden field holds the reserve insert id
		$spots .= '<input id="reserveID" type="hidden" value="'.$reserveID.'">';
		// On submit call js function
		$spots .= '<button id="confirmbtn" onClick="confirmSeats()">Buy Seats</button>';
		// Give them a cancel button if they change their mind
		$spots .= '&nbsp;&nbsp;&nbsp;<button onClick="cancelReserve(''.$reserveID.'')">Cancel Reserved Seats</button>';
 		$spots .= '</form>';
		/////////////////////////////////////////////////////
		/////////////////////End paypal//////////////////////
		/////////////////////////////////////////////////////
	} else {
		$spots .= "Sorry, someone just reserved those. Try another table";
		$reserveID = "open";
	}
	// Return our output to ajax and exit
	echo "$spots|$reserveID";
	exit();
}
//**** END reserving number of seats

//**** START register/buy seats
if (isset($_POST['confirm'])){
	// Initialize our display output to NULL
	$response = "";
	// Clean incoming POST
	// $rid is our id from when we inserted into the reserve table
	// $name is the users name
	// $tableNumber is the table number they are paying for
	// $numSeats is the number of seats they are paying for
	$rid = preg_replace('#[^0-9]#', '', $_POST['confirm']);
	$name = preg_replace('#[^a-z0-9 ]#i', '', $_POST['n']);
	$tableNumber = preg_replace('#[^a-z0-9 ]#i', '', $_POST['tn']);
	$numSeats = preg_replace('#[^0-9]#', '', $_POST['ns']);
	// If POSTS are empty after cleaning, exit
	if($rid == "" || $name == "" || $tableNumber == "" || $numSeats == ""){
		exit();
	}
	// See if reservation has timed out by seeing if the...
	// id from when we inserted into the reserve table still exists
	$sql = "SELECT id FROM reserves WHERE id='$rid' LIMIT 1";
	$query = database($connect, $sql) or die (mysqli_error($connect));
	$quick_check = mysqli_num_rows($query);
	// If that id has timed out and been removed due to....
	// them being slow, we need to see if their # of seats...
	// is still available for that table
	if ($quick_check != 1){
		// Select with table name and number of seats they want
		$sql = "SELECT id, avail FROM available WHERE tablenum='$tableNumber' AND avail>='$numSeats' LIMIT 1";
		$query = database($connect, $sql) or die (mysqli_error($connect));
		$quick_check2 = mysqli_num_rows($query);
		// If not available any longer...
		// They farted around too long
		// tell them they are too slow
		if ($quick_check2 == 0){
			$confirmedStatus = "false";
			$response = "Your reservation expired, please start over by refreshing page";
			echo "$confirmedStatus|$response";
			exit();
		} else {
			// They are still available even though they timed out
			// Grab that id and number available so they can buy them
			//while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC))
			foreach ($query as $row => MYSQLI_ASSOC)
			{
				$id = $row['id'];
				$avail = $row['avail'];
			}
			// Math to calculate new seats available after purchase
			$availNow = $avail - $numSeats;
			// Update table to reflect seats that were just bought
			$sql = "UPDATE available SET avail='$availNow' WHERE id='$id' LIMIT 1";
			$query = database($connect, $sql);
			// Insert directly into confirms table after they pay
			// We are skipping the reserves table as we don't need it
			$sql = "INSERT INTO confirms(tablename,numseats,person) VALUES ('$tableNumber','$numSeats','$name')";
			$query = database($connect, $sql);
			// Build display output
			$confirmedStatus = "true";
			$response = 'only needed for fail';
			// Return display output to ajax and exit
			echo "$confirmedStatus|$response";
			exit();
		}
	} else {
		// They have not timed out so let them buy the seats
		// Insert them in the confirms table
		$sql = "INSERT INTO confirms(tablename,numseats,person) VALUES ('$tableNumber','$numSeats','$name')";
		$query = database($connect, $sql);
		// Delete the reservation data as they have paid
		$sql = "DELETE FROM reserves WHERE id='$rid' LIMIT 1";
		$query = database($connect, $sql);
		// Build display output
		$response = 'only needed for fail';
		$confirmedStatus = "true";
		// Return display output to ajax and exit
		echo "$confirmedStatus|$response";
		exit();
	}
}
//**** END register/buy seats

//**** START clear reservations
if (isset($_POST['clearRes'])){
	$rid = preg_replace('#[^0-9]#', '', $_POST['clearRes']);
	$clean = "SELECT r.*, a.avail
		  FROM reserves AS r
		  LEFT JOIN available AS a ON a.tablenum = r.tablenumber
		  WHERE r.id ='$rid'";
	$freequery = database($connect, $clean) or die (mysqli_error($connect));
	$num_check = mysqli_num_rows($freequery);
	if ($num_check != 0){
		foreach ($freequery as $row => MYSQLI_ASSOC) 
		{
			$dI = $row['tablenumber'];
			$dS = $row['numseats'];
			$dIdRow = $row['id'];
			$originalavail = $row['avail'];
			// Add back the expired reserves
			$updateAvailable = $originalavail + $dS;

			// Delete the reserves
			$sql3 = "DELETE FROM reserves WHERE tablenumber='$dI' LIMIT 1";
			$query3 = database($connect, $sql3);
			// Update the database with newly available seats
			$sql3 = "UPDATE available SET avail='$updateAvailable' WHERE tablenum='$dI' LIMIT 1";
			$query3 = database($connect, $sql3);
		}
	}
}
//**** END clear reservations
?>
