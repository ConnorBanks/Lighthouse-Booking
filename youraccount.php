<?php 
$indexpage = false;
$_SESSION['bookingcompleted'] = 'false';
include 'globalsettings.php';
require 'header.php'; 
require 'functions.php'; 
require_once SERVER.'master/classes/CMS/Component/Encryption/class.proCrypt.php';

$senderemail = 'info@lighthouserestaurant.co.uk';
$business = 'Lighthouse Restaurant';
?>

<div class="container background-image--cover" <?php if (!$_SESSION['admin_access']) { ?> style="background-image:url('images/wine.jpg')" <?php } ?>>
    <div class="modal background--grey-transparent">       
       <?php 
        if (isset($_REQUEST['reset'])) 
        {
            ?>
            <h2>Password Reset</h2>
            <?php   
            $data_array = json_decode($crypt->decrypt($_REQUEST['reset']),true);
            if (!is_array($data_array)) {$data_array = json_decode($crypt->decrypt(urldecode($_REQUEST['reset'])),true);}   
            if (!is_array($data_array)) {exit('<p style="color:red">Error: Invalid Data, Please <a href="contactus.php">contact us</a> to find out why.</p>');}
            extract($data_array);

            switch ($resettype) {
                case 'C':
                    $MODULE_ID = 110;
                    $TABLE_NAME = 'tutors';
                    $TABLE_ID_NAME = 'tutorid';
                    $NAMEFIELD = 'tutorname';
                    $EMAIL_TITLE = BUSINESS." - Coach Password Reset Confirmation";
                    $FORM_ACTION = 'coach_login';
                    break;
                
                default:
                    $MODULE_ID = 'profile_locks';
                    $TABLE_NAME = 'useraccounts';
                    $TABLE_ID_NAME = 'useraccountid';
                    $NAMEFIELD = 'name';
                    $EMAIL_TITLE = BUSINESS." - Password Reset Confirmation";
                    $FORM_ACTION = 'user_login';
                    break;
            }

            if (isset($user_ip) && $user_ip <> $_SERVER['REMOTE_ADDR']) 
            {
                exit('<h2 style="color:red">Error: Unknown Error Occurred, Please <a href="contactus.php">contact us</a> to find out why.</h2>');
            }

            $plus_24_hours_str = strtotime(date("Ymd h:i:s", $date_str) . " +24 hours");
            $now = strtotime('now');
            if ($now > $plus_24_hours_str) 
            {
                exit('<h2 style="color:red">Sorry your reset request has timed out<br />Please request another reset email and try again</h2>');
            }

            if (isset($_POST['pin']))
            {
                $proceed = false;

                $result = database("SELECT * FROM $TABLE_NAME WHERE $TABLE_ID_NAME='$reset_id' LIMIT 1");
                $salt = $crypt->decrypt($result['pin']);
                $pin_hash = $crypt->hash($_POST['pin'], $salt);

                if ($pin_hash == $reset_pin_hash) {$proceed = true;}
                else {$pin_error = true;}
            }
            elseif (isset($_POST['password_r1']))
            {
                $password_r1 = $_POST['password_r1'];
                $password_r2 = $_POST['password_r2'];
                    
                if ($password_r1 == $password_r2)
                {
                    $result = database("SELECT * FROM $TABLE_NAME WHERE $TABLE_ID_NAME='$reset_id' LIMIT 1");
                    $username = $crypt->decrypt($result['username']);
                    $email = (strpos($username, '@')?$username:$result['email']);

                    $salt = $crypt->decrypt($result['pin']);

                    $firstname = $result['firstname'];
                    if ($firstname == '') {$cur_name = $result[$NAMEFIELD];}
                    else {$cur_name = $firstname;}

                    $path = SERVER.'master/'; $file = $MODULE_ID.'.txt';
                    pw_file_update($path.$file, $reset_id, $password_r1, $salt);

                    $title = $EMAIL_TITLE;
                    $message = email_template(
                        'Dear '.$cur_name.',' . "<br />" .
                        'Your password has been reset successfully:' . "<br />" .
                        'Please login with your username:' . "<br />" .
                        '<h3>'.$username . '</h3> and the password you supplied' . "<br />" . "<br />" .
                        $business  .
                        "<br />"
                    );
                    
                    // SEND VIA PHP MAIL FUNCTION \\
                    /*$headers = 'X-Mailer: PHP/' . phpversion() ."\n" ; 
                    $headers .= 'MIME-Version: 1.0' . "\n";
                    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
                    $headers .= 'From: ' . $business . '<'.$senderemail.'>'."\n";
                    $headers .= 'Reply-To: ' . $business . '<'.$senderemail.'>' . "\n";

                    $sent_mail = mail($email, $title, $message, $headers);*/
                    $email_setup = array(
                        //'debug' => true,
                        'from' => array(
                            'email' => $senderemail,
                            'name' => $business
                        ),
                        'to' => array(
                            array('email' => $email),
                        ),
                        'subject' => $title,
                        'body' => $message,
                        'html_email' => true
                    );
                    $sent_mail = phpmailer($email_setup);
                    if ($sent_mail)
                    {                 
                        echo '<p style="color:green">Your password has been reset successfully and a confirmation email has been sent to '.$email.'</p>';
                    }
                    else
                    {
                        echo '<p style="color:red">Your password has been reset successfully, Unfortunately your confirmation email has not been sent, Please <a href="contactus.php">contact us</a> to find out why.</p>';
                    }   

                    $finished = true;
                }
                else
                {
                    $password_error = true; 
                }
            }
            
            if ($finished == true)
            {
                $form_action = $FORM_ACTION;
                ?>
                <div class="row">
                    <div class="col-xs-12">
                        <h3>Login</h3>
                        <p>Login below with the username and password supplied</p>
                        <form action="myaccount/index.php" method="POST">
                            <?php 
                            if ($pageurl=='checkout.php' OR isset($_REQUEST['checkout'])) 
                            { 
                                ?>
                                <input type="hidden" name="checkoutlogin" value="true" />
                                <?php 
                            }
                            ?>
                            <div class="form-group">
                                <label>Username (Email)</label>
                                <input class="form-control" type="text" name="login[username]" required="true" <?php if (basename($_SERVER['PHP_SELF'])=='register.php') { ?> value="<?php echo $crypt->decrypt($register['email']); ?>" <?php } ?> />
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" type="password" name="login[password]" required="true" />
                            </div>
                            <div class="form-group">
                                <input class="form-control btn btn-default" type="submit" value="Log In" />
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            }
            elseif ($pin_hash > '' && $proceed == true)
            {
                ?>
                <div class="row">
                    <div class="col-xs-6">
                        <p><b>Please type your new password, and click reset to continue</b></p>
                        <?php if ($password_error == true) {echo '<p style="color:red">Error: Both password fields must match to reset password</p>';} ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="reset" value="<?php echo $_REQUEST['reset']; ?>" />
                            <label>New Password</label>
                            <br />
                            <input class="form-control" type="password" name="password_r1" size="25" required="true" />
                            <br />
                            <label>Confirm Password</label>
                            <br />
                            <input class="form-control" type="password" name="password_r2" size="25" required="true" />
                            <br />
                            <input class="btn btn-default" type="submit" value="Reset" />
                        </form>
                    </div>
                </div>
                <?php           
            }
            else
            {
                ?>
                <div class="row">
                    <div class="col-xs-6">
                        <?php if ($pin_error == true) {echo '<p style="color:red">Error: Wrong PIN entered, please try again</p>';} ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="reset" value="<?php echo $_REQUEST['reset']; ?>" />
                            <label>Please type the PIN from the reset email</label>
                            <br />
                            <input class="form-control" style="text-transform:uppercase" type="text" name="pin" size="25" maxlength="5" required="true" />
                            <br />
                            <input class="btn btn-default" type="submit" value="Continue" />
                        </form>
                    </div>
                </div>
                <?php           
            }
        }
        ?>
    </div>
</div>
<?php require 'footer.php'; ?>
