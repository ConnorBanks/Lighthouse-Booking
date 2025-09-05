<?php
require 'header.php';

$b_id = (isset($_GET['b_id'])?$_GET['b_id']:0);

$ps = 2;
$ml = 'Dinner';
$pd = date('Y-m-d',strtotime('2022-06-03'));
$tm = date('H:i:s',strtotime('21:30:00'));
$rd = strtotime($pd);

include ("check_availability.php");

$available_combi = get_available_combi();
extract($available_combi);

echo '<p>TableIDs: '.implode(', ', $tables).' | Table Numbers: '.implode(', ', $table_numbers).'</p>';
?>