<?php require 'header.php'; ?>
<?php
$ml = $_REQUEST["Meal"];
$pd = date('Y-m-d',strtotime($_REQUEST["PartyDate"]));
$ps = $_REQUEST["PartySize"];
$rd = strtotime($pd);

if (strtotime($pd) > strtotime('today +6 months'))
{
    header('Location: index.php');
    exit();
}

include ("check_availability.php");
?>
<div class="container background-image--cover" <?php if (!$_SESSION['admin_access'] OR isset($_GET['useraccountid'])) { ?> style="background-image:url('images/wine.jpg')" <?php } ?>>

    <div class="modal background--grey-transparent">

    <p class="position--relative margin-bottom--normal text-align--center heading-w-icons" style="padding: 15px 0px;">
        <a class='icon--small icon--return-grey position--absolute position--top position--left' style="top: 15px;" href='/'></a>
        <?php echo date("jS F Y", $rd) . " | " . $ps . " Guests | " . $ml; ?>
        <?php
        if (!$_SESSION['admin_access']) 
        {
        ?>
        <a class='icon--small icon--cancel-grey position--absolute position--top position--right' style="top: 15px;" href='/'></a>
        <?php 
        }
        ?>
    </p>

    <hr />

    <?php
    /*$result = database("SELECT * FROM day_settings WHERE `date`='".date('Y-m-d',$rd)."' AND meal='$ml' LIMIT 1");
    $min_covers = $result['minimum_covers'];
    $max_covers = $result['maximum_covers'];

    if ($ps < $min_covers OR $ps > $max_covers)
    {
        ?>
        <h3 style="text-align: center;color: red;">Your party size is not between the minimum and maximum that is required, <br />Please go back to select a different day or meal time</h3>
        <?php
    }
    else
    {*/
        ?>
        <h3 style="padding: 15px 0px;">Availability</h3>

        <?php
        $slot_availability = array();

        $results = database("SELECT * from slots_default_covers, day_settings WHERE day_settings.date='$pd' AND lower(day_settings.meal)=lower('$ml') AND day_settings.open_time <= slots_default_covers.slot_time AND day_settings.close_time >= slots_default_covers.slot_time");
        //if (count($results) == 0)
        //{
            //$results = database("SELECT * from slots WHERE slot_meal=lower('$ml')");
        //}

        echo "<ul class='slots'>";

        foreach ($results as $row) 
        {
            $time = date('H:i',strtotime($row['slot_time']));
            $slot_availability[$time] = slot_availability($time);
        }
        //print_r($slot_availability);

        if (array_search('Y', $slot_availability) OR isset($_SESSION['admin_access']))
        {
            foreach ($slot_availability as $time => $availbility) 
            {
                if ($availbility == 'Y') {
                    echo "<a href='booking.php?PartySize=$ps&Meal=$ml&PartyDate=$pd&Time=$time:00'><li class='slot-item background--primary'>$time</li></a>";
                } elseif (isset($_SESSION['admin_access'])) {
                    echo "<li class='slot-item background--grey-light confirm' style='cursor:pointer' data-url='booking.php?PartySize=$ps&Meal=$ml&PartyDate=$pd&Time=$time:00'>$time</li>";
                } else {
                    echo "<li class='slot-item background--grey-light'>$time</li>";
                }
            }
        }
        else
        {
            echo '<h3 style="text-align: center;color: red;">There are no tables available for '.date('j F Y',strtotime($pd)).',<br />Please go back and select different day</h3>';
        }

        echo "</ul>";
        ?>

        <?php /*
        <h3>Downstairs Availability</h3>

        <?php
        $results = database("SELECT * from slots WHERE slot_meal='$ml' AND slot_location='downstairs'");

        echo "<ul class='slots'>";

        foreach ($results as $row)
        {
            $time = date('H:i',strtotime($row['slot_time']));
            if (availbility($time,'downstairs') == 'Y') {
                echo "<a href='booking.php?PartySize=$ps&Meal=$ml&PartyDate=$pd&Time=$time:00&Location=Downstairs'><li class='slot-item background--primary'>$time</li></a>";
            } else {
                echo "<li class='slot-item background--grey-light'>$time</li>";
            }
        }

        echo "</ul>";

        ?>

        <h3>Upstairs Availability</h3>

        <?php

        $results = database("SELECT * from slots WHERE slot_meal='$ml' AND slot_location='upstairs'");

        echo "<ul class='slots'>";

        foreach ($results as $row) 
        {
            $time = date('H:i',strtotime($row['slot_time']));
            if (availbility($time,'upstairs') == 'Y') {
                echo "<a href='booking.php?PartySize=$ps&Meal=$ml&PartyDate=$pd&Time=$time:00&Location=Upstairs'><li class='slot-item background--primary'>$time</li></a>";
            } else {
                echo "<li class='slot-item background--grey-light'>$time</li>";
            }
        }

        echo "</ul>";
        */ ?>

        <?php
    //}
    ?>

    </div>

</div>

<?php require 'footer.php'; ?>
