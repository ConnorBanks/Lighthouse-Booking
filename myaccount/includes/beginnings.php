<?php 
  if (basename($_SERVER['PHP_SELF']) <> 'sitemap.php')
  {
  header('Content-Type: text/html; charset=UTF-8');
  }

  date_default_timezone_set('Europe/London');

  $script_tz = date_default_timezone_get();

  if (strcmp($script_tz, ini_get('date.timezone')) && $loginid == 30 && isset($_GET['timezonecheck']))
  {
    echo 'Script timezone differs from ini-set timezone. ('.ini_get('date.timezone').')';
  }

  $today = date('Y-m-d');
  $now = date('Y-m-d H:i:s');

  // Load settings
  include('../globalsettings.php');

  // Connect to the database
  include '../dbfunction.php';
  
  // Connect to the sessions
  include 'includes/sessions.php';

  // Load functions (including shipping.php)
  include 'includes/functions.php'; 
  
  //require_once 'admin/includes/phpmailer.php';


?>