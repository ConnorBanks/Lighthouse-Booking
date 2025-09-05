<?php
require_once 'includes/access.inc.php';

/*function get_session_id()
{
  GLOBAL $crypt;
  require SERVER.'admin/includes/package.php';
  return $crypt->hash(session_id(),BUSINESS.$PACKAGE['SITE_ID']);
}
//$sessionid = get_session_id();*/

function format_session($string)
{
  if (preg_match('/^[a-zA-Z0-9 .-’]+$/i', $string))
  {
    return $string;
  }
  else
  {
    return '';
  }
}

$accountid  = (is_integer($_SESSION['accountid'])?$_SESSION['accountid']:'');
$countryid  = (is_numeric($_SESSION['countryid'])?$_SESSION['countryid']:'');
$currency   = (is_integer($_SESSION['currency'])?$_SESSION['currency']:'');
$lastviewed = (is_integer($_SESSION['lastviewed'])?$_SESSION['lastviewed']:'');

$cust_name      = format_session($_SESSION['cust_name']);
$cust_address1  = format_session($_SESSION['cust_address1']);
$cust_address2  = format_session($_SESSION['cust_address2']);
$cust_town      = format_session($_SESSION['cust_town']);
$cust_county    = format_session($_SESSION['cust_county']);
$cust_country   = (is_numeric($_SESSION['cust_country'])?$_SESSION['cust_country']:0);
$cust_postcode  = format_session($_SESSION['cust_postcode']);
$cust_telephone = (ctype_digit(str_replace(' ', '', $_SESSION['cust_telephone']))?$_SESSION['cust_telephone']:'');
$cust_email     = (filter_var($_SESSION['cust_email'],FILTER_VALIDATE_EMAIL)?$_SESSION['cust_email']:'');

$recipient_name      = format_session($_SESSION['recipient_name']);
$recipient_address1  = format_session($_SESSION['recipient_address1']);
$recipient_address2  = format_session($_SESSION['recipient_address2']);
$recipient_town      = format_session($_SESSION['recipient_town']);
$recipient_county    = format_session($_SESSION['recipient_county']);
$recipient_postcode  = format_session($_SESSION['recipient_postcode']);
$recipient_country   = (is_numeric($_SESSION['recipient_country'])?$_SESSION['recipient_country']:0);
$recipient_telephone = (ctype_digit(str_replace(' ', '', $_SESSION['recipient_telephone']))?$_SESSION['recipient_telephone']:'');

$_payment     = (ctype_alpha($_SESSION['payment'])?$_SESSION['payment']:'');
$shipping     = (is_numeric($_SESSION['shipping'])?$_SESSION['shipping']:'');
$subtotal     = (is_numeric($_SESSION['subtotal'])?$_SESSION['subtotal']:'');
$amount       = (is_numeric($_SESSION['amount'])?$_SESSION['amount']:'');
$ordernumber  = (is_integer($_SESSION['ordernumber'])?$_SESSION['ordernumber']:'');
$ordered      = (ctype_alpha($_SESSION['ordered'])?$_SESSION['ordered']:'');

$selected_meal = $_SESSION['selected_meal'];


if (isset($_REQUEST['currency'])) 
{ 
  $currency = $_REQUEST['currency'];
  $_SESSION['currency'] = $currency;
}
if ($_SESSION['currency'] == ''){$_SESSION['currency'] = $currency= '1';}

if (isset($_REQUEST['addtobasket'])) 
{ 
  if ($accountid=='')
  {
    //Generate random pin consisting of 8 CAPITALS 
    $pin = "";  for ($pincount=0;$pincount<8;$pincount++) {$pin.=chr(mt_rand(65,90));}	
    $sql = "INSERT INTO shopaccounts SET 
	    pin='$pin',
      `date`=NOW(),
      sessionid='$sessionid'";
    $accountid = database($sql);
  	$_SESSION['accountid'] = (int) $accountid;
  }
  if ($cust_country=='')
  {
    $_SESSION['cust_country'] = $cust_country = 213; 
  }
  if ($countryid=='')
  {
    $_SESSION['recipient_country'] = $_SESSION['countryid'] = $countryid = 213;
  }

  // RESETTING SESSIONS FOR NEW ORDER \\
  if (isset($_SESSION['subtotal']) && $_SESSION['ordered'] == 'yes') {unset($_SESSION['subtotal']);}
  if (isset($_SESSION['shipping']) && $_SESSION['ordered'] == 'yes') {unset($_SESSION['shipping']);}
  if (isset($_SESSION['amount']) && $_SESSION['ordered'] == 'yes') {unset($_SESSION['amount']);}
  if (isset($_SESSION['ordernumber']) && $_SESSION['ordered'] == 'yes') {unset($_SESSION['ordernumber']);}
  if (isset($_SESSION['vendorcode']) && $_SESSION['ordered'] == 'yes') {unset($_SESSION['vendorcode']);}
}

if (isset($_REQUEST['country'])) 
{
	$countryid = $_REQUEST['country'];
	$_SESSION['recipient_country'] = $_SESSION['countryid'] = $countryid;  
}

if (isset($_REQUEST['viewcategory'])) 
{
	$lastviewed = $_REQUEST['viewcategory'];
	$_SESSION['lastviewed'] = $lastviewed;  
}

if (isset($_REQUEST['endsession'])) 
{
	session_destroy();
	echo 'Session has ended';
}

if (isset($_REQUEST['prev_day']))
{
  $cur_date = $_SESSION['cur_date'];

  $strdate = strtotime(date("Y-m-d", strtotime($cur_date)) . " -1 day");
  $cur_date = date('Y-m-d',$strdate);

  $_SESSION['cur_date'] = $cur_date;

  header('Location: '.$_SERVER['PHP_SELF']);
  exit();
}
elseif (isset($_REQUEST['next_day']))
{
  $cur_date = $_SESSION['cur_date'];

  $strdate = strtotime(date("Y-m-d", strtotime($cur_date)) . " +1 day");
  $cur_date = date('Y-m-d',$strdate);

  $_SESSION['cur_date'] = $cur_date;

  header('Location: '.$_SERVER['PHP_SELF']);
  exit();
}
elseif (isset($_REQUEST['today']))
{
  $_SESSION['cur_date'] = $cur_date = $today;

  header('Location: '.$_SERVER['PHP_SELF']);
  exit();
}
elseif (isset($_REQUEST['selected_date']))
{
  if ($_REQUEST['selected_date'] == '')
  {
      $_SESSION['cur_date'] = $cur_date = $_REQUEST['selected_date'] = '';
  }
  else
  {
    $_SESSION['cur_date'] = $cur_date = date('Y-m-d', strtotime(str_replace('/', '-', $_REQUEST['selected_date'])));
  } 
  header('Location: '.$_SERVER['PHP_SELF']);
  exit();
}
elseif (isset($_SESSION['cur_date']))
{
    $cur_date = $_SESSION['cur_date'];
}
else
{
    //$_SESSION['cur_date'] = $cur_date = $today;
}

if ($_REQUEST['selected_meal'])
{
  $_SESSION['selected_meal'] = $selected_meal = $_REQUEST['selected_meal'];

  header('Location: '.$_SERVER['PHP_SELF']);
  exit();
}
elseif (isset($_SESSION['selected_meal']))
{
  $selected_meal = $_SESSION['selected_meal'];
}
else
{
  if (date('H:i') > '14:00') {$selected_meal = 'Dinner';}
  else {$selected_meal = 'Lunch';}

  $_SESSION['selected_meal'] = $selected_meal;
}
?>