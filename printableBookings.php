<?php $hide_logout = true; ?>
<?php require 'headerAdmin.php'; ?>
<?php $date = $_GET['cur_date']; ?>
<div style="padding: 1cm;">		
		<h1 style="text-align: center;">Bookings for <?php echo date('l dS F Y',strtotime($date)); ?></h1>
        
        <h3 class="text--bold">Downstairs:</h3>

        <?php

        $query = "SELECT DISTINCT bookings.* FROM bookings, bookings_tables WHERE bookings.party_date = '$cur_date' AND bookings.meal='$selected_meal' AND bookings.booking_id=bookings_tables.booking_id AND bookings_tables.table_id <= '19' ORDER BY party_time, booking_id";
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
                    <th>Table Numbers</th>
                    <th></th>
                </tr>
            </thead>
        <tbody>"
        ;
        $total_party_size = 0;
        foreach ($results as $row)
        {
            echo "<tr>";
            $time = substr( $row['party_time'], 0, 5);
            echo "<td>" . $time . "</td>";
            echo "<td>" . $row['party_size'] . "</td>";
            echo "<td>" . $row['first_name'] . ' ' . $row['last_name'] . "</td>";
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
            echo "<td align='right'>";
                if($row['locked'] == 'Y') {echo '<img class="padlock" style="width:30%;margin-right: 10px;" src="images/padlock.png" />';}
            echo "</td>";
            echo "</tr>";
            $total_party_size = $total_party_size + $row['party_size'];
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
        echo "</tbody></table>";

        ?>

        <h3 class="text--bold">Upstairs:</h3>

        <?php

        $query = "SELECT DISTINCT bookings.* FROM bookings, bookings_tables WHERE bookings.party_date = '$cur_date' AND bookings.meal='$selected_meal' AND bookings.booking_id=bookings_tables.booking_id AND bookings_tables.table_id > '19' ORDER BY party_time";

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
                    <th>Table Numbers</th>
                </tr>
            </thead>
        <tbody>"
        ;
        $total_party_size = 0;
        foreach ($results as $row)
        {
            echo "<tr>";
            $time = substr( $row['party_time'], 0, 5);
            echo "<td>" . $time . "</td>";
            echo "<td>" . $row['party_size'] . "</td>";
            echo "<td>" . $row['first_name'] . ' ' . $row['last_name'] . "</td>";
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
            echo "<td align='right'>";
                if($row['locked'] == 'Y') {echo '<img class="padlock" style="width:30%;margin-right: 10px;" src="images/padlock.png" />';}
            echo "</td>";
            echo "</tr>";
            $total_party_size = $total_party_size + $row['party_size'];
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
        echo "</tbody></table>";

        ?>
    </div>