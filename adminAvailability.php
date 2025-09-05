<?php require 'headerAdmin.php'; ?>

    <?php require 'navigationAdmin.php'; ?>

    <div class="background--white max-width--1000 margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--normal padding-right--normal">

        <div class="grid">
            <form action="adminSearch.php" class="form--borders">
                <div class="col col-1-4 vertical-align--middle">
                    <input type="text" id="PartyDate" name="PartyDate" placeholder="Date">
                </div>
                <div class="col col-1-4 vertical-align--middle">
                    <select id="Meal" name="Meal">
                        <option value="lunch">Lunch</option>
                        <option value="dinner">Dinner</option>
                    </select>
                </div>
                <div class="col col-1-4 vertical-align--middle">
                    <button id="add-booking" type="button" value="Add a Booking" class="text--white padding-top--small padding-bottom--small">Add a booking</button>
                </div>
                <div class="col col-1-4 margin-bottom--normal text-align--center vertical-align--middle">
                    <a href="admin.php" class="padding--small"><li class="icon icon--rows vertical-align--middle"></li></a>
                    <a href="adminSchedule.php" class="padding--small"><li class="icon icon--rows-short vertical-align--middle"></li></a>
                </div>
            </form>
        </div>


        <div>
            <label class="switch vertical-align--middle">
                <input type="checkbox">
                <div class="slider"></div>
            </label>
            <span class="display--inline-block padding-left--small margin-bottom--small vertical-align--middle">Online Availability</span>
        </div>

        <h3 class="text--bold">Downstairs</h3>

        <?php

        //$conn = new PDO("mysql:host=localhost;dbname=lighthouse-booking","jack", "hack528618");

        $query = "SELECT * FROM bookings WHERE party_date = '$today' AND table_id <= '19' ORDER BY party_time";

        $results = database($query);

        echo "
        <table class='margin-bottom--normal'>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Covers</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Comments</th>
                    <th>Table Number</th>
                    <th>Req</th>
                    <th>Edit</th>
                </tr>
            </thead>
        <tbody>"
        ;

        foreach ($results as $result) 
        {
            echo "<tr>";
            $time = substr( $row['party_time'], 0, 5);
            echo "<td>" . $time . "</td>";
            echo "<td>" . $row['party_size'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "<td>" . $row['telephone'] . "</td>";
            echo "<td>" . $row['requests'] . "</td>";
            echo "<td>" . $row['table_id'] . "</td>";
            $id = $row['booking_id'];
            echo "<td>" . $row['booking_id'] . "</td>";
            echo "<td>" . "<a class='icon icon--small icon--edit' href='adminAmendBooking.php?ammend=" . $id . "'>" . "</a>" . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";

        ?>

        <div class="tables-list margin-bottom--normal">
            <?php
                for($i = 1; $i <=  19; $i++)
                {
                    echo '<div class="display--inline-block width--auto margin-bottom--small">';
                    echo '<input type="checkbox" class="display--none tables-list__item" id="table_' . $i . '" name="table_' . $i . '">';
                    echo '<label class="display--inline-block width--auto padding--tiny margin-left--small border border--tiny border-color--black" for="table_' . $i . '">' . $i . '</label>';
                    echo '</div>';
                }
            ?>

            <a href="/adminSchedule.php"><div class="display--inline-block text--white border--curved padding--small padding-left--normal padding-right--normal background--primary float--right">10 Unallocated</div></a>
        </div>

        <div>
            <label class="switch vertical-align--middle">
                <input type="checkbox">
                <div class="slider"></div>
            </label>
            <span class="display--inline-block padding-left--small margin-bottom--small vertical-align--middle">Online Availability</span>
        </div>

        <h3 class="text--bold">Upstairs</h3>
        <?php

        //$conn = new PDO("mysql:host=localhost;dbname=lighthouse-booking","jack", "hack528618");

        $query = "SELECT * FROM bookings WHERE party_date = '$today' AND table_id > '19' ORDER BY party_time";

        $results = database($query);

        echo "
        <table class='margin-bottom--normal'>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Covers</th>
                    <th>Name</th>
                    <th>Mobile</th>
                    <th>Comments</th>
                    <th>Table Number</th>
                    <th>Req</th>
                    <th>Edit</th>
                </tr>
            </thead>
        <tbody>"
        ;

        foreach ($results as $result)
        {
            echo "<tr>";
            $time = substr( $row['party_time'], 0, 5);
            echo "<td>" . $time . "</td>";
            echo "<td>" . $row['party_size'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "<td>" . $row['telephone'] . "</td>";
            echo "<td>" . $row['requests'] . "</td>";
            echo "<td>" . $row['table_id'] . "</td>";
            $id = $row['booking_id'];
            echo "<td>" . $row['booking_id'] . "</td>";
            echo "<td>" . "<a class='icon icon--small icon--edit' href='adminAmendBooking.php?ammend=" . $id . "'>" . "</a>" . "</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";

        ?>

        <div class="tables-list">
            <?php
                for($i = 20; $i <=  34; $i++)
                {
                    echo '<div class="display--inline-block width--auto margin-bottom--small">';
                    echo '<input type="checkbox" class="display--none tables-list__item" id="table_' . $i . '" name="table_' . $i . '">';
                    echo '<label class="display--inline-block width--auto padding--tiny margin-left--small border border--tiny border-color--black" for="table_' . $i . '">' . $i . '</label>';
                    echo '</div>';
                }
            ?>

            <a href="/adminSchedule.php"><div class="display--inline-block text--white border--curved padding--small padding-left--normal padding-right--normal background--primary float--right">10 Unallocated</div></a>
        </div>
    </div>

    <?php
    //**** START Database connection script
    // Connect to database script
    include ("connect.php");
    //**** END Database connection script

    $ml = $_POST["Meal"];
    $pd = $_POST["SearchDate"];
    $ps = $_POST["PartySize"];
    $rd = strtotime($pd);

    ?>

    <div id="availability-form" class="padding--large background--grey text--white" title="<?php echo date("jS F Y", $rd) . " | " . $ps . " Guests | " . $ml; ?>">
        <form action="adminBookingSubmit.php" method="POST">
            <div>

            <h3>Downstairs Availbility</h3>

            <?php
            //$conn = new PDO("mysql:host=localhost;dbname=lighthouse-booking","jack", "hack528618");

            $results = database("SELECT * from slots WHERE slot_meal='$ml' AND slot_location='downstairs'");

            echo "<ul class='slots'>";

            foreach ($results as $result)
            {
                $time = $row['slot_time'];
                $tables_booked = $row['slot_tables_booked'];
                if ($tables_booked < 10) {
                    echo "<a href='adminBookingSubmit.php?PartySize=$ps&Meal=$ml&PartyDate=$pd&Time=$time'><li class='slot-item background--primary'>$time</li></a>";
                } else if ($tables_booked >= 10) {
                    echo "<a href='adminBookingOverride.php?PartySize=$ps&Meal=$ml&PartyDate=$pd&Time=$time'><li class='slot-item background--grey-light'>$time</li></a>";
                }
            }

            echo "</ul>";

            ?>

            <h3>Upstairs Availability</h3>

            <?php

            $results = database("SELECT * from slots WHERE slot_meal ='$ml' AND slot_location ='upstairs'");

            echo "<ul class='slots'>";

            foreach ($results as $result)
            {
                $time = $row['slot_time'];
                $tables_booked = $row['slot_tables_booked'];
                if ($tables_booked < 10) {
                    echo "<a href='adminBookingSubmit.php?PartySize=$ps&Meal=$ml&PartyDate=$pd&Time=$time'><li class='slot-item background--primary'>$time</li></a>";
                } else if ($tables_booked >= 10) {
                    echo "<li class='slot-item background--grey-light'>$time</li>";
                }
            }

            echo "</ul>";

            ?>

            </div>
        </form>
    </div>

<?php require 'footer.php'; ?>
