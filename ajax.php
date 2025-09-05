<?php session_start(); //print_r($_SESSION); ?>
<?php if ($_SESSION['loggedin'] == '' && $_SESSION['useraccountid'] == ''){
    exit();
} 
//ini_set('display_errors', '1');
include 'connect.php'; 
?>
<?php
switch($_REQUEST['request']) {
case 'updateSortorder':
	//$arrayTablesAllowed = array('tables_single','');
	$updateTable = $_REQUEST['update'];
	$identifyer = $_REQUEST['identifyer'];
	$identifyerID = $_REQUEST['identifyerID'];
	//if (in_array($updateTable, $arrayTablesAllowed)) {
		$counter = 1;
		foreach ($identifyerID as $recordIdentifyer) {
			//echo $counter.' - '.$recordIdentifyer."\n\n";
			$query = "UPDATE $updateTable SET sortorder = '" . $counter . "' WHERE $identifyer = '" . $recordIdentifyer."'";
			//echo $query;
			database($query);
			//mysql_query($query) or die('Error, insert query failed');
			$counter = $counter + 1;
		}
	/*}
	else {
		echo "Denied!";
	}*/
break;

case 'schedule':
	//print_r($_REQUEST);
	$cur_date = $_SESSION['cur_date'];
	$selected_meal = $_SESSION['selected_meal'];

	if ($selected_meal == 'lunch') {$min = 12;$max = 15;}
    else {$min = 17;$max = 22;}

    /*$result = database("SELECT open_time AS min, close_time AS max FROM day_settings WHERE `date`='$cur_date' AND meal='".ucfirst($selected_meal)."' LIMIT 1");
    $min = explode(':', $result['min'])[0];
    $max = explode(':', $result['max'])[0];
    if (explode(':', $result['max'])[1] > 0) {$max++;}*/

    $time = date("H:i:s", strtotime(date('Y')."-01-01 ".$min.":00:00" . " +".(15 * $_REQUEST['el_x'])." mins"));
    $duration = (15 * $_REQUEST['el_width']);
    $table_id = $_REQUEST['table_id'];
    $booking_id = $_REQUEST['booking_id'];

    if ($table_id > 0)
    {
    	$double_booked = false;
    	$bookings = database("SELECT * FROM bookings, bookings_tables WHERE bookings.party_date='$cur_date' AND bookings.booking_id=bookings_tables.booking_id AND bookings_tables.table_id='$table_id'");
    	if (count($bookings) > 0)
    	{
	    	foreach ($bookings as $booking) 
	    	{
	    		if (date("H:i:s", strtotime(date('Y')."-01-01 ".$time. " +".$duration." mins")) > $booking['party_time'])
	    		{
	    			$double_booked = true;
	    		}
	    		elseif ($time > $booking['party_time'] && $time < date("H:i:s", strtotime(date('Y')."-01-01 ".$booking['party_time']. " +".$booking['duration']." mins")))
	    		{
	    			$double_booked = true;	
	    		}
	    	}
	    }

    	if ($double_booked == false)
    	{
	    	$query = "UPDATE bookings SET 
		    	party_time='$time',
	    		duration='$duration' 
	    	WHERE booking_id='$booking_id'";
	    	database($query);

	    	$result = database("SELECT * FROM bookings_tables WHERE booking_id='$booking_id' LIMIT 1");
	    	$old_table_id = $result['table_id'];

	    	if ($table_id <> $old_table_id)
	    	{
				database("DELETE FROM bookings_tables WHERE booking_id='$booking_id'");
				database("INSERT INTO bookings_tables SET booking_id='$booking_id', table_id='$table_id'");
	    	}
	    }
    }
    else
    {
    	database("DELETE FROM bookings_tables WHERE booking_id='$booking_id'");
    }
break;

case 'check_tables':
	$dropdown = true;
	$cur_b_id = $_POST['booking_id'];
	$cur_tables = array();
    $subresults = database("SELECT * FROM bookings_tables, tables_single WHERE bookings_tables.booking_id='$cur_b_id' AND bookings_tables.table_id=tables_single.table_id");
    foreach ($subresults as $key => $subresult) 
    {
        $cur_tables[] = $subresult['table_number'];
    }

	$b_id = 0;
	$pd = date('Y-m-d',$rd = strtotime($_POST["date"]));
	$ml = $_POST["meal"];
	$tm = $_POST["time"].':00';
	$d = $_POST["duration"];
	$ps = $_POST["size"];

	$default_duration = $d;
	include ("check_availability.php");

	//echo slot_availability($tm);

    $get_available_combinations = get_available_combinations();
    //print_r($get_available_combinations);
    if (count($get_available_combinations['tables']) > 0)
    {
    	echo '<option value="">'.implode(', ',$cur_tables).'</option>';
    	echo '<option value="unallocate">Unallocate tables</option>';
	    echo '<option value="custom">Custom table(s)</option>';
    	foreach ($get_available_combinations['tables'] as $key => $table_id_combi) 
    	{
	    	echo '<option value="'.implode(',', $table_id_combi).'">'.implode(',', $get_available_combinations['table_numbers'][$key]).'</option>';
	    }
	}
	else
	{
		echo '<option value="">'.implode(', ',$cur_tables).'</option>';
    	echo '<option value="unallocate">Unallocate tables</option>';
	    echo '<option value="custom">Custom table(s)</option>';
	}
	break;

case 'check_tables_myaccount':
	$dropdown = true;
	$cur_b_id = $_POST['booking_id'];
	$cur_tables = array();
    $subresults = database("SELECT * FROM bookings_tables, tables_single WHERE bookings_tables.booking_id='$cur_b_id' AND bookings_tables.table_id=tables_single.table_id");
    foreach ($subresults as $key => $subresult) 
    {
        $cur_tables[] = $subresult['table_number'];
    }

	$b_id = 0;
	$pd = date('Y-m-d',$rd = strtotime($_POST["date"]));
	$ml = $_POST["meal"];
	$tm = $_POST["time"].':00';
	$d = $_POST["duration"];
	$ps = $_POST["size"];

	$default_duration = $d;
	include ("check_availability.php");

	//echo slot_availability($tm);

    $get_available_combinations = get_available_combinations();
    //print_r($get_available_combinations);
    if (is_array($get_available_combinations['tables']) && count($get_available_combinations['tables']) > 0)
    {
    	$cur_tables_str = implode(', ',$cur_tables);
    	$table_id_combi_str = implode(',', $get_available_combinations['tables'][0]);
    	echo $table_id_combi_str;
	}	
	break;

default:

}
?>