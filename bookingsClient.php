<?php require 'header.php'; ?>

<div class="container background-image--cover" style="background-image:url('images/wine.jpg')">

    <div class="modal background--grey">

        <p class="position--relative text-align--center heading-w-icons">
            <a class='icon--small icon--return-grey position--absolute position--top position--left' href='/bookingSearch.php'></a>
            Email address and last name are both required to find your booking
            <a class='icon--small icon--cancel-grey position--absolute position--top position--right' href='/'></a>
        </p>

		<hr class="margin-bottom--large">
        <div class="table-container">
        <?php
        if (isset($_POST['LastName'])) {
            $_SESSION['LastName'] = $lastname = $_POST["LastName"];
        } else {
            $lastname = $_SESSION['LastName'];
        }
        if(isset($_POST['EmailAddress'])){
           $_SESSION['EmailAddress'] = $email = $_POST["EmailAddress"];
        }else{
            $email = $_SESSION['EmailAddress'];
        }
        
    	$results = database("SELECT * from bookings WHERE last_name='$lastname' AND email_address='$email'");
        if(count($results) == 0){
            echo 'No bookings were found, please remember to use email address & last name to view bookings. If you think there is an issue, please contact the restaurant on 01728 453377';
        }else{
        echo "
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Guests</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Cancel?</th>
                </tr>
            </thead>
            <tbody>"
        ;

        foreach ($results as $row)
    	{
            echo "<tr>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "<td>" . $row['last_name'] . "</td>";
            $ln = $row['last_name'];
            echo "<td>" . $row['party_size'] . "</td>";
            $rd = strtotime($row['party_date']);
            echo "<td>" . date("d/m/Y", $rd) . "</td>";
            echo "<td>" . $row['party_time'] . "</td>";
            $id = $row['booking_id'];
            echo "<td>" . "<a class='icon--small icon--cancel' href='bookingDelete.php?id=$id&lastName=$ln'>" . "</a>" . "</td>";
            echo "</tr>";
        }

        echo "
            </tbody>
        </table>
        ";
        }

        ?>
    </div>
	</div>

</div>

<?php require 'footer.php'; ?>
