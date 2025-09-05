    <div class="background--white max-width--1000 margin-auto--left margin-auto--right padding-bottom--normal padding-left--normal padding-right--normal">

        <?php
            $booked_tables = array();
            $results = database("SELECT * FROM bookings, bookings_tables WHERE bookings.party_date='$cur_date' AND bookings.booking_id=bookings_tables.booking_id ORDER BY bookings.party_time");
            if (count($results) == 0)
            {
                $booked_tables['lunch'] = array();
                $booked_tables['dinner'] = array();
            }
            else
            {
                $booked_tables['lunch'] = array();
                $booked_tables['dinner'] = array();
                foreach ($results as $result) 
                {
                    $booked_tables[strtolower($result['meal'])][] = $result['table_id'];
                }
            }
            //print_r($booked_tables);

            $availability = database("SELECT * FROM day_settings WHERE `date`='$cur_date' AND meal='".ucfirst($selected_meal)."' LIMIT 1");
        ?>

        <?php 
            /*if (count($availability) == 0) 
            {
        ?>
                <a href="adminDaySettings.php?date=<?php echo $cur_date; ?>&returnurl=admin.php"><button class="text--white padding-top--small padding-bottom--small" style="background: red;">Click here to add days settings</button></a>
        <?php 
            }*/
        ?>            

       <?php /*<div class="grid">
            <div class="col col-1-4 vertical-align--middle">
                <?php 
                    $allow_admin_booking = (count($availability) == 0?'Y':$availability['admin_booking']); 
                    if ($allow_admin_booking == 'Y')
                    {
                        ?>
                        <button id="add-booking" type="button" value="Add a Booking" class="text--white padding-top--small padding-bottom--small">Add a booking</button>
                        <?php 
                    } 
                ?>
            </div>
            <div class="col col-1-2" style="text-align: center;padding-left: 0px;padding-right: 10px;">
                <?php <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input style="text-align: center;" type="text" name="selected_date" id="AdminDate" onchange="this.form.submit();" placeholder="<?php echo date('l, j F Y',strtotime($cur_date)); ?>" />
                </form>  ?>
            </div>
            <div class="col col-1-4 margin-bottom--normal text-align--center vertical-align--middle">
                <?php <a href="admin.php" class="padding--small"><li class="icon icon--rows vertical-align--middle"></li></a>
                <a href="adminSchedule.php" class="padding--small"><li class="icon icon--rows-short vertical-align--middle"></li></a>  ?>
            </div>
        </div>
            */ ?>
        <div class="grid padding-top--normal">
            <div class="col col-1-4 vertical-align--middle">
                <div class="grid">
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
                <?php /*if ($selected_meal<>'lunch') { ?>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?selected_meal=lunch">
                    <button type="button" value="Previous" class="text--white padding-top--small padding-bottom--small">Lunch</button>
                </a> 
                <?php } 
                 if ($selected_meal<>'dinner') { ?>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?selected_meal=dinner">
                    <button type="button" value="Next" class="text--white padding-top--small padding-bottom--small">Dinner</button>
                </a>
                
                <?php } */ ?>
            </div>
            <div class="col col-1-4 text-align--center vertical-align--middle">
                <?php 
                    $allow_admin_booking = (count($availability) == 0?'Y':$availability['admin_booking']); 
                    if ($allow_admin_booking == 'Y')
                    {
                        ?>
                        <button id="add-booking" type="button" value="Add a Booking" class="text--white padding-top--small padding-bottom--small">Add a booking</button>
                        <?php 
                    } 
                ?>
            </div>
            <div class="col col-1-4 margin-bottom--normal text-align--center vertical-align--middle">
                <a href="admin.php" class="padding--small"><li class="icon icon--list-active vertical-align--middle"></li></a>
                <a href="adminSchedule.php" class="padding--small"><li class="icon icon--graph-inactive vertical-align--middle"></li></a> 
                <a href="printableBookings.php?cur_date=<?php echo $cur_date; ?>" target="_blank"><li class="icon icon--print vertical-align--middle"></li>
                    
                </a>   
            </div>
        </div>

    <?php /*    <div class="grid">
            <div class="col col-1-4">
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?prev_day">
                    <button type="button" value="Previous" class="text--white padding-top--small padding-bottom--small">< Previous</button>
                </a>
            </div>
            <div class="col col-1-2" style="text-align: center;padding-left: 0px;padding-right: 10px;">
                <h2 style="margin-bottom: 20px;">
                    <?php //echo date('l, j F Y',strtotime($cur_date)); ?>
                    <?php if ($cur_date <> $today) { ?>
                        <br /><span style="font-size: 12px;"><a style="color: #000!important;" href="<?php echo $_SERVER['PHP_SELF']; ?>?today">TODAY</a></span>
                    <?php }  ?>    
                </h2>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input style="text-align: center;" type="text" name="selected_date" id="AdminDate" onchange="this.form.submit();" placeholder="<?php echo date('l, j F Y',strtotime($cur_date)); ?>" />
                </form>
            </div>
            <div class="col col-1-4">
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?next_day">
                    <button type="button" value="Next" class="text--white padding-top--small padding-bottom--small">Next ></button>
                </a>
            </div>
        </div>

        <div class="grid" style="text-align: center;">
            <div class="col col-1-4">
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?selected_meal=lunch">
                    <button type="button" value="Previous" class="text--white padding-top--small padding-bottom--small" <?php if ($selected_meal<>'lunch') {echo 'style="background-color:#5c5c5c"';} ?>>Lunch</button>
                </a>
            </div>
            <div class="col col-1-4">
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?selected_meal=dinner">
                    <button type="button" value="Next" class="text--white padding-top--small padding-bottom--small" <?php if ($selected_meal<>'dinner') {echo 'style="background-color:#5c5c5c"';} ?>>Dinner</button>
                </a>
            </div>
            <div class="col col-1-4">
            <a href="printableBookings.php?cur_date=<?php echo $cur_date; ?>" target="_blank">
                    <button type="button" value="Next" class="text--white padding-top--small padding-bottom--small">Printable</button>
                </a>    </div>
            <div class="col col-1-4 col--vertical-top" style="padding-top: 5px;">
                 <a href="admin.php" class="padding--small"><li class="icon icon--rows vertical-align--middle"></li></a>
                <a href="adminSchedule.php" class="padding--small"><li class="icon icon--rows-short vertical-align--middle"></li></a>       
            </div>
        </div>
*/ ?>
        <h3 class="text--bold" style="display: inline-block;vertical-align: top;padding-top: 5px;padding-right: 10px;"><?php echo ucfirst($selected_meal); ?> Downstairs</h3>

        <?php 
            if (count($availability) > 0) 
            { 
        ?>
        <div style="display: inline-block;vertical-align: top;">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="hidden" name="admin_availability_downstairs" value="true" />
                <input type="hidden" name="date" value="<?php echo $cur_date; ?>" />
                <input type="hidden" name="meal" value="<?php echo $selected_meal; ?>" />
                <label class="switch vertical-align--middle">
                    <input onchange="this.form.submit();" type="checkbox" name="availability_downstairs" value="Y" <?php if ($availability['availability_downstairs'] == 'Y') {echo 'checked="checked"';} ?>>
                    <div class="slider"></div>
                </label>
                <span class="display--inline-block padding-left--small margin-bottom--small vertical-align--middle">Online Availability</span>
            </form>
        </div>
        <?php 
            }

        $query = "SELECT DISTINCT bookings.* FROM bookings WHERE party_date = '$cur_date' AND meal='$selected_meal' ORDER BY party_time, booking_id";
        $results = database($query);

        $bookings = array(); 
        $downstairs_unallocations = 0;
        $upstairs_unallocations = 0;
        foreach ($results as $result) 
        {
            $tables = array();
            $id = $result['booking_id'];
            $subresults = database("SELECT * FROM bookings_tables, tables_single WHERE bookings_tables.booking_id='$id' AND bookings_tables.table_id=tables_single.table_id");
            foreach ($subresults as $key => $subresult) 
            {
                $tables[] = $subresult['table_number'];
                if ($key == 0)
                {
                    $location = strtolower(trim($subresult['location']));
                }
            }
            if (count($tables) == 0)
            {
                $location = $result['location'];
                ${$location.'_unallocations'}=${$location.'_unallocations'}+$result['party_size'];
            }
            elseif ($location > '')
            {
                $bookings[$location][] = array_merge($result,array('tables' => $tables));
            }
        }
        //print_r($bookings);

        echo "
        <table class='margin-bottom--normal'>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Covers</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Comments</th>
                    <th>Table Numbers</th>
                    <th>Req</th>
                    <th>Edit</th>
                </tr>
            </thead>
        <tbody>"
        ;
        if (count($bookings['downstairs']))
        {
            $total_party_size = 0;
            foreach ($bookings['downstairs'] as $row)
            {
                $id = $row['booking_id'];
                $tables = $row['tables'];
                if (count($tables) > 0)
                {
                    echo "<tr>";
                    $time = substr( $row['party_time'], 0, 5);
                    echo "<td>" . $time . "</td>";
                    echo "<td>" . $row['party_size'] . "</td>";
                    echo "<td>" . $row['last_name'] . "</td>";
                    echo "<td>" . $row['telephone'] . "</td>";
                    echo "<td>" . $row['requests'] . "</td>";
                    echo "<td>";
                        echo implode(', ', $tables);
                    echo "</td>";
                    echo "<td>";
                        if($row['locked'] == 'Y') {echo '<img src="images/padlock.png"';}
                    echo "</td>";
                    echo "<td>" . "<a class='icon icon--small icon--edit' href='adminAmendBooking.php?booking_id=" . $id . "&returnurl=admin.php'>" . "</a>" . "</td>";
                    echo "</tr>";
                    $total_party_size = $total_party_size + $row['party_size'];
                }            
            }
            echo "<tr>";
            echo "<td>Total:</td>";
            echo "<td>" . $total_party_size . "</td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "</tr>";
        }

        echo "</tbody></table>";

        ?>
<div class="tables-list">
            <!--<div class="display--inline-block" style="width: 50px"><?php //echo ucfirst($selected_meal); ?>:</div>-->

            <?php

            $query = "SELECT * FROM tables_single WHERE location = 'downstairs' ORDER BY table_number";

            $results = database($query);

            foreach ($results as $row)
            {
                echo '<div class="display--inline-block width--auto margin-bottom--small">';
                echo '<label class="display--inline-block width--auto padding--tiny margin-left--small border border--tiny border-color--black text--gray" style="background-color:'.(in_array($row['table_id'], $booked_tables[$selected_meal])?'red':'white').'" for="table_' . $row['table_number'] . '">' . $row['table_number'] . '</label>';
                echo '</div>';
            }

            ?>

            <div class="display--inline-block text--white border--curved padding--small padding-left--normal padding-right--normal background--primary float--right" style="width: 160px;text-align: center;"><a href="adminSchedule.php?unallocate"><?php echo $downstairs_unallocations; ?> Unallocated</a></div> 
        </div> 
        <h3 class="text--bold" style="display: inline-block;vertical-align: top;padding-top: 5px;padding-right: 10px;"><?php echo ucfirst($selected_meal); ?> Upstairs</h3>

        <?php 
            if (count($availability) > 0) 
            { 
        ?>        
        <div style="display: inline-block;vertical-align: top;">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <input type="hidden" name="admin_availability_upstairs" value="true" />
                <input type="hidden" name="date" value="<?php echo $cur_date; ?>" />
                <input type="hidden" name="meal" value="<?php echo $selected_meal; ?>" />
                <label class="switch vertical-align--middle">
                    <input onchange="this.form.submit();" type="checkbox" name="availability_upstairs" value="Y" <?php if ($availability['availability_upstairs'] == 'Y') {echo 'checked="checked"';} ?>>
                    <div class="slider"></div>
                </label>
                <span class="display--inline-block padding-left--small margin-bottom--small vertical-align--middle">Online Availability</span>
            </form>        
        </div>
        <?php 
            }
        ?>

        

        <?php

        echo "
        <table class='margin-bottom--normal'>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Covers</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Comments</th>
                    <th>Table Numbers</th>
                    <th>Req</th>
                    <th>Edit</th>
                </tr>
            </thead>
        <tbody>"
        ;
        if (count($bookings['upstairs']))
        {
            $total_party_size = 0;
            foreach ($bookings['upstairs'] as $row)
            {
                $id = $row['booking_id'];
                $tables = $row['tables'];
                if (count($tables) > 0)
                {
                    echo "<tr>";
                    $time = substr( $row['party_time'], 0, 5);
                    echo "<td>" . $time . "</td>";
                    echo "<td>" . $row['party_size'] . "</td>";
                    echo "<td>" . $row['last_name'] . "</td>";
                    echo "<td>" . $row['telephone'] . "</td>";
                    echo "<td>" . $row['requests'] . "</td>";
                    echo "<td>";
                        echo implode(', ', $tables);
                    echo "</td>";
                    echo "<td>";
                    if($row['locked'] == 'Y') {echo '<img src="images/padlock.png"';}
                    echo "</td>";
                    echo "<td>" . "<a class='icon icon--small icon--edit' href='adminAmendBooking.php?booking_id=" . $id . "&returnurl=admin.php'>" . "</a>" . "</td>";
                    echo "</tr>";
                    $total_party_size = $total_party_size + $row['party_size'];
                }         
            }
            echo "<tr>";
            echo "<td>Total:</td>";
            echo "<td>" . $total_party_size . "</td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "<td></td>";
            echo "</tr>";
        }

        echo "</tbody></table>";

        ?>
        <div class="tables-list">
            <!--<div class="display--inline-block" style="width: 50px"><?php //echo ucfirst($selected_meal); ?>:</div>-->

            <?php

            $query = "SELECT * FROM tables_single WHERE location = 'upstairs' ORDER BY table_number";

            $results = database($query);

            foreach ($results as $row)
            {
                echo '<div class="display--inline-block width--auto margin-bottom--small">';
                echo '<label class="display--inline-block width--auto padding--tiny margin-left--small border border--tiny border-color--black text--gray" style="background-color:'.(in_array($row['table_id'], $booked_tables[$selected_meal])?'red':'white').'" for="table_' . $row['table_number'] . '">' . $row['table_number'] . '</label>';
                echo '</div>';
            }

            ?>

           <div class="display--inline-block text--white border--curved padding--small padding-left--normal padding-right--normal background--primary float--right" style="width: 160px;text-align: center;"><a href="adminSchedule.php?unallocate"><?php echo $upstairs_unallocations; ?> Unallocated</a></div> 
        </div>
    </div>

    <div id="add-form" class="padding--large background--grey text--white" title="Admin | New Booking">
        <iframe src="/index.php?admin_date=<?php echo $cur_date; ?>&admin_meal=<?php echo strtolower($selected_meal); ?>&admin" style="width: 100%;height: 550px;"></iframe>
    </div>