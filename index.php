<?php $indexpage = true; ?>
<?php require 'header.php'; 
$_SESSION['bookingcompleted'] = 'false'; 
if (isset($_GET['useraccountid']))
{
    $_SESSION['useraccount'] = $_GET['useraccountid'];
}
?>
<div class="container background-image--cover" <?php if (!$_SESSION['admin_access'] OR isset($_GET['useraccountid'])) { ?> style="background-image:url('images/wine.jpg')" <?php } ?>>

    <div class="modal background--grey-transparent">
        <?php if (!$_SESSION['admin_access']) { ?>
        <div class="margin-bottom--normal text-align--center">
            <a href="/">
                <img src="images/lighthouse-restaurant-logo.png" alt="lighthouse-restaurant-logo" style="margin: 20px 0px;">
            </a>
        </div>
        <?php } ?>
        <?php if (isset($_GET['error']) && $_GET['error'] == 'unknown') { ?>
            <div style="color: red; text-align: center;margin: 10px;font-size: 18px;">An unknown error occured, please try again</div>
        <?php } ?>
        <?php if (isset($_GET['useraccountid'])) { $_SESSION['useraccountid'] = $useraccountid = $_GET['useraccountid'];
            $result = database("SELECT * FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1");
            $name = $result['name'];
            ?>
            <h3 style="text-align: center;">Booking for <?php echo $name; ?></h3>
        <?php } elseif ($_SESSION['admin_access'] && ((isset($_GET['admin_date'])) OR isset($_SESSION['admin_date']))) { 
            if (isset($_GET['admin_date'])) {$_SESSION['admin_date'] = $_GET['admin_date'];} 
            if (isset($_GET['admin_meal'])) {$_SESSION['admin_meal'] = $_GET['admin_meal'];} 
            ?>
            <h3 style="text-align: center;">Booking for <?php echo date('l j F Y',strtotime($_SESSION['admin_date'])); ?></h3>
        <?php } ?>
        <form action="slots-available.php" method="post">
            <div class="grid">
                <div class="col col-1-2">
                    <label for="PartySize">Party Size</label>
                    <select name="PartySize" required>
                    <option value="">Please select below</option>
                    <?php 
                    $results = database("SELECT DISTINCT bookingnumber FROM combinations ".(!$_SESSION['admin_access']?'WHERE bookingnumber <= 6 ':'')."ORDER BY bookingnumber");
                    foreach ($results as $result) 
                    {
                        $bookingnumber = $result['bookingnumber'];
                        echo '<option value="'.$bookingnumber.'">'.$bookingnumber.' '.($bookingnumber == 1?'Guest':'Guests').'</option>';
                    }
                    ?>
                    </select>
                </div>
                <?php if ($_SESSION['admin_access']) { ?>
                <div class="col col-1-2">
                    <label for="Meal">Lunch or Dinner</label>
                    <select id="Meal" name="Meal" required>
                        <?php //if (date('H:i') < '14:15') { ?>
                            <option value="">Please select below</option>
                            <option value="Lunch" <?php if ($_SESSION['admin_meal'] == 'lunch') {echo 'selected="selected"';} ?>>Lunch</option>
                            <option value="Dinner" <?php if ($_SESSION['admin_meal'] == 'dinner') {echo 'selected="selected"';} ?>>Dinner</option>
                        <?php /*} else { ?>
                            <option value="Dinner" selected="selected">Dinner</option>
                        <?php }*/ ?>
                    </select>
                </div>
                <?php } else { ?>
                <div class="col col-1-2">
                    <label for="Meal">Lunch or Dinner</label>
                    <select id="Meal" name="Meal" required>
                        <?php //if (date('H:i') < '14:15') { ?>
                            <option value="">Please select below</option>
                            <option value="Lunch" <?php if ($_SESSION['admin_meal'] == 'lunch') {echo 'selected="selected"';} ?>>Lunch</option>
                            <option value="Dinner" <?php if ($_SESSION['admin_meal'] == 'dinner') {echo 'selected="selected"';} ?>>Dinner</option>
                        <?php /*} else { ?>
                            <option value="Dinner" selected="selected">Dinner</option>
                        <?php }*/ ?>
                    </select>
                </div>
                <?php } ?>
                <?php if (
                    !$_SESSION['admin_access'] OR 
                    (!isset($_GET['admin_date']) && !isset($_SESSION['admin_access'])) OR 
                    (isset($_GET['useraccountid']) && isset($_SESSION['admin_access']))
                ) 
                { 
                    ?>
                    <div class="col col-1-2">
                        <label for="PartyDate">Date</label>
                        <input type="text" id="PartyDate<?php echo (!$_SESSION['admin_access']?'Plus6Months':''); ?>" name="PartyDate" size="30" autocomplete="off" value="<?php echo date('l j F Y',strtotime($UKtoday)); ?>" readonly="true" required>
                    </div>
                    <?php 
                } 
                else 
                { 
                    ?>
                    <input type="hidden" name="PartyDate" value="<?php echo date('l j F Y',strtotime($_SESSION['admin_date'])); ?>" />
                    <?php 
                } 
                ?>
            </div>
            <div class="input input--full">
                <input type="submit" value="See Availability" class="text--white front-end-submit"></input>
            </div>
        </form>
        <?php /*<p class="text--italic">* For parties of 7 and above, please contact the restraunt directly</p>*/ ?>
        <?php if (!$_SESSION['admin_access'] && !$_SESSION['useraccountid']) { ?>
        <hr>
        <ul class="text-align--center">
            <?php /*<a href="bookingSearch.php" class="large-icon-buttons">
                <li class="icon icon--calendar"></li>
                <p style="text-align: center;">Find Bookings</p>
            </a>*/ ?>
            <a href="http://www.lighthouserestaurant.co.uk/" target="_blank" class="large-icon-buttons">
                <li class="icon icon--globe"></li>
                <p style="text-align: center;">Visit<br />Website</p>
            </a>
            <a href="account_login.php" class="large-icon-buttons">
                <li class="icon icon--calendar"></li>
                <p style="text-align: center;">Login<br />& Bookings</p>
            </a>
        </ul>
        <?php } ?>
    </div>

</div>

<?php require 'footer.php'; ?>
