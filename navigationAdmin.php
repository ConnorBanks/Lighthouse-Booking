<div class="background--grey padding--normal position--relative">

        <a href="/admin.php" class="admin_logo">
            <img src="images/lighthouse-restaurant-logo.png" alt="lighthouse-restaurant-logo">
        </a>

        <nav class="admin_nav">
            <ul>
                <?php /*$availability = database("SELECT * FROM day_settings WHERE `date`='$cur_date' AND meal='$selected_meal' LIMIT 1"); ?>
                <?php 
                    $allow_admin_booking = (count($availability) == 0?'Y':$availability['admin_booking']); 
                    if ($allow_admin_booking == 'Y')
                    {
                        ?>
                        
                        <li class="vertical-align--middle"><a id="add-booking"><img src="images/calendar.png" class="display--block max-width--large margin-auto--left margin-auto--right padding--tiny padding-bottom--small">Add a Booking</a></li>
                        <?php 
                    } */
                ?>
                
                
                <li class="margin-left--large vertical-align--middle"><a href="admin.php"><img src="images/calendar.png" class="display--block max-width--large margin-auto--left margin-auto--right padding--tiny padding-bottom--small">Bookings</a></li>
                <li class="margin-left--large vertical-align--middle"><a href="settings.php"><img src="images/settings.png" class="display--block max-width--large margin-auto--left margin-auto--right padding--tiny padding-bottom--small">Settings</a></li>
                <li class="margin-left--large vertical-align--middle"><a href="adminSearch.php"><img src="images/search.png" class="display--block max-width--large margin-auto--left margin-auto--right padding--tiny padding-bottom--small">Search</a></li>
                <li class="margin-left--large vertical-align--middle"><a href="https://www.lighthouserestaurant.co.uk/booking-system-feedback/" target="_blank"><img src="images/chat.png" class="display--block max-width--large margin-auto--left margin-auto--right padding--tiny padding-bottom--small">Report Issue</a></li>
                <?php if ($_SESSION["username"]=='master') { ?>
                    <li class="margin-left--large vertical-align--middle"><a href="http://lighthouserest.bpweb.net/phpMyAdmin/" target="_blank"><img src="images/settings.png" class="display--block max-width--large margin-auto--left margin-auto--right padding--tiny padding-bottom--small">phpMyAdmin</a></li
                <?php } ?>
            </ul>
        </nav>

    </div>
</div>
