<?php session_start(); ?>
<?php 
    if (isset($_GET['admin'])) {$_SESSION['admin_access'] = true;} 
    //else {unset($_SESSION['admin_access']);}

    if (isset($_SESSION['loggedin']) && $indexpage==true && !isset($_SESSION['admin_access']) && !isset($_GET['useraccountid']))
    {
        session_destroy();
        header('Location: index.php');
    }
?>
<?php include 'connect.php'; ?>
<?php include 'phpmailer.php'; ?>
<?php 
if (basename($_SERVER['PHP_SELF'])=='bookingSubmit.php') 
{
    include 'bookingSubmitProcess.php';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Lighthouse Booking System</title>
        <link href="../style.css" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="images/favicon/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="images/favicon/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="images/favicon/manifest.json">
        <link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="images/favicon/favicon.ico">
        <meta name="msapplication-config" content="images/favicon/browserconfig.xml">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <meta name="format-detection" content="telephone=no" />
        <meta name="theme-color" content="#ffffff">

        <script src="../js/jquery-3.1.1.min.js"></script>
        <script src="../js/jquery-ui.min.js"></script>
        <script src="../js/source.min.js"></script>
    </head>
    <body>

    <?php
        $UKtoday = date("d-m-Y");
        $today = date("Y-m-d");
    ?>

    <?php if (!isset($_SESSION['username'])){
        $_SESSION['username'] = "";
    } ?>

    <?php if (!isset($_SESSION['permissions'])){
        $_SESSION['permissions'] = "";
    } ?>

    <?php if (!isset($_SESSION['loggedin'])){
        $_SESSION['loggedin'] = "";
    } ?>

    <?php if ($_SESSION["username"] && !$_SESSION['admin_access']) { ?>
        <a class="position--fixed position--bottom position--right background--primary padding--normal padding-top--tiny padding-bottom--tiny" href="admin.php">
        Currently logged in as <?php echo $_SESSION["username"]; ?>
        </a>

        <a class="position--fixed position--bottom position--left background--primary padding--normal padding-top--tiny padding-bottom--tiny" href="logout.php">
            Logout?
        </a>
        <?php
    } ?>
    
    <a class="position--fixed position--bottom position--right background--primary padding--normal padding-top--tiny padding-bottom--tiny" href="https://www.lighthouserestaurant.co.uk/booking-system-feedback/" target="_blank">
            Report Issue
    </a>
    
    <?php
    function table_data($table_id,$return_data)
    {
        $result = database("SELECT * FROM tables_single WHERE table_id='$table_id' LIMIT 1");
        return $result[$return_data];
    }

    $default_duration = 150;
    
    echo 'hello development server';
    ?>