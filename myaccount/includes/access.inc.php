<?php
      require_once '../master/classes/CMS/Component/Encryption/class.proCrypt.php';
session_start();
	$login = $_SESSION['login'];
  $useraccountid = (is_numeric($_SESSION['useraccountid'])?$_SESSION['useraccountid']:'');
  $accountname = (ctype_alpha(str_replace(' ', '', $_SESSION['accountname']))?$_SESSION['accountname']:'');


	// Process login attempt
	if (isset($_POST['login']['username'])) 
	{
  		// MODULE_ID = ID of admin module \\
  		$MODULE_ID = 'profile_locks';


  		$_username=$_POST['login']['username'];  
    		if ($_username=='') {$_username= 'kdjfwhjfwpoedkhfw';}
    		else{$_username = substr($_username,0,50);}
  		$_password=$_POST['login']['password'];
    		$_password = substr($_password,0,25);

  		$useraccountid = 0;
  		$results = database("SELECT * FROM useraccounts ORDER BY useraccountid");
  		foreach ($results as $result)
  		{
    		$db_username = $crypt->decrypt($result['username']);
    		if ($_username == $db_username)
    		{
      			$useraccountid = (int) $result['useraccountid'];
      			$salt = $crypt->decrypt($result['pin']);
      			$username = $db_username;
      			break;
    		}
  		}
  
  		$passwords = pw_file_get('../master/'.$MODULE_ID.'.txt');

      if (isset($_POST['checkoutlogin']))
    {
      $redirect_success = 'checkout.php?order=1';
      $redirect_details_wrong = 'checkout.php?order=1&';
    }
    else
    {
      $redirect_success = 'index.php';
      $redirect_details_wrong = '../account_login.php?';
    }
      
  		if ($useraccountid > 0 && $crypt->hash($_password,$salt) == $passwords[$useraccountid])
  		{
		    $result = database("SELECT * FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1");
		    $useraccountid = (int) $result['useraccountid'];
		    $accountname = $result['name'];
		    $name = $result['name'];
		    $address1 = $result['address1'];
		    $address2 = $result['address2'];
		    $town = $result['town'];
		    $county = $result['county'];
		    $postcode = $result['postcode'];
		    $country = $result['country'];
		    $telephone = $result['telephone'];
		    $email = $result['email'];

		    $_SESSION['login'] = $login = TRUE;
		    $_SESSION['useraccountid'] = $useraccountid;
		    $_SESSION['accountname'] = $accountname;
		    $_SESSION['cust_name'] = $name;
		    $_SESSION['cust_address1'] = $address1;
		    $_SESSION['cust_address2'] = $address2;
		    $_SESSION['cust_town'] = $town;
		    $_SESSION['cust_county'] = $county;
		    $_SESSION['cust_postcode'] = $postcode;
		    $_SESSION['cust_country'] = $country;
		    $_SESSION['cust_telephone'] = $telephone;
		    $_SESSION['cust_email'] = $email;

		    $sql = "UPDATE useraccounts SET
        lastlogin=CURDATE()
      WHERE useraccountid = '$useraccountid'";
      database($sql);

      header('Location: '.$redirect_success);
      exit();
    }
    else 
    {
      header('Location: '.$redirect_details_wrong.'details_wrong=true');
      exit();
    }
  	}

  	if (isset($_GET['logout']))
  {
    $accountid  = $_SESSION['accountid'];
        
    session_destroy();
    
    header('Location: ../account_login.php');
    exit();
  }
?>