<?php require 'headerAdmin.php'; ?>

    <?php require 'navigationAdmin.php'; ?>


    <?php

    if (!isset($_POST["StaffComments"])){
        $_POST["StaffComments"] = "";
    }

    $fn = $_POST["FirstName"];
    $ln = $_POST["LastName"];
    $em = $_POST["Email"];
    $te = $_POST["Telephone"];
    $rq = $_POST["SpecialRequests"];
    $sc = $_POST["StaffComments"];
    $sn = $_POST["StaffName"];

    if (!isset($_SESSION['PartySize'])){
    	$_SESSION['PartySize'] = "";
    }

    if (!isset($_SESSION['Meal'])){
    	$_SESSION['Meal'] = "";
    }

    if (!isset($_SESSION['PartyDate'])){
    	$_SESSION['PartyDate'] = "";
    }

    if (!isset($_SESSION['Time'])){
    	$_SESSION['Time'] = "";
    }

    $ps = $_SESSION["PartySize"];
    $ml = $_SESSION["Meal"];

    $us_date = $_SESSION["PartyDate"];
    $parts = explode("-", $us_date, 3);
    $uk_date = $parts[2] . "-" . $parts[1] . "-" . $parts[0]; // flip day and month?
    $pd = date($uk_date);

    $tm = $_SESSION['Time'];
    $rd = strtotime($pd);


    //$conn = new PDO("mysql:host=localhost;dbname=lighthouse-booking","jack", "hack528618");

    $results = database("SELECT * FROM tables_single WHERE minimum_covers <= $ps AND maximum_covers >= $ps ORDER BY maximum_covers ASC LIMIT 1");

    $count = 0;

    foreach ($results as $row) 
    {
        echo "Table Number : " . $row['table_number'] . " | ";
        echo "Table ID : " . $row['table_id'] . " | ";
        echo "Min Size : " . $row['minimum_covers'] . " | ";
        echo "Max Size : " . $row['maximum_covers'] . " | ";

        $_SESSION['TableID'] = $row['table_id'];
    }

    if (!isset($_SESSION['TableID'])){
        $_SESSION['TableID'] = "";
    }

    $tid = $_SESSION['TableID'];

    //$conn = new PDO("mysql:host=localhost;dbname=lighthouse-booking","jack", "hack528618");

    if ($ps != "" && $ml != "" && $pd != "" && $tm != "" && $fn != "" && $ln != "" && $te != "" && $em != "" && $tid != "") {
    	database("INSERT INTO `bookings`(`first_name`, `last_name`, `email_address`, `telephone`, `requests`, `party_size`, `meal`, `table_id`, `party_date`, `party_time`, `notified`) VALUES ('$fn','$ln','$em','$te','$rq','$ps','$ml','$tid','$pd','$tm','false')");
    	$message = "Thank you for booking, we look forward to seeing you on the " . date("jS F Y", $rd) . " for " . $ps . " guests at " . $tm . ".";
    }
    else if ($ps == "" || $ml == "" || $pd == "" || $tm == "" || $fn == "" || $ln == "" || $te == "" || $em == "" || $tid == "") {
    	$message = "Error Booking.";
        echo "<p>PartySize: " . $ps . "</p>";
        echo "<p>Meal: " . $ml . "</p>";
        echo "<p>PartySize: " . $pd . "</p>";
        echo "<p>Time: " . $tm . "</p>";
        echo "<p>FirstName: " . $fn . "</p>";
        echo "<p>LastName: " . $ln . "</p>";
        echo "<p>Telephone: " . $te . "</p>";
        echo "<p>Email: " . $em . "</p>";
        echo "<p>Table ID: " . $tid . "</p>";
        echo "<p>StaffComments: " . $sc . "</p>";
        echo "<p>SpecialRequests: " . $rq . "</p>";
        echo "<p>StaffName: " . $sn . "</p>";
    } ?>

    <div class="background--white max-width--1000 margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--normal padding-right--normal">

    		<p class="position--relative margin-bottom--normal text-align--center padding-right--large padding-left--large">
    			<a class='icon icon--small icon--return-grey position--absolute position--top position--left' href='/admin.php'></a>

    			<?php

    			if ($_SESSION['PartySize'] != "" && $_SESSION['Meal'] != "" && $_SESSION['PartyDate'] != "" && $_SESSION['Time'] != ""){
    				echo date("jS F Y", $rd) . " | " . $ps . " Guests | " . $ml . " | " . $tm;

    				// Add AM or PM depending on the first 2 values of the time string
    				if (substr($tm, 0, 2) > 11) {
    					echo "pm";
    				} else {
    					echo "am";
    				}

    			} else {
    				echo "Your request couldn't be processed. We apologise for any inconvenience.";
    			}

    			?>

    			<a class='icon icon--small icon--cancel-grey position--absolute position--top position--right' href='/admin.php'></a>
    		</p>

    		<hr class="margin-bottom--large">

    		<p class="padding-left--large padding-right--large"><?php echo $message; ?></p>

    </div>

    <?php

    // remove all session variables
    session_unset();

    // destroy the session
    session_destroy();

    ?>

<?php require 'footer.php'; ?>
