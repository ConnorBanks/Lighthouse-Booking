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

        foreach ($results as $row) 
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

        $query = "SELECT * FROM bookings WHERE party_date = '$today' AND table_id > 19 ORDER BY party_time";

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

        foreach ($results as $row) 
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

    if (!isset($_GET["PartySize"])){
        $_GET["PartySize"] = "0";
    }

    if (!isset($_GET["Meal"])){
        $_GET["Meal"] = "Lunch";
    }

    if (!isset($_GET["Time"])){
        $_GET["Time"] = "";
    }

    if (!isset($_GET["PartyDate"])){
        $_GET["PartyDate"] = "";
    }

    $ps = $_GET["PartySize"];
    $ml = $_GET["Meal"];
    $pd = $_GET["PartyDate"];
    $tm = $_GET["Time"];

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

    $_SESSION['PartySize'] = $ps;
    $_SESSION['Meal'] = $ml;
    $_SESSION['PartyDate'] = $pd;
    $_SESSION['Time'] = $tm;

    $rd = strtotime($pd);

    ?>

    <div id="booking-form" class="padding--large background--grey text--white"

    title="
    <?php
        if (!isset($_GET['PartySize'], $_GET['Meal'], $_GET['PartyDate'], $_GET['Time'])) {
            echo "Error";
        } else {
            echo date("jS F Y", $rd) . " | " . $ps . " Guests | " . $ml . " | " . $tm ;
        }
    ?>
    ">

        <form action="adminBookingConfirm.php" method="POST">
            <div class="grid">
                <div class="col col-1-2">
                    <label for="FirstName">First Name</label>
                    <input type="text" id="FirstName" name="FirstName" autofocus required>
                </div>
                <div class="col col-1-2">
                    <label for="LastName">Last Name</label>
                    <input type="text" id="LastName" name="LastName" required>
                </div>
                <div class="col col-1-2">
                    <label for="Telephone">Contact Telephone Number</label>
                    <input type="number" id="Telephone" name="Telephone" required>
                </div>
                <div class="col col-1-2">
                    <label for="Email">Email Address</label>
                    <input type="email" id="Email" name="Email" required>
                </div>
                <div class="col col-1-2">
                    <label for="SpecialRequests">Add Special Requests</label>
                    <textarea id="SpecialRequests" name="SpecialRequests" rows="6" class="rezisable--none"></textarea>
                </div>
                <div class="col col-1-2">
                    <label for="StaffComments">Staff Comments</label>
                    <input type="text" id="StaffComments" name="StaffComments">

                    <label for="StaffName">Staff Name</label>
                    <input type="text" id="StaffName" name="StaffName" required>
                </div>
                <div class="input input--full">
                    <input type="submit" value="Submit Booking" class="text--white"></input>
                </div>
            </form>
        </div>
    </div>

<?php require 'footer.php'; ?>
