<?php define('settings_pw', 'access4lighthouse'); ?>
<?php require 'headerAdmin.php'; ?>

<?php require 'navigationAdmin.php'; ?>

<?php 
if (isset($_POST['access_settings']))
{
    if ($_POST['settings_pw'] == settings_pw)
    {
        $_SESSION['settings_access'] = true;
    }
    else
    {
        $settings_login_error = true;
    }
}

if (!isset($_SESSION['settings_tab'])) {$_SESSION['settings_tab'] = $settings_tab = 1;}
elseif (isset($_REQUEST['settings_tab'])) {$_SESSION['settings_tab'] = $settings_tab = $_REQUEST['settings_tab'];}
else {$settings_tab = $_SESSION['settings_tab'];}
?>

<div class="background--white max-width--1000 margin-auto--left margin-auto--right padding-top--normal padding-bottom--normal padding-left--normal padding-right--normal">
<?php if ($_SESSION['settings_access'] == false) { ?>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="padding: 0px ;text-align: center;">
        <input type="hidden" name="access_settings" value="true" />
        <label>Login to access settings</label>
        <?php if ($settings_login_error) { echo '<p style="color:red">Incorrect password, please try again</p>';} ?>
        <div style="padding: 5px;max-width: 100%;width: 400px;margin: 0 auto;">
            <input style="text-align: center;" type="password" name="settings_pw" />
        </div>
        <div style="padding: 5px;max-width: 100%;width: 400px;margin: 0 auto;">
            <input type="submit" value="Login" />
        </div>
    </form>
<?php } else { ?>
    <div>
        <ul class="margin-bottom--large">
            <li class="tab"><a style="padding: 10px 10px;" href="<?php echo $_SERVER['PHP_SELF']; ?>?settings_tab=1">Day Settings</a></li>
            <li class="tab"><a style="padding: 10px 10px;" href="<?php echo $_SERVER['PHP_SELF']; ?>?settings_tab=2">Seating Availability</a></li>
            <?php /*<li class="tab"><a style="padding: 10px 10px;" href="<?php echo $_SERVER['PHP_SELF']; ?>?settings_tab=3">Table Settings</a></li>*/ ?>
            <li class="tab"><a style="padding: 10px 10px;" href="<?php echo $_SERVER['PHP_SELF']; ?>?settings_tab=4">Time Slot Covers</a></li>
            <li class="tab"><a style="padding: 10px 10px;" href="<?php echo $_SERVER['PHP_SELF']; ?>?settings_tab=7">Time Slot Durations</a></li>
            <li class="tab"><a style="padding: 10px 10px;" href="<?php echo $_SERVER['PHP_SELF']; ?>?settings_tab=5">Table Numbers</a></li>
            <li class="tab"><a style="padding: 10px 10px;" href="<?php echo $_SERVER['PHP_SELF']; ?>?settings_tab=6">Combination Tables</a></li>
        </ul>
        <div class="tabs-<?php echo $settings_tab; ?>">
        <?php
        switch ($settings_tab) {
            case '1':
                require 'pagesSettings/daySettings.php';
                break;
            case '2':
                require 'pagesSettings/onlineAvailability.php';
                break;
            case '3':
                require 'pagesSettings/tableSettings.php';
                break;
            case '4':
                require 'pagesSettings/coverSettings.php';
                break;
            case '5':
                require 'pagesSettings/tableNumberSettings.php';
                break;
            case '6':
                require 'pagesSettings/combinationSettings.php';
                break;
            case '7':
                require 'pagesSettings/durationSettings.php';
                break;
            
            /*default:
                # code...
                break;*/
        }
        ?>
        </div>
    </div>

    <?php /*
    <div id="add-table" class="padding--large padding-top--none background--grey text--white" title="Install Table">

        <p class="padding-top--normal">Please select which table you want to add, select more than one to make these a linked table.</p>

        <form action="adminTableAdd.php" method="POST">
            <h4>Downstairs</h4>
            <?php
            for($i = 1; $i <=  19; $i++)
            {
                echo '<div class="display--inline-block width--auto margin-bottom--small">';
                echo '<label class="display--inline-block width--auto padding-right--small margin-left--small" for="table_' . $i . '">' . $i . '</label>';
                echo '<input type="checkbox" class="padding-right--small margin-bottom--small" id="table_' . $i . '" name="table_' . $i . '">';
                echo '</div>';
            }
            ?>

            <h4>Upstairs</h4>
            <?php
            for($i = 20; $i <=  34; $i++)
            {
                echo '<div class="display--inline-block width--auto margin-bottom--small">';
                echo '<label class="display--inline-block width--auto padding-right--small margin-left--small" for="table_' . $i . '">' . $i . '</label>';
                echo '<input type="checkbox" class="padding-right--small margin-bottom--small" id="table_' . $i . '" name="table_' . $i . '">';
                echo '</div>';
            }
            ?>

            <div class="grid">
                <div class="col col-1-2">
                    <label for="MinimumCovers">Minimum Covers</label>
                    <input type="number" id="MinimumCovers" name="MinimumCovers" min="1" max="99" required>
                </div>
                <div class="col col-1-2">
                    <label for="MaximumCovers">Maximum Covers</label>
                    <input type="number" id="MaximumCovers" name="MaximumCovers" min="1" max="99" required>
                </div>
            </div>
            <div class="input input--full">
                <input type="submit" value="Install Table into System" class="text--white"></input>
            </div>
        </form>
    </div>
    */ ?>
    <script type="text/javascript" src="js/jquery.sortable_sortorder.js"></script>
<?php } ?>
<?php require 'footer.php'; ?>