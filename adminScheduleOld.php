<?php require 'headerAdmin.php'; ?>

    <?php require 'navigationAdmin.php'; ?>

    <style type="text/css">
        .calendar__row {
            cursor: none!important;
            width: 100px!important;
            height: auto!important;
        }
        .calendar__table-row {
            min-width: 100px;
            text-align: center;
        }
        .ui-resizable-e, .ui-resizable-w {
            display: none!important;
        }
    </style>

    <?php /*<div class="background--white max-width--1000 margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--tiny padding-right--tiny">

        <div class="grid">
            <div class="col col-1-4 vertical-align--middle">
                <button id="add-booking" type="button" value="Add a Booking" class="text--white padding-top--small padding-bottom--small">Add a booking</button>
            </div>
            <div class="col col-1-2" style="text-align: center;padding-left: 0px;padding-right: 10px;">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input style="text-align: center;" type="text" name="selected_date" id="AdminDate" onchange="this.form.submit();" placeholder="Other dates" />
                </form>
            </div>
            <div class="col col-1-4 margin-bottom--normal text-align--center vertical-align--middle">
                <a href="admin.php" class="padding--small"><li class="icon icon--rows vertical-align--middle"></li></a>
                <a href="adminSchedule.php" class="padding--small"><li class="icon icon--rows-short vertical-align--middle"></li></a>
            </div>
        </div>
        */ ?>


        <div class="grid padding-top--normal max-width--1000 margin-auto--left margin-auto--right">
            <div class="col col-1-4 vertical-align--middle">
                <div class="grid">
                    <div class="col col-1-4 vertical-align--middle">
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>?prev_day">
                            <button type="button" value="Previous" class="text--white padding-top--small padding-bottom--small"><</button>
                        </a>
                    </div>
                    <div class="col col-1-2 vertical-align--middle" style="text-align: center;padding-left: 0px;padding-right: 0px;">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                            <input style="text-align: center;" type="text" name="selected_date" id="AdminDate" onchange="this.form.submit();" placeholder="<?php echo date('d/m/Y',strtotime($cur_date)); ?>" />
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
                <?php }*/ ?>
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
                <a href="admin.php" class="padding--small"><li class="icon icon--list-inactive vertical-align--middle"></li></a>
                <a href="adminSchedule.php" class="padding--small"><li class="icon icon--graph-active vertical-align--middle"></li></a> 
                <a href="printableBookings.php?cur_date=<?php echo $cur_date; ?>" target="_blank"><li class="icon icon--print vertical-align--middle"></li></a>   
            </div>
        </div>

<?php /*
        <div class="grid max-width--1000 margin-auto--left margin-auto--right">
            <div class="col col-1-4">
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?prev_day">
                    <button type="button" value="Previous" class="text--white padding-top--small padding-bottom--small">< Previous</button>
                </a>
            </div>
            <div class="col col-1-2" style="text-align: center;padding-left: 0px;padding-right: 10px;">
                <h2 style="margin-bottom: 20px;">
                    <?php echo date('l, d F Y',strtotime($cur_date)); ?>
                    <?php if ($cur_date <> $today) { ?>
                        <br /><span style="font-size: 12px;"><a style="color: #000!important;" href="<?php echo $_SERVER['PHP_SELF']; ?>?today">TODAY</a></span>
                    <?php } ?>    
                </h2>                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <input style="text-align: center;" type="text" name="selected_date" id="AdminDate" onchange="this.form.submit();" placeholder="<?php echo date('l, d F Y',strtotime($cur_date)); ?>" />
                </form>

            </div>
            <div class="col col-1-4">
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>?next_day">
                    <button type="button" value="Next" class="text--white padding-top--small padding-bottom--small">Next ></button>
                </a>
            </div>
        </div>

        <div class="grid max-width--1000 margin-auto--left margin-auto--right" style="text-align: center;">
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
            <div class="col col-1-4"><a href="printableBookings.php?cur_date=<?php echo $cur_date; ?>" target="_blank">
                    <button type="button" value="Next" class="text--white padding-top--small padding-bottom--small">Printable</button>
                </a>     </div>
            <div class="col col-1-4 col--vertical-top" style="padding-top: 5px;">
                    <a href="admin.php" class="padding--small"><li class="icon icon--rows vertical-align--middle"></li></a>
                <a href="adminSchedule.php" class="padding--small"><li class="icon icon--rows-short vertical-align--middle"></li></a>
            </div>
        </div>
    </div> */ ?>
    <div class="background--white margin-auto--left margin-auto--right padding-bottom--normal padding-left--tiny padding-right--tiny">
        <?php
            if ($selected_meal == 'lunch') {$min = 12;$max = 15;}
            else {$min = 18;$max = 22;}

            $results['Downstairs'] = database("SELECT * FROM tables_single WHERE location='downstairs'");
            foreach ($results['Downstairs'] as $key => $result)
            {
                $hour = $min;
                while($hour < $max)
                {
                    for ($i=0; $i < 4; $i++) 
                    { 
                        $timetoprint = date('H:i',mktime($hour,(15 * $i),0,1,1,2017));
                        $results['Downstairs'][$key]['Bookings'][$timetoprint] = database("SELECT * FROM bookings, bookings_tables WHERE bookings.party_date='$cur_date' AND bookings.booking_id=bookings_tables.booking_id AND bookings_tables.table_id='".$result['table_id']."' AND bookings.party_time='".$timetoprint.":00' LIMIT 1");
                    }
                    $hour++;
                }
            }

            $results['Upstairs'] = database("SELECT * FROM tables_single WHERE location='upstairs'");
            foreach ($results['Upstairs'] as $key => $result)
            {
                $hour = $min;
                while($hour < $max)
                {
                    for ($i=0; $i < 4; $i++) 
                    { 
                        $timetoprint = date('H:i',mktime($hour,(15 * $i),0,1,1,2017));
                        $results['Upstairs'][$key]['Bookings'][$timetoprint] = database("SELECT * FROM bookings, bookings_tables WHERE bookings.party_date='$cur_date' AND bookings.booking_id=bookings_tables.booking_id AND bookings_tables.table_id='".$result['table_id']."' AND bookings.party_time='".$timetoprint.":00' LIMIT 1");
                    }
                    $hour++;
                }
            }

            //echo '<pre>'.print_r($results,true).'</pre>';
            //echo '<pre>'.print_r($results['Downstairs'],true).'</pre>';
            //echo '<pre>'.print_r($results['Upstairs'],true).'</pre>';

            foreach ($results as $location => $tables) 
            {
                ?>
                <h3 class="text--bold"><?php echo $location; ?></h3>
                <div class="calendar-container margin-bottom--large" style="overflow-x: none;">
                    <table class="max-width--100" cellpadding="0" cellpadding="0">
                        <colgroup>
                            <col span="1" class="border-right border-right--small border-color--black-fade">
                            <?php
                            $hour = $min;
                            while($hour++ < $max)
                            {
                                echo '<col span="4" class="border-right border-right--small border-color--black-fade">';
                            }
                            ?>
                        </colgroup>
                        <tr>
                            <th class="padding-left--normal padding-right--normal">Table</th>
                            <?php
                            $hour = $min;
                            while($hour++ < $max)
                            {
                                $timetoprint = date('H:i',mktime($hour,0,0,1,1,2017));
                                echo '<th colspan="4" style="text-align:right;">' . $timetoprint . '</th>';
                            }
                            ?>
                        </tr>
                        <?php
                        foreach ($tables as $table_data) 
                        {
                            echo '<tr class="">';
                                echo '<td class="min-width--large text-align--center padding-top--tiny padding-bottom--tiny">' . $table_data['table_number'] . '</td>';
                                foreach ($table_data['Bookings'] as $timetoprint => $booking) 
                                {
                                    echo '<td class="calendar__table-row" style="min-width:50px;padding:0px;" colspan="2">';
                                        //echo $timetoprint;
                                        if (isset($booking['booking_id']))
                                        {
                                            echo '<div class="box-sizing--content-box display--inline-block padding--small padding-left--normal padding-right--normal background--primary" style="width:6vw;font-size:1vw;padding:5px 15px;margin: 5px 0px;">';
                                                echo '<a href="adminAmendBooking.php?booking_id='.$booking['booking_id'].'&returnurl=adminSchedule.php">';
                                                    echo '<span class="calendar__info text--white">ID: '.$booking['booking_id'].' | '.$booking['last_name'].'<br />Table for '.$booking['party_size'].'</span>';
                                                echo '</a>';
                                            echo '</div>';
                                        }
                                    echo '</td>';
                                }
                            echo '</tr>';
                        }
                        ?>
                    </table>
                </div>
                <br />
                <?php
            }
            ?>
<?php /*
        <h3 class="text--bold">Upstairs</h3>
        <div class="calendar-container margin-bottom--normal">
            <table class="calendar max-width--100">
                <colgroup>
                    <col span="1">
                    <?php
                    $hour = 8;
                    while($hour++ < $max)
                    {
                        echo '<col span="1" class="border-right border-right--small border-color--black-fade">';
                    }
                    ?>
                </colgroup>
                <thead>
                    <tr>
                        <th class="padding-left--normal padding-right--normal">Table</th>
                        <?php
                        $hour = 8;
                        while($hour++ < $max)
                        {
                            $timetoprint = date('H:i',mktime($hour,0,0,1,1,2017));
                            echo '<th class="min-width--200">' . $timetoprint . '</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
                <tbody>

                    <?php
                    foreach ($results['Upstairs'] as $result) 
                    {
                        echo '<tr>';
                        echo '<td class="min-width--large text-align--center padding-top--tiny padding-bottom--tiny">' . $result['table_number'] . '</td>';
                        echo '<td class="calendar__table-row" colspan="24">';
                        echo '<div class="calendar__row box-sizing--content-box display--inline-block padding--small padding-left--normal padding-right--normal background--primary">';
                        echo '<span class="calendar__info text--white">Johnson | Table of 4</span>';
                        echo '</div>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
*/ ?>

    <?php require 'footer.php'; ?>

    <script>
    /*$(".calendar-container").mCustomScrollbar({
        axis:"x", // horizontal scrollbar
        theme:"dark"
    });*/
    </script>

    <div id="add-form" class="padding--large background--grey text--white" title="Admin | New Booking">
        <iframe src="/index.php?<?php if (strtotime($cur_date)<>strtotime(date('Y-m-d'))) {echo 'admin_date='.$cur_date.'&';} ?>admin_meal=<?php echo strtolower($selected_meal); ?>&admin" style="width: 100%;height: 550px;"></iframe>
    </div>