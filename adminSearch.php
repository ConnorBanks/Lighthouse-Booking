<?php require 'headerAdmin.php'; 

require 'bootstrap.php';
?>

    <?php require 'navigationAdmin.php'; ?>

    <div class="background--white max-width--1000 margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--normal padding-right--normal">

    <div id="tabs" <?php if (isset($_GET['tab'])) { echo 'data-active="'.$_GET['tab'].'"'; } ?>>
        <ul class="margin-bottom--large">
            <li class="tab"><a href="#tabs-1">Upcoming Bookings</a></li>
            <li class="tab"><a href="#tabs-2">Recent Bookings</a></li>
            <li class="tab"><a href="#tabs-3">Search All</a></li>
        </ul>
        <div class="clear"></div>

        <div id="tabs-1">
            <?php require 'pagesSearch/upcomingBookings.php'; ?>
        </div>        
        <div id="tabs-2">
            <?php require 'pagesSearch/recentBookings.php'; ?>
        </div>
        <div id="tabs-3">
            <?php require 'pagesSearch/allBookings.php'; ?>
        </div>
    </div>

    </div>

<?php require 'footer.php'; ?>
