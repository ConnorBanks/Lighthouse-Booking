<?php require 'header.php'; 
require __DIR__ . '/bootstrap.php';?>

<?php

//$conn = new PDO("mysql:host=localhost;dbname=lighthouse-booking","jack", "hack528618"); ?>

<script>
    /*function showUser(str) {
        if (str == "") {
            document.getElementById("results").innerHTML = "";
            return;
        } else {
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("results").innerHTML = xmlhttp.responseText;
                }
            };
            xmlhttp.open("GET","searchClientAJAX.php?q="+str,true);
            xmlhttp.send();
        }
    }*/
</script>


<div class="container background-image--cover" style="background-image:url('images/wine.jpg')">

    <div class="modal background--grey">

        <p class="position--relative text-align--center heading-w-icons">
            <a class='icon--small icon--return-grey position--absolute position--top position--left' href='/'></a>
            Email address and last name are both required to find your booking
            <a class='icon--small icon--cancel-grey position--absolute position--top position--right' href='/'></a>
        </p>

		<hr class="margin-bottom--large">

        <div id="search">
            <h2>Search for your booking</h2>
            <form name="search" method="post" action="bookingsClient.php">
                <div class="grid">
                    <div class="col col-1-2">
                        <div class="input">
                            <label for="LastName">Last Name</label>
                            <input type="text" name="LastName" id="LastName" onkeypress="showUser(this.value)">
                        </div>
                    </div>
                    <div class="col col-1-2">
                        <div class="input input--half">
                            <label for="EmailAddress">Email Address</label>
                            <input type="email" name="EmailAddress" id="EmailAddress" onkeypress="showUser(this.value)">
                        </div>
                    </div>
                    <div class="col">
                        <div class="input input--full">
                            <input type="submit" value="Search for Booking" class="text--white"/>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <div id="results">

        </div>

	</div>

</div>

<?php require 'footer.php'; ?>
