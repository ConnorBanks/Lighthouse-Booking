<?php require 'headerAdmin.php'; ?>

    <?php require 'navigationAdmin.php'; ?>

<link rel="stylesheet" href="styles/jquery-ui.theme.min.css" />
<link rel="stylesheet" href="styles/gridstack_<?php echo $selected_meal; ?>.css" />
<link rel="stylesheet" href="styles/gridstack_custom.css" />
<script src="js/lodash.js"></script>
<script src="js/gridstack.js"></script>
<script src="js/gridstack.jQueryUI.js"></script>
<style type="text/css">
    tr,
    tr:hover {
        background-color: transparent!important;
    }
</style>

        <div class="grid padding-top--normal max-width--1000 margin-auto--left margin-auto--right">
            <div class="col col-1-4 vertical-align--middle">
                <div class="grid day-next-prev-container">
                    <div class="col col-1-4 vertical-align--middle">
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?prev_day">
                            <button type="button" value="Previous" class="text--white padding-top--small padding-bottom--small"><</button>
                        </a>
                    </div>
                    <div class="col col-1-2 vertical-align--middle" style="text-align: center;padding-left: 0px;padding-right: 0px;">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                            <input style="text-align: center;" type="text" name="selected_date" id="AdminDate" onchange="this.form.submit();" value="<?php echo date('d/m/Y',strtotime($cur_date)); ?>" />
                        </form>
                    </div>
                    <div class="col col-1-4 vertical-align--middle" style="padding-left: 10px;">
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?next_day">
                            <button type="button" value="Next" class="text--white padding-top--small padding-bottom--small">></button>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col col-1-4 vertical-align--middle">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
                    <select name="selected_meal" onChange="this.form.submit();" style="text-align: center;">
                        <option value="lunch" <?php if ($selected_meal=='lunch') {echo 'selected="selected"';} ?>>Lunch</option>
                        <option value="dinner" <?php if ($selected_meal=='dinner') {echo 'selected="selected"';} ?>>Dinner</option>
                    </select>
                </form>            
            </div>
            <div class="col col-1-4 text-align--center vertical-align--middle">
                <?php 
$allow_admin_booking = ((is_array($availability) || $availability instanceof Countable ? count($availability): 0) == 0?'Y':$availability['admin_booking']);                    if ($allow_admin_booking == 'Y')
                    {
                        ?>
                        <button id="add-booking" type="button" value="Add a Booking" class="text--white padding-top--small padding-bottom--small">Add a booking</button>
                        <?php 
                    } 
                ?>
            </div>
            <div class="col col-1-4 margin-bottom--normal text-align--center vertical-align--middle">
                <a href="admin.php" class="padding--small"><li class="icon icon--list-inactive vertical-align--middle"></li></a>
                <a href="adminSchedule.php" class="padding--small"><li class="icon icon--graph-active vertical-align--middle"></li></a> 
                <!--<a href="printableBookings.php?cur_date=<?php echo $cur_date; ?>" target="_blank"><li class="icon icon--print vertical-align--middle"></li></a>-->
            </div>
        </div>
        <?php
            if ($selected_meal == 'lunch') {$min = 12;$max = 15;}
            else {$min = 17;$max = 22;}

            /*$result = database("SELECT open_time AS min, close_time AS max FROM day_settings WHERE `date`='$cur_date' AND meal='".ucfirst($selected_meal)."' LIMIT 1");
            $min = explode(':', $result['min'])[0];
            $max = explode(':', $result['max'])[0];
            if (explode(':', $result['max'])[1] > 0) {$max++;}*/

            $max_cols = ($max - $min) * 4;

            $start = $min.':00:00';
            $end = $max.':00:00';

            $db_bookings = array();
            $tables = database("SELECT * FROM tables_single ORDER BY table_number");
            foreach ($tables as $table) 
            {
                $double_booked = false; 
                $table_id = $table['table_id'];

                $bookings = database("SELECT * FROM bookings, bookings_tables WHERE bookings.party_date='$cur_date' AND bookings.booking_id=bookings_tables.booking_id AND bookings_tables.table_id='$table_id' AND bookings.party_time BETWEEN '$start' AND '$end' ORDER BY bookings.party_time");
                if (count($bookings) > 0)
                {
                    foreach ($bookings as $key => $booking) 
                    {
                        if ($key > 0)
                        {
                            if (date("H:i:s", strtotime(date('Y')."-01-01 ".$time. " +".$duration." mins")) > $booking['party_time'])
                            {
                                $double_booked = true;
                                $db_bookings[] = $booking['booking_id'];
                            }
                            elseif ($time > $booking['party_time'] && $time < date("H:i:s", strtotime(date('Y')."-01-01 ".$booking['party_time']. " +".$booking['duration']." mins")))
                            {
                                $double_booked = true;  
                                $db_bookings[] = $booking['booking_id'];
                            }

                        }
                        $time = $booking['party_time'];
                        $duration = $booking['duration'];
                    }
                }
            }
            if (count($db_bookings) > 0)
            {
                ?>
                <div style="background-color: red;color: #fff;padding: 5px;text-align: center;">
                    <h2 style="margin: 0px;">Double booking detected for bookings:</h2><br />
                    <p><?php echo implode(' | ', $db_bookings); ?></p>
                </div>
                <br />
                <?php 
            }

            $bookings = array();

            $bookings_data = database("SELECT * FROM bookings WHERE party_date='$cur_date' AND party_time BETWEEN '$start' AND '$end'");
            foreach ($bookings_data as $booking) 
            {
                $bookings[$booking['booking_id']] = $booking;
            }
            $booking_numbers = array_keys($bookings);

            //echo '<pre>'.print_r($bookings,true).'</pre>';
            //echo '<pre>'.print_r($booking_numbers,true).'</pre>';

            $bookings_tables = array(); $table_counts = array();

            foreach ($booking_numbers as $booking_number) 
            {
                $bookings_tables_data = database("SELECT bookings_tables.* FROM bookings_tables, tables_single WHERE bookings_tables.table_id=tables_single.table_id AND bookings_tables.booking_id='$booking_number'");    
                if (count($bookings_tables_data) > 0)
                {
                    foreach ($bookings_tables_data as $key => $booking_table) 
                    {
                        $booking_tables[$booking_table['table_id']][] = $booking_table['booking_id'];
                        $table_counts[$booking_table['booking_id']][] = $booking_table['table_id'];

                        unset($booking_numbers[array_search($booking_table['booking_id'], $booking_numbers)]);
                    }
                }
            }

            //echo '<pre>'.print_r($booking_tables,true).'</pre>';
            //echo '<pre>'.print_r($table_counts,true).'</pre>';

            //echo '<pre>'.print_r($booking_numbers,true).'</pre>';
            $unallocated_booking_numbers = $booking_numbers;
            if (count($unallocated_booking_numbers) > 0)
            {
                foreach ($unallocated_booking_numbers as $booking_number) 
                {
                    $unallocated_bookings[$booking_number] = $bookings[$booking_number];
                }
            }
            
            $tables['Downstairs'] = database("SELECT * FROM tables_single WHERE location='downstairs' ORDER BY table_number");
            $tables['Upstairs'] = database("SELECT * FROM tables_single WHERE location='upstairs' ORDER BY table_number");

            $results['Downstairs'] = array();
            $results['Upstairs'] = array();

            foreach ($tables['Downstairs'] as $key => $result)
            {
                $results['Downstairs'][$key] = $result;
if ((is_array($booking_tables[$result['table_id']]) || $booking_tables[$result['table_id']] instanceof Countable ? count($booking_tables[$result['table_id']]): 0) > 0)                {
                    foreach ($booking_tables[$result['table_id']] as $booking_id) 
                    {
                        $results['Downstairs'][$key]['bookings'][] = array_merge($bookings[$booking_id],array('table_num' => count($table_counts[$booking_id])));
                    }
                }
            }

            foreach ($tables['Upstairs'] as $key => $result)
            {
                $results['Upstairs'][$key] = $result;
if ((is_array($booking_tables[$result['table_id']]) || $booking_tables[$result['table_id']] instanceof Countable ? count($booking_tables[$result['table_id']]): 0) > 0)                {
                    foreach ($booking_tables[$result['table_id']] as $booking_id) 
                    {
                        $results['Upstairs'][$key]['bookings'][] = array_merge($bookings[$booking_id],array('table_num' => count($table_counts[$booking_id])));
                    }
                }
            }

            //echo '<pre>'.print_r($results,true).'</pre>';
        ?>
        <div class="background--white margin-auto--left margin-auto--right padding-bottom--normal padding-left--tiny padding-right--tiny">
            <button id="toggle_unallocated_bookings" class="text--white padding-top--small padding-bottom--small" style="width:224px;<?php if (!isset($_GET['unallocate'])) { echo 'background:#aaa;'; } else { echo 'background:#faae18;'; } ?>"><?php echo (is_array($unallocated_bookings) || $unallocated_bookings instanceof Countable ? count($unallocated_bookings): 0); ?> Unallocated Bookings</button>
        </div>
        <div id="unallocated_bookings" class="unallo-left-box background--white margin-auto--left margin-auto--right padding-bottom--normal padding-left--tiny padding-right--tiny" style="bottom:auto!important;width: 24%;vertical-align: top;<?php if (!isset($_GET['unallocate'])) { ?>display: none;<?php } else { ?>display:inline-block;<?php } ?>">
            <h3><b>Unallocated Bookings:</b></h3>
            <div id="grid-stack-unallocated" class="grid-stack" data-gs-width="10" data-table_id="0">
            <?php 
                if ((is_array($unallocated_bookings) || $unallocated_bookings instanceof Countable ? count($unallocated_bookings): 0) == 0)
                {
                    ?>
                    <div class="grid-stack-item" id="nobookings" 
                        data-gs-x="0" data-gs-y="0"
                        data-gs-width="10" data-gs-height="1"
                        data-gs-no-resize="1" data-gs-no-move="1">
                            <div class="grid-stack-item-content">No Bookings</div>
                    </div>
                    <?php
                }
                else
                {
                    foreach ($unallocated_bookings as $booking_id => $booking) 
                    {
                        ?>
                        <div class="grid-stack-item grid-stack-single-item"
                            data-gs-x="0" data-gs-y="0"
                            data-gs-width="10" data-gs-height="1"
                            data-booking_id="<?php echo $booking['booking_id']; ?>"
                        >
                                <div class="grid-stack-item-content">
                                    <a href="adminAmendBooking.php?booking_id=<?php echo $booking['booking_id']; ?>&returnurl=adminSchedule.php">
                                    ID: <?php echo $booking['booking_id'].' | '.$booking['last_name']; ?> | Table for <?php echo $booking['party_size']; ?>
                                    </a>
                                </div>
                        </div>
                        <?php
                    }
                    
                }
            ?>
            </div>
        </div>
        <div id="booking_tables" class="background--white margin-auto--left margin-auto--right padding-bottom--normal padding-left--tiny" style="width:<?php if (!isset($_GET['unallocate'])) {echo '100';} else {echo '75';} ?>%;display: inline-block;">
            <table class="max-width--100" cellpadding="0" cellpadding="0">
                <thead>
                    <tr>
                        <th>Table</th>
                        <th>
                            <div class="grid-stack grid-stack-default grid-stack-header" data-gs-width="<?php echo $max_cols; ?>" data-gs-height="1" data-gs-current-height="1">
                                <div class="grid-stack-item"
                                    data-gs-x="0" data-gs-y="0"
                                    data-gs-width="1" data-gs-height="1"
                                    data-gs-no-resize="1" data-gs-no-move="1">
                                        <div class="grid-stack-item-content"><?php echo $min.':00'; ?></div>
                                </div>
                                <?php
                                $timetoprint = ($min).':30'; $x = 1;
                                while($timetoprint <= $max.':00')
                                {
                                    ?>
                                    <div class="grid-stack-item"
                                        data-gs-x="<?php echo $x; ?>" data-gs-y="0"
                                        data-gs-width="<?php echo ($x<=($max_cols-3)?2:1); ?>" data-gs-height="1"
                                        data-gs-no-resize="1" data-gs-no-move="1">
                                            <div class="grid-stack-item-content"><?php echo $timetoprint; ?></div>
                                    </div>    
                                    <?php
                                    $timetoprint = date("H:i",strtotime(date("H:i", strtotime($timetoprint)) . " +30 mins"));
                                    $x = $x + 2;
                                }
                                ?>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($results as $location => $tables) 
                    {
                        /*
                        ?>
                        <tr>
                            <td colspan="2"><h3 class="text--bold" style="text-align: center;margin-bottom: 0px;"><?php echo $location; ?></h3></td>
                        </tr>
                        <?php
                        */
                        foreach ($tables as $table_data) 
                        {
                            ?>
                            <tr>
                                <td width="5%" style="text-align: center;"><?php echo $table_data['table_number']; ?></td>
                                <td width="95%" class="bg-pattern-<?php echo $selected_meal; ?>">
                                    <div class="grid-stack grid-stack-default grid-stack-animate" data-gs-width="<?php echo $max_cols; ?>" data-gs-height="1" data-gs-current-height="1" data-table_id="<?php echo $table_data['table_id']; ?>">
                                        <?php
if ((is_array($table_data['bookings']) || $table_data['bookings'] instanceof Countable ? count($table_data['bookings']): 0) > 0)                                            {
                                                foreach ($table_data['bookings'] as $booking) 
                                                {
                                                    if (isset($booking['booking_id']))
                                                    {
                                                        $to_time = strtotime(date('Y')."-01-01 ".$booking['party_time']);
                                                        $from_time = strtotime(date('Y')."-01-01 ".$min.":00:00");
                                                        $time_from_min = round(abs($to_time - $from_time) / 60,2);
                                                        $x = $time_from_min / 15;
                                                        $width = $booking['duration'] / 15;
                                                        if (($max_cols - $x) < $width) {$width = ($max_cols - $x);}

                                                        $table_num = count(database("SELECT * FROM bookings_tables WHERE booking_id='".$booking['booking_id']."'"));
                                                        ?>
                                                        <div class="grid-stack-item <?php echo ($table_num==1?'grid-stack-single-item':''); ?>"
                                                            data-gs-x="<?php echo $x; ?>" 
                                                            data-gs-y="0"
                                                            data-gs-width="<?php echo $width; ?>" 
                                                            data-gs-height="1"
                                                            data-gs-locked="<?php echo ($booking['locked']=='Y'?1:0); ?>" 
                                                            data-gs-no-resize="<?php echo ($booking['locked']=='Y'||$table_num > 1?1:0); ?>" 
                                                            data-gs-no-move="<?php echo ($booking['locked']=='Y'||$table_num > 1?1:0); ?>"
                                                            data-booking_id="<?php echo $booking['booking_id']; ?>"
                                                        >
                                                            <div class="grid-stack-item-content">
                                                                <a href="adminAmendBooking.php?booking_id=<?php echo $booking['booking_id']; ?>&returnurl=adminSchedule.php">
                                                                <span>ID: <?php echo $booking['booking_id'].' | '.$booking['last_name']; ?> | Table for <?php echo $booking['party_size']; ?></span>
                                                                <?php if($table_num > 1) {echo '<img class="linkedtable" src="images/link.png" />';} ?>
                                                                <?php if($booking['locked'] == 'Y') {echo '<img class="padlock" src="images/padlock.png" />';} ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            }
                                        ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>      
            </table>
        </div>
    
     <script type="text/javascript">
        $('#toggle_unallocated_bookings').on('click', function(event) {
            if ($('#unallocated_bookings').css('display') == 'none')
            {
                $(this).css('background-color', '#faae18');
                $('#unallocated_bookings').css('display', 'inline-block');
                $('#booking_tables').css('width', '75%');
                $('#booking_tables').css('margin-left', '25%');
            }
            else
            {
                $(this).css('background-color', '#aaa');
                $('#unallocated_bookings').hide();
                $('#booking_tables').css('width', '100%');
                $('#booking_tables').css('margin-left', '0%');
            }
        });
    </script>

    <script src="js/gridstack.custom.js"></script>

    <?php require 'footer.php'; ?>

    <div id="add-form" class="padding--large background--grey text--white" title="Admin | New Booking">
        <iframe src="/index.php?admin_date=<?php echo $cur_date; ?>&admin_meal=<?php echo strtolower($selected_meal); ?>&admin" style="width: 100%;height: 550px;"></iframe>
    </div>