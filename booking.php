<?php require 'header.php'; ?>
<?php
if (!isset($_GET["PartySize"]) OR 
    !isset($_GET["Meal"]) OR
    !isset($_GET["Time"]) OR
    !isset($_GET["PartyDate"]))
{
    header('Location: index.php?error=unknown');
}

$ps = $_GET["PartySize"];
$ml = $_GET["Meal"];
$pd = $_GET["PartyDate"];
$tm = $_GET["Time"];
$rd = strtotime($pd);

include ("check_availability.php");
?>
<div class="container background-image--cover" style="<?php if (!$_SESSION['admin_access'] OR isset($_GET['useraccountid'])) { ?> background-image:url('images/wine.jpg') <?php } else { ?> padding:0px; <?php } ?>" >

    <div class="modal background--grey" <?php if ($_SESSION['admin_access']) { ?> style="padding: 11px;" <?php } ?>>
    <p class="position--relative margin-bottom--normal text-align--center heading-w-icons">
        <a class='icon--small icon--return-grey position--absolute position--top position--left' href='slots-available.php?Meal=<?php echo $ml; ?>&PartyDate=<?php echo $pd; ?>&PartySize=<?php echo $ps; ?>'></a>
        <?php
        if (!isset($_GET['PartySize'], $_GET['Meal'], $_GET['PartyDate'], $_GET['Time'])) {
            echo "Error";
        } else {
            echo date("jS F Y", $rd) . " | " . $ps . " Guests | " . $ml . " | " . date('H:ia',strtotime($tm));
        }
        if (!$_SESSION['admin_access']) 
        {
        ?>
        <a class='icon--small icon--cancel-grey position--absolute position--top position--right' href='/'></a>
        <?php 
        }
        ?>
    </p>

    <hr />

    <?php 
    $available_combi = get_available_combi();
    extract($available_combi);

    if (count($tables) > 0 OR isset($_SESSION['admin_access']))
    {
        //echo '<p>TableIDs: '.implode(', ', $tables).' | Table Numbers: '.implode(', ', $table_numbers).'</p>';

        if (isset($_SESSION['useraccount']))
        {
            require_once 'master/classes/CMS/Component/Encryption/class.proCrypt.php';

            $useraccount = $_SESSION['useraccount'];
            $result = database("SELECT * FROM useraccounts WHERE useraccountid='$useraccount' LIMIT 1");
            $_firstname = $result['firstname'];
            $_surname = $result['surname'];
            $_telephone = $result['telephone'];
            $_email = $crypt->decrypt($result['email']);

            $allergyinfo = $result['allergyinfo'];
            $seatingpref = $result['seatingpref'];
            $winepref = $result['winepref'];

            $_requests = '';
            if ($allergyinfo > '') {$_requests .= $allergyinfo."\n";}
            if ($seatingpref > '') {$_requests .= $seatingpref."\n";}
            if ($winepref > '') {$_requests .= $winepref."\n";}
        }
        ?>
    	<form action="bookingSubmit.php" method="post">
            <input type="hidden" name="PartySize" value="<?php echo $ps; ?>" />
            <input type="hidden" name="Meal" value="<?php echo $ml; ?>" />
            <input type="hidden" name="PartyDate" value="<?php echo $pd; ?>" />
            <input type="hidden" name="Time" value="<?php echo $tm; ?>" />
            <?php if (count($tables) > 0) { ?><input type="hidden" name="Tables" value="<?php echo implode('|', $tables); ?>" /><?php } ?>
            <?php if (isset($_SESSION['useraccount'])) { ?><input type="hidden" name="useraccountid" value="<?php echo $useraccount; ?>" /><?php } ?>
            <div class="grid" <?php if ($_SESSION['admin_access']) { ?> style="margin-bottom: 0px;" <?php } ?>>
                <div class="col col-1-2">
            		<label for="FirstName">First Name</label>
            		<input type="text" id="FirstName" name="FirstName" autofocus <?php if ($_firstname > '') { ?> value="<?php echo $_firstname; ?>" <?php } ?>>
                </div>
                <div class="col col-1-2">
            		<label for="LastName">Last Name *</label>
            		<input type="text" id="LastName" name="LastName" required <?php if ($_surname > '') { ?> value="<?php echo $_surname; ?>" <?php } ?>>
                </div>
                <div class="col col-1-2">
            		<label for="Telephone">Telephone Number *</label>
            		<input type="text" id="Telephone" name="Telephone" required <?php if ($_telephone > '') { ?> value="<?php echo $_telephone; ?>" <?php } ?>>
                </div>
                <div class="col col-1-2">
            		<label for="Email">Email<?php if (!$_SESSION['admin_access']) { ?> *<?php } ?></label>
            		<input type="email" id="Email" name="Email" <?php if (!$_SESSION['admin_access']) { ?> required <?php } if ($_email > '') { ?> value="<?php echo $_email; ?>" <?php } ?>>
                </div>
                <?php 
                if ($_SESSION['admin_access']) 
                { 
                    ?>
                    <div class="col col-1-2" style="vertical-align: top;">
                      <label for="SpecialRequests">Add Special Requests </label>
                      <textarea id="SpecialRequests" name="SpecialRequests" rows="6"></textarea>                    
                    </div>
                    <div class="col col-1-2" style="vertical-align: top;">
                        <label>Staff Comments</label>
                        <textarea id="CustomerComments" name="staffcomments" class="rezisable--none" rows=6 ></textarea>
                        <label>Staff Name *</label>
                        <input type="text" name="staffname" required />
                    </div>
                    <?php 
                } 
                else 
                { 
                    if (!$_SESSION['useraccount'])
                    {
                        ?>
                        <div class="col padding-right--none margin-bottom--large">
                          <label for="AllergyInfo">Add Allergy Information</label>
                          <textarea id="AllergyInfo" name="AllergyInfo" rows="5"></textarea>
                        </div>
                        <?php  
                    }
                    ?>
                    <div class="col padding-right--none margin-bottom--large">
            		  <label for="SpecialRequests">Add Special Requests<br /> (If you have made a request to sit somewhere particular or have a certain dish, please note this is not guaranteed - Please contact us if you wish to discuss).</label>
            		  <textarea id="SpecialRequests" name="SpecialRequests" rows="5"><?php if ($_requests > '') { echo $_requests; } ?></textarea>
                    </div>
                    <?php 
                    if (!$_SESSION['useraccount'])
                    { 
                        ?>
                        <div class="col padding-right--none margin-bottom--large">
                          <label for="password">If You Wish To Create an Account Please enter a Password</label>
                          <p>By having an account you will be able to save your details, and will allow us to keep records of your dietry requirements, drink preferences and other key notes that allow us to enhance your visit to The Lighthouse.</p>
                          <input id="password" type="password" name="password">
                        </div>
                        <?php 
                    } 
                }                 
                if (!$_SESSION['admin_access'] && !$_SESSION['useraccount']) 
                {
                    ?>
                    <label><input type="checkbox" style="margin: 0px;
                    height: 14px;" required/>&nbsp;By submit your booking you are agreeing to our <a target="_blank" href="https://lighthouserestaurant.co.uk/privacy-policy/">privacy policy</a></label><br /><br />
                    <?php 
                } 
                ?>
                <div class="col">
                    <input type="submit" value="Book Now" class="text--white front-end-submit"></input>
                </div>
            </div>
	   </form>
       <?php 
    }
    ?>
    </div>
</div>

<?php require 'footer.php'; ?>
