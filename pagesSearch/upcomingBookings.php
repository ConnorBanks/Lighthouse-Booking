<style type="text/css">
    thead th {
        padding: 10px;
    }
    td {
        padding: 0px 10px;
    }
    input[type="checkbox"] {
        box-shadow: none;
    }
    h3 {
        float: left;
    }
</style>
<?php
if (isset($_POST['notified']))
{
    $booking_id = $_POST['booking_id'];

    $sql = "UPDATE bookings SET 
        notified='true'
    WHERE booking_id='$booking_id'";
    database($sql);
}

// Upcoming 30 days \\
$upcoming30days = time() + (30 * 24 * 60 * 60);

if(isset($_POST['FromDate'], $_POST["ToDate"])){ //check if form was submitted
    $_SESSION['ToDate'] = $_POST['ToDate']; //POST input text
    $_SESSION['FromDate'] = $_POST['FromDate']; //POST input text
    $_SESSION['ToDate'] =  strtotime($_SESSION['ToDate']) + (30 * 24 * 60 * 60);
    $_SESSION['FromDate'] =  strtotime($_SESSION['FromDate']);
    $_SESSION['ToDate'] = date('Y-m-d', $_SESSION['ToDate']);
    $_SESSION['FromDate'] = date('Y-m-d', $_SESSION['FromDate']);
} else {
    $_SESSION['ToDate'] = date('Y-m-d', $upcoming30days);
    $_SESSION['FromDate'] = date('Y-m-d');
}

$date1 = $_SESSION['FromDate'];
$date2 = $_SESSION['ToDate'];

if(isset($_POST['Read'])) {
    if($_POST['Read'] == 'read') {
        $notified = 'true';
    } else {
        $notified = 'false';
    }
} else {
    $notified = 'false';
}

if(isset($_POST["FromDate"], $_POST["ToDate"])) {

    $date1 = $_SESSION['FromDate'];
    $date2 = $_SESSION['ToDate'];

    $date1_us_date = $_SESSION["FromDate"];
    $date1_parts = explode("-", $date1_us_date, 3);
    $date1_uk_date = $date1_parts[2] . "-" . $date1_parts[1] . "-" . $date1_parts[0]; // flip day and month?
    $date1_pd = date($date1_uk_date);

    $date2_us_date = $_SESSION["ToDate"];
    $date2_parts = explode("-", $date2_us_date, 3);
    $date2_uk_date = $date2_parts[2] . "-" . $date2_parts[1] . "-" . $date2_parts[0]; // flip day and month?
    $date2_pd = date($date2_uk_date);

    $query = "SELECT * FROM bookings WHERE notified = '$notified' AND party_date BETWEEN '$date1' AND '$date2' ORDER BY party_date";

} else {

    $query = "SELECT * FROM bookings WHERE notified = '$notified' AND party_date BETWEEN '$date1' AND '$date2' ORDER BY party_date";

}

//$conn = new PDO("mysql:host=localhost;dbname=lighthouse-booking","jack", "hack528618");

$results = database($query);

?>

    <!--<form method="POST" action="adminSearch.php#tabs-2">
        <div class="grid">
            <div class="col col-1-4">
                <label for="FromDate">From:</label>
                <input type="text" id="FromDate" name="FromDate" placeholder="From Date" <?php if(isset($_POST["FromDate"])) { echo "value='$date1_pd'"; } else { echo "value='$from_date'"; } ?> >
            </div>
            <div class="col col-1-4">
                <label for="ToDate">To:</label>
                <input type="text" id="ToDate" name="ToDate" placeholder="To Date"  <?php if(isset($_POST["ToDate"])) { echo "value='$date2_pd'"; } else { echo "value='$to_date'"; } ?> >
            </div>
            <div class="col col-1-4">
                <label for="Read">Un/Read:</label>
                <select id="Read" name="Read">
                    <option value="read" <?php if(isset($notified) && $notified == 'true') { echo "Selected=''"; } ?> >Read</option>
                    <option value="unread"  <?php if(isset($notified) && $notified == 'false') { echo "Selected=''"; } ?> >Unread</option>
                </select>
            </div>
            <div class="col col-1-4">
                <input type="submit" id="SubmitButton" name="SubmitButton" value="Search" class="text--white"></input>
            </div>
        </div>
    </form>-->

    <h3 class="text--bold">Upcoming Bookings (Next 30 Days)</h3>

    <?php

    //echo "<i>" . $query . "</i>";
    echo "<hr class='margin-bottom--large'>";
    echo "<div class='table-container'>";
    echo "
    <table>
        <thead>
            <tr>
                <th>Booking Details</th>
                <th>Guest Details</th>
                <th>Other</th>
                <th class='text-align--center' style='text-align:center!important;'>Read?</th>
            </tr>
        </thead>
        <tbody>"
    ;

    foreach ($results as $row) 
    {
        $dateParty =  strtotime($row['party_date']);
        $time = substr( $row['party_time'], 0, 5);
        echo "<tr>";
        echo "<td>" . date('jS M, Y', $dateParty) ."<br>" . $time ."<br>" . $row['party_size'] . " Guest" . ($row['party_size']>1?'s':'') . "</td>";
        echo "<td>" . $row['first_name'] . " " . $row['last_name'] ."<br>" . $row['email_address'] ."<br>" . $row['telephone'] . "</td>";
        echo "<td>" . $row['requests'] . "</td>";
        $id = $row['booking_id'];

        echo "<td class='text-align--center'>";
        echo "<form action='".$_SERVER['PHP_SELF']."?tab=0' method='POST'>";
        echo "<input type='hidden' name='booking_id' value='".$row['booking_id']."' />";
        echo "<input onChange='this.form.submit();' type='checkbox'";
        echo " name='notified' /></form>";
        echo "</td>";

        echo "</tr>";
    }

    echo "
        </tbody>
    </table>
    "
    ;
    echo "</div>";
    ?>
