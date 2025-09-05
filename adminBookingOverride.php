<?php require 'headerAdmin.php'; ?>

    <?php require 'navigationAdmin.php'; ?>

    <?php
        $ml = $_GET["Meal"];
        $pd = $_GET["PartyDate"];
        $ps = $_GET["PartySize"];
        $time = $_GET["Time"];
    ?>

    <div class="background--white max-width--600 margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--normal padding-right--normal">

        <h1 class="margin-bottom--large">Override Booking</h1>

        <h2 class="margin-bottom--normal">Booking Info</h2>
        <ul class="list--bulleted margin-bottom--normal">
            <li>Meal: <?php echo $ml; ?></li>
            <li>Meal Time: <?php echo $time; ?></li>
            <li>Meal Date: <?php echo $pd; ?></li>
            <li>Meal Size: <?php echo $ps; ?></li>
        </ul>

        <p>You have a set default not to allow more bookings at the selected time, please click below if you wish to override this.</p>

        <?php
        echo "<a class='display--block background--primary padding--small text-align--center' href='adminBookingSubmit.php?PartySize=$ps&Meal=$ml&PartyDate=$pd&Time=$time'>Continue</a>";
        ?>
    </div>

<?php require 'footer.php'; ?>
