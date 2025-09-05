<?php 
$indexpage = false;
include 'globalsettings.php';
require 'header.php';
require 'functions.php'; 
$_SESSION['bookingcompleted'] = 'false';

$senderemail = 'info@lighthouserestaurant.co.uk';
$business = 'Lighthouse Restaurant';
?>

<div class="container background-image--cover" <?php if (!$_SESSION['admin_access']) { ?> style="background-image:url('images/wine.jpg')" <?php } ?>>
    <div class="modal background--grey-transparent">
        <a href="index.php">Back To Booking</a>     
        <h3 style="text-align: center;">Login</h3>
        <?php if (isset($_GET['password'])): 
      // ***** ASK FOR EMAIL ADDRESS *****
      ?>
      <p><a class="btn btn-default" href="<?php echo $_SERVER['PHP_SELF']; ?>">Back</a></p>
      <p>Please enter the email address you used to register with:</p>
      <form class="nomargins" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="hidden" name="sendresetemail" value="1" size="12" />
          <div class="form-group yname">
            <label for="">Email</label>
            <input class="form-control" type="text" name="email" size="25" />
          </div>
          <input class="btn btn-default" type="submit" value="Check Email" />
      </form>

    <?php elseif (isset($_REQUEST['sendresetemail'])): 
      require_once SERVER.'master/classes/CMS/Component/Encryption/class.proCrypt.php';
        $email = $_REQUEST['email'];
        // ***** CHECK EMAIL ADDRESS *****
        ?>
        <h3>Checking your details</h3>
        <?php
        $numemails = 0;
        $results = database("SELECT * FROM useraccounts ORDER BY useraccountid");
        foreach ($results as $result)
        {
          $db_username = $crypt->decrypt($result['username']);
          if ($email == $db_username)
          {
              $reset_id = $result['useraccountid'];
              $firstname = $result['firstname'];
              if ($firstname == '') {$cur_name = $result['name'];}
              else {$cur_name = $firstname;}
              $salt = $crypt->decrypt($result['pin']);
              $numemails++;
          }
        }

        if ($numemails == 0) 
        {
          ?>
        <div class="alert alert-danger">
          <strong>Error: </strong> We have no record of that Email address, <a href="<?php echo $_SERVER['PHP_SELF']; ?>?password=lost">Try Again</a>
        </div>
          <?php
        }
        elseif ($numemails > 1) 
        {
          ?>
        <div class="alert alert-danger">
          <strong>Error: </strong> There appears to be a problem with this E-mail address, <a href="contactus.php">Contact us</a> to find out why
        </div>
          <?php
        }
        elseif ($numemails == 1) 
        {
          // ***** SEND EMAIL *****
          $reset_pin = "";  for ($pincount=0;$pincount<5;$pincount++) {$reset_pin.=chr(mt_rand(65,90));} 
          $reset_pin_hash = $crypt->hash($reset_pin,$salt);

          $data_array = array(
            'reset_id' => $reset_id,
            'date_str' => strtotime('now'),
            'reset_pin_hash' => $reset_pin_hash,
            'user_ip' => $_SERVER['REMOTE_ADDR']
          );

          $LINK = '<a href="'.(strpos(FULLDOMAIN, 'www.')&&strpos(FULLDOMAIN, 'bpweb.net')?str_replace('www.', '', FULLDOMAIN):FULLDOMAIN).str_replace(' ', '%20', SERVER_PATH).'youraccount.php?reset='.urlencode($crypt->encrypt(json_encode($data_array))).'">Reset Password Now</a>';

          $MODULE_ID = 'profile_locks';
          $temp_pw = "R:".$reset_pin_hash.":".$reset_id."";

          $title = $business." - Password Reset";

          $messagebody = email_template(
            'Dear '.$cur_name.',' . "<br />" .
          'Please use this pin with the link below and follow on screen instructions to reset your password:' . "<br />" .
          '<h2>PIN: ' . $reset_pin . "</h2>" .
          $LINK . "<br />" .
          '<h3>Please note that this link will expire if not used within 24 hours.</h3>' . "<br />" .
          '<hr />' . 
          'Once your password has been changed you will be able to log in with your username:' . "<br />" .
          '<h3>'.$email . '</h3> and the password you supply' . "<br />" . "<br />" .
          $business .
          "<br />"
          );

          
        // SEND VIA PHP MAIL FUNCTION \\
            /*
        $headers = 'X-Mailer: PHP/' . phpversion() ."\n" ; 
        $headers .= 'MIME-Version: 1.0' . "\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
        $headers .= 'From: ' . $business . '<'.$senderemail.'>'."\n";
        $headers .= 'Reply-To: ' . $business . '<'.$senderemail.'>' . "\n";

        $sent_mail = mail($email, $title, $messagebody, $headers);
        */
        
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
          'body' => $messagebody,
          'html_email' => true
        );

        $sent_mail = phpmailer($email_setup);
        if ($sent_mail)
        {
          $path = SERVER.'master/'; $file = $MODULE_ID.'.txt';
          pw_file_update($path.$file, $reset_pin, $temp_pw, $salt);
          ?>
          <div class="alert alert-success">
            Please reset your password via the email sent to <?php echo $email; ?>
          </div>
          <?php
        }
        else
        {
          ?>
          <div class="alert alert-danger">
            <strong>Error: </strong> Your reset email has not been sent, Please <a href="contactus">contact us</a> to find out why
          </div>          
          <?php
        }
      }
      ?>
    <?php else:
        if (isset($_GET['details_wrong'])) { ?>
        <div class="alert alert-danger" style="color: red;">
            <strong>Error: </strong> Details not recognised - Please try again
        </div>
        <?php } ?>
        <?php if (isset($_GET['pw_expired'])) { ?>
            <div class="alert alert-danger" style="color: red;">
                <strong>Error: </strong> Your password has expired, please <a href="login.php?password=lost">click here</a> to reset your password
            </div>
        <?php } ?>
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
        <?php if (!isset($_POST['register'])) { ?>
            <p><a href="account_login.php?password=lost">Lost password?</a></p>
        <?php } endif; ?>

    </div>
</div>
<?php require 'footer.php'; ?>
