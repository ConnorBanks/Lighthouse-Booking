<?php
$SHOW_ECHOES = 'N';

if (!function_exists('table_data'))
{
	function table_data($table_id,$return_data)
	{
    	$result = database("SELECT * FROM tables_single WHERE table_id='$table_id' LIMIT 1");
    	return $result[$return_data];
	}
}

$dn = date('N',$rd);
$online_booking_data = database("SELECT * FROM onlineavailability WHERE meal='$ml' AND day='$dn' ORDER BY location");
$book = array();
if ($_SESSION['admin_access']) 
{
	$book['downstairs'] = true;
	$book['upstairs'] = true;
}
else
{
	foreach ($online_booking_data as $online_booking) 
	{
		$book[$online_booking['location']] = true;
	}
}

$online_day_booking_data = database("SELECT * FROM day_settings WHERE meal='$ml' AND `date`='$pd'");
if (count($online_day_booking_data) > 0)
{
	$day_book = array();
	if ($_SESSION['admin_access']) 
	{
		$day_book['downstairs'] = 'Y';
		$day_book['upstairs'] = 'Y';
	}
	else
	{
		foreach ($online_day_booking_data as $online_day_booking) 
		{
			if ($online_day_booking['availability_downstairs'] == 'Y') {$day_book['downstairs'] = 'Y';}
			if ($online_day_booking['availability_upstairs'] == 'Y') {$day_book['upstairs'] = 'Y';}
			//$day_book['downstairs'] = ($online_day_booking['availability_downstairs']=='Y'?true:false);
			//$day_book['upstairs'] = ($online_day_booking['availability_upstairs']=='Y'?true:false);
		}
	}
}
if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php') {echo '<pre>'.print_r($book,true).'<br />'.print_r($day_book,true).'<br />'.print_r($online_day_booking,true).'</pre>';}


$slots_default_covers_data = database("SELECT * FROM slots_default_covers ORDER BY slot_time");
$default_covers = array();
foreach ($slots_default_covers_data as $slots_default_cover) 
{
	$default_covers[$slots_default_cover['slot_time']] = $slots_default_cover['slot_covers'];
}

//echo '<pre>'.print_r($default_covers,true).'</pre>';
if (isset($b_id))
{
	$bookings_data = database("SELECT * FROM bookings WHERE party_date='$pd' AND meal='$ml' AND booking_id<>'$b_id'");
	$bookings_tables_data = database("SELECT bookings.booking_id, bookings_tables.table_id FROM bookings, bookings_tables WHERE bookings.party_date='$pd' AND bookings.meal='$ml' AND bookings.booking_id<>'$b_id' AND bookings.booking_id=bookings_tables.booking_id");
}
else
{
	$bookings_data = database("SELECT * FROM bookings WHERE party_date='$pd' AND meal='$ml'");
	$bookings_tables_data = database("SELECT bookings.booking_id, bookings_tables.table_id FROM bookings, bookings_tables WHERE bookings.party_date='$pd' AND bookings.meal='$ml' AND bookings.booking_id=bookings_tables.booking_id");
}

$bookings_tables = array();
foreach ($bookings_tables_data as $bookings_table) 
{
	$bookings_tables[$bookings_table['booking_id']][] = $bookings_table['table_id'];
}

$bookings = array();
foreach ($bookings_data as $booking) 
{
	$bookings[$booking['booking_id']] = $booking;
	$bookings[$booking['booking_id']]['start_time'] = $booking['party_time'];
	$bookings[$booking['booking_id']]['end_time'] = date('H:i:s',strtotime(date("H:i:s", strtotime($bookings[$booking['booking_id']]['start_time'])) . " +".$booking['duration']." mins"));
	if ($bookings[$booking['booking_id']]['end_time'] == '00:00:00')
	{
		$bookings[$booking['booking_id']]['end_time'] = '22:00:00';
	}
	$bookings[$booking['booking_id']]['tables'] = $bookings_tables[$booking['booking_id']];
}

if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php') {echo '<pre>'.print_r($bookings,true).'</pre>';}

$combinations_data = database("SELECT * FROM combinations WHERE bookingnumber='$ps' ORDER BY location, sortorder");
$table_combinations_data = database("SELECT * FROM tables_combinations ORDER BY combinationid, sortorder");

$table_combinations = array();
foreach ($table_combinations_data as $table_combination) 
{
	$table_id = $table_combination['tableid'];
	$result = database("SELECT * FROM tables_single WHERE table_id='$table_id'");
	if (count($result) > 0)
	{
		$table_combinations[$table_combination['combinationid']][] = $table_id;
	}
}

$combinations = array();
foreach ($combinations_data as $combination) 
{
	$add_combination = false;

	if (isset($day_book[$combination['location']]))
	{
		$add_combination = true;
	}
	/*elseif ($book[$combination['location']] == true)
	{
		$add_combination = true;
	}*/

	if ($add_combination == true && is_array($table_combinations[$combination['combinationid']]) == true && count($table_combinations[$combination['combinationid']]) > 0)
	{
		$combinations[$combination['location']][] = array_merge(
			$combination,
			array('tables' => $table_combinations[$combination['combinationid']])
		);
	}
}

//if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php') {echo '<pre>'.print_r($combinations,true).'</pre>';}

function slot_availability($time)
{
	GLOBAL $ps, $bookings, $default_duration, $default_covers;

	if (basename($_SERVER['PHP_SELF']) <> 'ajax.php')
	{
		$duration_result = database("SELECT * FROM slots_default_duration WHERE slot_time='$time' LIMIT 1");
		$duration = $duration_result['slot_duration'];
		if ($duration == '') {$duration = $default_duration;}
	}
	else
	{
		$duration = $default_duration;
	}

	$slot['start_time'] = $time.':00';
	$slot['end_time'] = date('H:i:s',strtotime(date("H:i:s", strtotime($slot['start_time'])) . " +".$duration." mins"));

	$max_covers = $default_covers[$time.':00'];

	$booked_tables = array(); $cur_party_size = 0;
	foreach ($bookings as $booking_id => $booking_data) 
	{
		if ($slot['start_time'] == $booking_data['start_time'])
		{
			$cur_party_size = $cur_party_size + $booking_data['party_size'];
		}

		if (check_times($slot,$booking_data))
		{
			if (is_array($booking_data['tables']) && count($booking_data['tables']))
			{
				foreach ($booking_data['tables'] as $table_id)
				{
					if (!in_array($table_id, $booked_tables)) 
					{
						$booked_tables[] = $table_id;
					}
				}
			}
		}
	}
  	
  	/*if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php')
  	{
		echo '<p>'.$time.': '.($cur_party_size + $ps).' : '.$max_covers.' : '.print_r($booked_tables,true).'</p>';
	}*/
	
	if (($cur_party_size + $ps) > $max_covers && count($bookings) > 0/*&& !isset($_SESSION['admin_access'])*/)
	{
		return 'N';
	}
	else
	{
		return (count(combi_availability($booked_tables))>0?'Y':'N');
	}
}

function combi_availability($booked_tables,$return='single')
{
	GLOBAL $book, $combinations;

	foreach ($book as $location => $active) 
	{
		if ($active == true && isset($combinations[$location]) && count($combinations[$location]) > 0)
		{
			foreach ($combinations[$location] as $combination_data)
			{
				$combi_booked = false;
				if (count($combination_data['tables']) > 0)
				{
					foreach ($combination_data['tables'] as $table_id) 
					{
						if (in_array($table_id, $booked_tables))
						{
							$combi_booked = true;
							break;
						}
					}
				}
				if ($combi_booked == false)
				{
					if ($return == 'single')
					{
						return $combination_data['tables'];
					}
					else
					{
						$combi_not_booked[] = $combination_data['tables'];
					}
				}
			}
		}
	}
	if ($return == 'single')
	{
		return array();
	}
	else
	{
		return (is_array($combi_not_booked)?$combi_not_booked:array());
	}
}

function get_available_combi()
{
	GLOBAL $tm, $bookings, $default_duration;

	if (basename($_SERVER['PHP_SELF']) <> 'ajax.php')
	{
		$duration_result = database("SELECT * FROM slots_default_duration WHERE slot_time='$tm' LIMIT 1");
		$duration = $duration_result['slot_duration'];
		if ($duration == '') {$duration = $default_duration;}
	}
	else
	{
		$duration = $default_duration;
	}

	$slot['start_time'] = $tm;
	$slot['end_time'] = date('H:i:s',strtotime(date("H:i:s", strtotime($slot['start_time'])) . " +".$duration." mins"));
	
	$booked_tables = array();
	foreach ($bookings as $booking_id => $booking_data) 
	{
		if (check_times($slot, $booking_data))
		{
			if (is_array($booking_data['tables']) && count($booking_data['tables']) > 0)
			{
				foreach ($booking_data['tables'] as $table_id)
				{
					if (!in_array($table_id, $booked_tables)) 
					{
						$booked_tables[] = $table_id;
					}
				}
			}
		}
	}	
	$tables = combi_availability($booked_tables);
	if (count($tables) > 0)
	{
		$table_numbers = array();
		foreach ($tables as $tableid) 
    	{
        	$table_numbers[] = table_data($tableid,'table_number');
    	}
    	return array(
    		'tables' => $tables, 
    		'table_numbers' => $table_numbers
    	);
	}
	else
	{
		if (isset($_SESSION['admin_access']))
		{
			echo '<h3 style="text-align: center;color: red;">There are no tables available for the '.date('H:i',strtotime($tm)).' time slot,<br />Booking will be shown as an unallocated booking</h3>';
		}
		else
		{
   			echo '<h3 style="text-align: center;color: red;">There are not enough tables available for the '.date('H:i',strtotime($tm)).' time slot,<br />Please go back and select another time</h3>';
   		}
   	}
	return array();
}

function get_available_combinations()
{
	GLOBAL $dropdown, $tm, $bookings, $default_duration, $b_id;

	if (basename($_SERVER['PHP_SELF']) <> 'ajax.php')
	{
		$duration_result = database("SELECT * FROM slots_default_duration WHERE slot_time='$tm' LIMIT 1");
		$duration = $duration_result['slot_duration'];
		if ($duration == '') {$duration = $default_duration;}
	}
	else
	{
		$duration = $default_duration;
	}

	$slot['start_time'] = $tm;
	$slot['end_time'] = date('H:i:s',strtotime(date("H:i:s", strtotime($slot['start_time'])) . " +".$duration." mins"));
	
	$booked_tables = array();
	foreach ($bookings as $booking_id => $booking_data) 
	{
		if (check_times($slot, $booking_data))
		{
			foreach ($booking_data['tables'] as $table_id)
			{
				if (!in_array($table_id, $booked_tables)) 
				{
					$booked_tables[] = $table_id;
				}
			}
		}
	}	
	if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php')
	{
		print_r($booked_tables);
	}
	$combinations_available = combi_availability($booked_tables,'multi');
	if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php')
	{
		print_r($combinations_available);
	}
	if (count($combinations_available) > 0)
	{
		$tables = array();
		$table_numbers = array();
		foreach ($combinations_available as $key => $combi_tables) 
		{
			$tables[$key] = $combi_tables;
			
			foreach ($combi_tables as $tableid) 
    		{
        		$table_numbers[$key][] = table_data($tableid,'table_number');
    		}
    	}
    	return array(
    		'tables' => $tables, 
    		'table_numbers' => $table_numbers
    	);
	}
	elseif (!$dropdown)
	{
		echo 'No Tables Available';
		return array();
	}
	else
	{
		return array();
	}
}

function check_times($slot, $booking_data)
{
	if (explode(':', $slot['end_time'])[0] == '00')
	{
		$slot['end_time'] = '22:00:00';
	}
	if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php') 
	{
		echo '<p>';
			echo 'SLOT: '.$slot['start_time'].' -> '.$slot['end_time']."\n";
			echo 'BOOKING '.$booking_data['booking_id'].': '.$booking_data['start_time'].' -> '.$booking_data['end_time']."\n";
		echo '</p>';
	}

	// SLOT TIMES \\
	$slot_times = array();
	$curtime = $slot['start_time'];
	while ($curtime <= $slot['end_time']) 
	{
		$slot_times[] = $curtime;
		$curtime = date('H:i:s',strtotime($curtime.' +5 mins'));
	}

	// BOOKING TIMES \\
	$booking_times = array();
	$curtime = $booking_data['start_time'];
	while ($curtime <= $booking_data['end_time']) 
	{
		$booking_times[] = $curtime;
		$curtime = date('H:i:s',strtotime($curtime.' +5 mins'));
		if ($curtime > max($slot_times))
		{
			break;
		}
	}

	if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php') 
	{
		echo '<p>';
			echo 'SLOT: '.print_r($slot_times,true)."\n";
			echo 'BOOKING '.print_r($booking_times,true)."\n";
		echo '</p>';
	}

	$booked = false;
	foreach ($slot_times as $time) 
	{
		if (in_array($time, $booking_times))
		{
			$booked = true;
			break;
		}
	}

	return $booked;

	/*
	if (
		(strtotime(date('Y-m-d').' '.$booking_data['start_time']) >= strtotime(date('Y-m-d').' '.$slot['start_time']) && strtotime(date('Y-m-d').' '.$booking_data['end_time']) <= strtotime(date('Y-m-d').' '.$slot['end_time']))
		OR
		(strtotime(date('Y-m-d').' '.$booking_data['start_time']) < strtotime(date('Y-m-d').' '.$slot['start_time']) && strtotime(date('Y-m-d').' '.$booking_data['end_time']) == strtotime(date('Y-m-d').' '.$slot['end_time']))
		OR
		(strtotime(date('Y-m-d').' '.$booking_data['start_time']) == strtotime(date('Y-m-d').' '.$slot['start_time']) && strtotime(date('Y-m-d').' '.$booking_data['end_time']) > strtotime(date('Y-m-d').' '.$slot['end_time']))
		OR
		(strtotime(date('Y-m-d').' '.$booking_data['start_time']) > strtotime(date('Y-m-d').' '.$slot['start_time']) && strtotime(date('Y-m-d').' '.$booking_data['start_time']) < strtotime(date('Y-m-d').' '.$slot['end_time']))
		OR 
		(strtotime(date('Y-m-d').' '.$booking_data['end_time']) < strtotime(date('Y-m-d').' '.$slot['end_time']) && strtotime(date('Y-m-d').' '.$booking_data['end_time']) > strtotime(date('Y-m-d').' '.$slot['start_time']))
	) {
		if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php') 
		{
			echo '<p>true</p>'."\n\n";
		}
		return true;
	} else {
		if ($SHOW_ECHOES == 'Y' && basename($_SERVER['PHP_SELF']) <> 'ajax.php') 
		{
			echo '<p>false</p>'."\n\n";
		}
		return false;
	}
	*/
}
?>