<?php session_start(); //print_r($_SESSION); ?>
<?php if ($_SESSION['loggedin'] == ''){
    header("Location: control_panel.php");
    die();
} 

include 'connect.php'; 
include 'phpmailer.php';

    $today = date("Y-m-d");
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
            $_SESSION['cur_date'] = $cur_date = $_REQUEST['selected_date'] = date('Y-m-d');
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
        $_SESSION['cur_date'] = $cur_date = $today;
    }

    if ($_REQUEST['month'])
    {
        $_SESSION['month'] = $month = $_REQUEST['month'];
    }
    elseif (isset($_SESSION['month']))
    {
        $month = $_SESSION['month'];
    }
    else
    {
        $_SESSION['month'] = $month = date('m');
    }

    if ($_REQUEST['year'])
    {
        $_SESSION['year'] = $year = $_REQUEST['year'];
    }
    elseif (isset($_SESSION['year']))
    {
        $year = $_SESSION['year'];
    }
    else
    {
        $_SESSION['year'] = $year = date('Y');
    }

    if ($_REQUEST['selected_meal'])
    {
        $_SESSION['selected_meal'] = $selected_meal = $_REQUEST['selected_meal'];

        header('Location: '.$_SERVER['PHP_SELF']);
    }
    elseif (isset($_SESSION['selected_meal']))
    {
        $selected_meal = $_SESSION['selected_meal'];
    }
    else
    {
        if (date('H:i') > '14:00') {$selected_meal = 'dinner';}
        else {$selected_meal = 'lunch';}

        $_SESSION['selected_meal'] = $selected_meal;
    }

    if (isset($_POST['admin_availability_downstairs']))
    {
        $availability_downstairs = $_POST['availability_downstairs'];
        if ($availability_downstairs == '') {$availability_downstairs = 'N';}
        $date = $_POST['date'];
        $meal = $_POST['meal'];

        database("UPDATE day_settings SET availability_downstairs='$availability_downstairs' WHERE `date`='$date' AND meal='$meal'");

        header('Location: '.$_SERVER['PHP_SELF']);
    }

    if (isset($_POST['admin_availability_upstairs']))
    {
        $availability_upstairs = $_POST['availability_upstairs'];
        if ($availability_upstairs == '') {$availability_upstairs = 'N';}
        $date = $_POST['date'];
        $meal = $_POST['meal'];

        database("UPDATE day_settings SET availability_upstairs='$availability_upstairs' WHERE `date`='$date' AND meal='$meal'");

        header('Location: '.$_SERVER['PHP_SELF']);
    }

?>
<?php if ($_SESSION['loggedin'] && $hide_logout <> true) {?>
    <a class="position--fixed position--bottom position--left background--primary padding--normal padding-top--tiny padding-bottom--tiny" href="logout.php">
        Logout?
    </a>
    <?php
} 

function table_data($id_ref,$return_data)
{
    if ($return_data == 'table_id')
    {
        $result = database("SELECT * FROM tables_single WHERE table_number='$id_ref' LIMIT 1");
    }
    else
    {
        $result = database("SELECT * FROM tables_single WHERE table_id='$id_ref' LIMIT 1");
    }
    return $result[$return_data];
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Lighthouse Booking System | Admin Area</title>
        <!--<link href="styles/jquery.ui.timepicker.css" rel="stylesheet" type="text/css">-->
        <link href="style.css" rel="stylesheet" type="text/css">
        <link href="styles/jquery.mCustomScrollbar.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="images/favicon/manifest.json">
        <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="images/favicon/favicon.ico">
        <meta name="msapplication-config" content="images/favicon/browserconfig.xml">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="theme-color" content="#ffffff">
        <script src="js/jquery-3.1.1.min.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        <script src="js/jquery.ui.touch-punch.min.js"></script>
        <script src="js/jquery.mousewheel.min.js"></script>
        <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
        <script src="js/jquery.fixedheadertable.min.js"></script>
        <script src="js/jquery-migrate-1.2.1.js"></script>
        <!--<script src="js/jquery.ui.timepicker.js"></script>-->
        <script src="js/source.min.js"></script>
    </head>
    <body>
        <p>Development Server</p>