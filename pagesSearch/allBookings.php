    <?php
    if (isset($_GET['bookingamended']))
    {
        $_POST['search'] = true;
        $_POST['PartyDate'] = $_SESSION['PartyDate'];
        $_POST['LastName'] = $_SESSION['LastName'];
        $_POST['EmailAddress'] = $_SESSION['EmailAddress'];
    }
    ?>
    <form action="adminSearch.php?tab=2" method="POST">
        <input type="hidden" name="search" value="true">
        <div class="grid">
            <div class="col col-1-4">
                <label for="PartyDate">Party Date</label>
                <input type="text" id="AdminDate" name="PartyDate" placeholder="<?php echo $UKtoday; ?>" value="<?php if (isset($_POST["PartyDate"])) {echo $_POST["PartyDate"];} else {echo '';} ?>">
            </div>
            <div class="col col-1-4">
                <div class="input">
                    <label for="LastName">Last Name</label>
                    <input type="text" name="LastName" id="LastName" value="<?php if (isset($_POST["LastName"])) {echo $_POST["LastName"];} else {echo '';} ?>">
                </div>
            </div>
            <div class="col col-1-4">
                <div class="input input--half">
                    <label for="EmailAddress">Email Address</label>
                    <input type="email" name="EmailAddress" id="EmailAddress" value="<?php if (isset($_POST["EmailAddress"])) {echo $_POST["EmailAddress"];} else {echo '';} ?>">
                </div>
            </div>
            <div class="col col-1-4">
                <input type="submit" value="Search" class="text--white"></input>
            </div>
        </div>
    </form>
    <?php
    if(isset($_POST["search"]) && 
        (
            $_POST['PartyDate'] > '' OR 
            $_POST['LastName'] > '' OR 
            $_POST['EmailAddress'] > ''
        )
    ) 
    {
        $date = $_SESSION['PartyDate'] = $_POST["PartyDate"];
        $lastname = $_SESSION['LastName'] = $_POST['LastName'];
        $email = $_SESSION['EmailAddress'] = $_POST['EmailAddress'];

        $date = str_replace('/', '-', $date);

        $searching_sql = array();
        if ($date > '') {$searching_sql[] = "bookings.party_date='".date('Y-m-d', strtotime($date))."'";}
        if ($lastname > '') {$searching_sql[] = "bookings.last_name LIKE '%$lastname%'";}
        if ($email > '') {$searching_sql[] = "bookings.email_address LIKE '%$email%'";}

        $results_downstairs = database("SELECT DISTINCT bookings.* FROM bookings, bookings_tables WHERE (".implode(' OR ', $searching_sql).") AND bookings.booking_id=bookings_tables.booking_id AND bookings_tables.table_id <= 19 ORDER BY bookings.party_date DESC, bookings.party_time");
        $results_upstairs = database("SELECT DISTINCT bookings.* FROM bookings, bookings_tables WHERE (".implode(' OR ', $searching_sql).") AND bookings.booking_id=bookings_tables.booking_id AND bookings_tables.table_id > 19 ORDER BY bookings.party_date DESC, bookings.party_time");

        $searching = array();
        if ($date > '') {$searching[] = date('jS M, Y', strtotime($date));$date_search = true;}
        if ($lastname > '') {$searching[] = $lastname;$date_search = false;}
        if ($email > '') {$searching[] = $email;$date_search = false;}

        if ($date_search == true)
        {
            ?>
            <h2>Searching all bookings on <?php echo implode(', ', $searching);?></h2>
            <?php
        }
        else 
        {
            ?>
            <h2>Searching all bookings for "<?php echo implode(', ', $searching);?>"</h2>
            <?php
        }
        ?>        

        <h3 class="text--bold">Downstairs</h3>
        <div class="scroll-table">
        <?php

        echo "
        <table class='margin-bottom--large'>
            <thead>
                <tr>
                    ".($date == ''?'<th>Date</th>':'')."
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

        foreach ($results_downstairs as $row)
        {
            echo "<tr>";
            if ($date == '') {echo "<td>" . date('jS M, Y', strtotime($row['party_date'])) . "</td>";}
            $time = substr( $row['party_time'], 0, 5);
            echo "<td>" . $time . "</td>";
            echo "<td>" . $row['party_size'] . "</td>";
            echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
            echo "<td>" . $row['telephone'] . "</td>";
            echo "<td>" . $row['requests'] . "</td>";
            $id = $row['booking_id'];
            echo "<td>";
                $tables = array();
                $subresults = database("SELECT * FROM bookings_tables, tables_single WHERE bookings_tables.booking_id='$id' AND bookings_tables.table_id=tables_single.table_id");
                foreach ($subresults as $subresult) 
                {
                    $tables[] = $subresult['table_number'];
                }
                echo implode(', ', $tables);
            echo "</td>";
            echo "<td>";
            if($row['locked'] == 'Y') {echo '<img src="images/padlock.png"';}
            echo "</td>";            echo "<td>" . "<a class='icon icon--small icon--edit padding--normal' href='adminAmendBooking.php?booking_id=$id&returnurl=adminSearch.php'>" . "</a>" . "</td>";
            echo "</tr>";
        }

        echo "
            </tbody>
        </table>
        "
        ;
        ?>
    </div>
        <h3 class="text--bold">Upstairs</h3>
        <div class="scroll-table">
        <?php
        echo "
        <table class='margin-bottom--large'>
            <thead>
                <tr>
                    ".($date == ''?'<th>Date</th>':'')."
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

        foreach ($results_upstairs as $row)
        {
            echo "<tr>";
            if ($date == '') {echo "<td>" . date('jS M, Y', strtotime($row['party_date'])) . "</td>";}
            $time = substr( $row['party_time'], 0, 5);
            echo "<td>" . $time . "</td>";
            echo "<td>" . $row['party_size'] . "</td>";
            echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
            echo "<td>" . $row['telephone'] . "</td>";
            echo "<td>" . $row['requests'] . "</td>";
            $id = $row['booking_id'];
            echo "<td>";
                $tables = array();
                $subresults = database("SELECT * FROM bookings_tables, tables_single WHERE bookings_tables.booking_id='$id' AND bookings_tables.table_id=tables_single.table_id");
                foreach ($subresults as $subresult) 
                {
                    $tables[] = $subresult['table_number'];
                }
                echo implode(', ', $tables);
            echo "</td>";
            echo "<td>";
            if($row['locked'] == 'Y') {echo '<img src="images/padlock.png"';}
            echo "</td>";            echo "<td>" . "<a class='icon icon--small icon--edit padding--normal' href='adminAmendBooking.php?booking_id=$id&returnurl=adminSearch.php'>" . "</a>" . "</td>";
            echo "</tr>";
        }

        echo "
            </tbody>
        </table></div>
        "
        ;
    }
    elseif(isset($_POST["search"]) && 
        $_POST['PartyDate'] == '' && 
        $_POST['LastName'] == '' && 
        $_POST['EmailAddress'] == ''
    ) 
    {
        echo '<p style="color:red;">Please enter a value in at least one box above</p>';
    }
    ?>