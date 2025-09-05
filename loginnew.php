<?php
include ("connect.php");

session_start();

if (isset($_POST['Username'])) {
  
  // USERS \\
  $users_logins = array(
    'master' => array('4','D1g1talK3y5'),
    //'admin' => array('31','***********')
  );

  // GET LOGIN LOG FILE \\
  $h = "0";$hm = $h * 60;$ms = $hm * 60;
  $gmdate = gmdate("d/m/Y g:i:s A", time()-($ms));

  $file = SERVER.'master/logs/users.txt';
  $log_update = file_get_contents($file);

  // GET LOGIN ATTEMPT \\
  $_username = $_POST['Username'];
  $_password = $_POST['Password'];
  if ($_username == '') {$_username = '***';}

  // NOT MASTER CHECK \\
  if (in_array($_username, array_keys($users_logins)))
  {
  	$query = database("SELECT * FROM users WHERE username='$_username' LIMIT 1");
    list($client_id,$client_pw) = $users_logins[$_username];   
  }
  else
  {
    // LOG UNSUCCESSFUL ATTEMPT \\
    $log_update .= "\n"."Login Status: Unsuccessful-USER NOT FOUND/Username | Date/Time: ".$gmdate." | IP: ". $_SERVER['REMOTE_ADDR'];
    if ($_SERVER['HTTP_HOST'] <> 'localhost') {file_put_contents($file, $log_update);}
    header('Location: control_panel.php');
    exit();
  }

  // MASTER IP CHECK \\
  if ($_username == 'master' && $_SERVER['HTTP_HOST'] <> 'localhost' && $_SERVER['REMOTE_ADDR'] <> '88.97.47.220')
  {
    $pass = false;

    if (file_exists('loginoverride.php'))
    {
      include 'loginoverride.php';
      if ($override_datetime_str > strtotime('now'))
      {
        $pass = true;
      }
    }
    
    if ($pass == false)
    {
      $loginid = $client_id;
      $login_attempt = true;
      include 'tracker.php';
      exit('<h1>Access Denied</h1>');
    }
  }

  // LOGIN ATTEMPT CHECKER/TRACKER \\
  if ($client_id > 0)
  {
    $loginid = $client_id;
    $login_attempt = true;
    include 'tracker.php';
    if (!isset($response['pass']))
    {
      $response = array();
      $curtime = date('H:i:s');
      if ($curtime < '07:00:00' OR $curtime > '22:00:00')
      {
        $pass = false;
      }
    }
    else
    {
      $pass = $response['pass'];
    }
    if ($pass == false)
    {
      exit('<h1>Access Denied</h1>');
    }
  }  

  // SEE IF PASSWORD MATCHES \\
  if ($_password == $client_pw)
  {
  	$_SESSION["username"] = $query["username"];
    $_SESSION["permissions"] = $query["permission_level"];
    $_SESSION["loggedin"] = "true";
    $_SESSION['loginid'] = $client_id;

    // LOG SUCCESSFUL ATTEMPT \\
    $log_update .= "\n"."Login Status: Successful | Date/Time: ".$gmdate." | Userid: ".$client_id." | IP: ". $_SERVER['REMOTE_ADDR'];
    if ($_SERVER['HTTP_HOST'] <> 'localhost') {file_put_contents($file, $log_update);}

    // LOGIN TO DASHBOARD \\
    header('Location: admin.php');
    exit();
  }
  else
  {
    // LOG UNSUCCESSFUL ATTEMPT \\
    $log_update .= "\n"."Login Status: Unsuccessful/Password | Date/Time: ".$gmdate." | Userid: ".$client_id." | IP: ". $_SERVER['REMOTE_ADDR'];
    if ($_SERVER['HTTP_HOST'] <> 'localhost') {file_put_contents($file, $log_update);}

    // REFRESH TO LOGIN FORM \\
    header('Location: control_panel.php?loginerror=true');
    exit();
  }  
}
?>