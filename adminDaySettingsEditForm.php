<?php require 'headerAdmin.php'; ?>

<?php require 'navigationAdmin.php'; ?>

<?php
$date = $_GET['date'];
$showdate = date('d F Y',strtotime($date));
$day = date('N', strtotime($date));

$result['Lunch'] = database("SELECT * FROM day_settings WHERE `date`='$date' AND meal='Lunch' LIMIT 1");
$result['Dinner'] = database("SELECT * FROM day_settings WHERE `date`='$date' AND meal='Dinner' LIMIT 1");

switch ($day) {
    case 6:
    case 7:
        if ($result['Lunch']['open_time'] == '') {$result['Lunch']['open_time'] = '12:00';}
        else {$result['Lunch']['open_time'] = date('H:i',strtotime($date.' '.$result['Lunch']['open_time']));}
        if ($result['Lunch']['close_time'] == '') {$result['Lunch']['close_time'] = '12:30';}
        else {$result['Lunch']['close_time'] = date('H:i',strtotime($date.' '.$result['Lunch']['close_time']));}
        break;
    
    default:
        if ($result['Lunch']['open_time'] == '') {$result['Lunch']['open_time'] = '12:00';}
        else {$result['Lunch']['open_time'] = date('H:i',strtotime($date.' '.$result['Lunch']['open_time']));}
        if ($result['Lunch']['close_time'] == '') {$result['Lunch']['close_time'] = '14:00';}
        else {$result['Lunch']['close_time'] = date('H:i',strtotime($date.' '.$result['Lunch']['close_time']));}
        break;
}
if ($result['Dinner']['open_time'] == '') {$result['Dinner']['open_time'] = '18:30';}
else {$result['Dinner']['open_time'] = date('H:i',strtotime($date.' '.$result['Dinner']['open_time']));}
if ($result['Dinner']['close_time'] == '') {$result['Dinner']['close_time'] = '22:00';}
else {$result['Dinner']['close_time'] = date('H:i',strtotime($date.' '.$result['Dinner']['close_time']));}
?>

<div class="background--white max-width--800 margin-auto--left margin-auto--right padding-top--large padding-bottom--normal padding-left--normal padding-right--normal">

    <h1>Edit Day Settings | <?php echo $showdate; ?></h1>

    <h2>Edit the settings of the day selected</h2>

    <form action="settings.php?settings_tab=1" method="POST">
        <input type="hidden" name="update_daycovers" value="<?php echo $date; ?>" />
        <?php if (isset($_GET['returnurl'])) { ?>
            <input type="hidden" name="returnurl" value="<?php echo $_GET['returnurl']; ?>" />
        <?php } ?>
        
        <div class="grid">
            <div class="col col-1-2">
                <h2 class="margin-bottom--small">Lunch</h2>
                <h3 style="font-weight: bold;text-decoration: underline;">Opening &amp; Closing Times</h3>
                <div class="grid">
                    <div class="col col-1-2">
                        <label for="OpenTime">Open</label>
                        <select name="day_settings[Lunch][open_time]">
                        <?php $cur_time = '12:00';
                        while ($cur_time <= '15:00') 
                        {
                            echo '<option value="'.$cur_time.'"'.($result['Lunch']['open_time'] == $cur_time?' selected="selected"':'').'>'.$cur_time.'</option>';
                            $strdate = strtotime(date("H:i", strtotime($cur_time)) . " +15 mins");
                            $cur_time=date('H:i',$strdate);
                            if ($cur_time == '00:00')
                            {
                                break;
                            }
                        }
                        ?>
                        </select>
                    </div>
                    <div class="col col-1-2">
                        <label for="CloseTime">Close</label>
                        <select name="day_settings[Lunch][close_time]">
                        <?php $cur_time = '12:00';
                        while ($cur_time <= '15:00') 
                        {
                            echo '<option value="'.$cur_time.'"'.($result['Lunch']['close_time'] == $cur_time?' selected="selected"':'').'>'.$cur_time.'</option>';
                            $strdate = strtotime(date("H:i", strtotime($cur_time)) . " +15 mins");
                            $cur_time=date('H:i',$strdate);
                            if ($cur_time == '00:00')
                            {
                                break;
                            }
                        }
                        ?>
                        </select>                        
                    </div>
                </div>
                <?php /*
                <h3>Covers</h3>
                <div class="grid">            
                    <div class="col col-1-2">
                        <label for="MinimumCovers">Minimum</label>
                        <input type="number" id="MinimumCovers" name="day_settings[Lunch][minimum_covers]" min="1" max="99" value="<?php echo $result['Lunch']['minimum_covers']; ?>" required>
                    </div>
                    <div class="col col-1-2">
                        <label for="MaximumCovers">Maximum</label>
                        <input type="number" id="MaximumCovers" name="day_settings[Lunch][maximum_covers]" min="1" max="99" value="<?php echo $result['Lunch']['maximum_covers']; ?>" required>
                    </div>
                </div>
                */ ?>
                <h3 style="font-weight: bold;text-decoration: underline;">Disable Bookings</h3>
                <div class="grid">            
                    <div class="col col-1-2" style="vertical-align: top;">
                        <label for="MinimumCovers">Disable sessions bookings</label>
                    </div>
                    <div class="col col-1-2">
                        <label class="switch vertical-align--middle">
                            <input type="checkbox" name="day_settings[Lunch][admin_booking]" value="N" <?php if ($result['Lunch']['admin_booking'] == 'N') {echo 'checked="checked"';} ?> />
                            <div class="slider"></div>
                        </label>
                    </div>
                </div>                
                <h3 style="font-weight: bold;text-decoration: underline;">Online Availability</h3>
                <div class="grid">            
                    <div class="col col-1-2">
                        <label for="MinimumCovers">Downstairs</label>
                        <label class="switch vertical-align--middle">
                            <input type="checkbox" name="day_settings[Lunch][availability_downstairs]" value="Y" <?php if ($result['Lunch']['availability_downstairs'] == 'Y') {echo 'checked="checked"';} ?> />
                            <div class="slider"></div>
                        </label>
                    </div>
                    <div class="col col-1-2">
                        <label for="MaximumCovers">Upstairs</label>
                        <label class="switch vertical-align--middle">
                            <input type="checkbox" name="day_settings[Lunch][availability_upstairs]" value="Y" <?php if ($result['Lunch']['availability_upstairs'] == 'Y') {echo 'checked="checked"';} ?> />
                            <div class="slider"></div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="col col-1-2">
                <h2 class="margin-bottom--small">Dinner</h2>
                <h3 style="font-weight: bold;text-decoration: underline;">Opening &amp; Closing Times</h3>
                <div class="grid">
                    <div class="col col-1-2">
                        <label for="OpenTime">Open</label>
                        <select name="day_settings[Dinner][open_time]">
                        <?php $cur_time = '17:00';
                        while ($cur_time <= '22:00') 
                        {
                            echo '<option value="'.$cur_time.'"'.($result['Dinner']['open_time'] == $cur_time?' selected="selected"':'').'>'.$cur_time.'</option>';
                            $strdate = strtotime(date("H:i", strtotime($cur_time)) . " +15 mins");
                            $cur_time=date('H:i',$strdate);
                            if ($cur_time == '00:00')
                            {
                                break;
                            }
                        }
                        ?>
                        </select>
                    </div>
                    <div class="col col-1-2">
                        <label for="CloseTime">Close</label>
                        <select name="day_settings[Dinner][close_time]">
                        <?php $cur_time = '17:00';
                        while ($cur_time <= '22:00') 
                        {
                            echo '<option value="'.$cur_time.'"'.($result['Dinner']['close_time'] == $cur_time?' selected="selected"':'').'>'.$cur_time.'</option>';
                            $strdate = strtotime(date("H:i", strtotime($cur_time)) . " +15 mins");
                            $cur_time=date('H:i',$strdate);
                            if ($cur_time == '00:00')
                            {
                                break;
                            }
                        }
                        ?>
                        </select>                         
                    </div>
                </div>
                <?php /*
                <h3>Covers</h3>
                <div class="grid">            
                    <div class="col col-1-2">
                        <label for="MinimumCovers">Minimum</label>
                        <input type="number" id="MinimumCovers" name="day_settings[Dinner][minimum_covers]" min="1" max="99" value="<?php echo $result['Dinner']['minimum_covers']; ?>" required>
                    </div>
                    <div class="col col-1-2">
                        <label for="MaximumCovers">Maximum</label>
                        <input type="number" id="MaximumCovers" name="day_settings[Dinner][maximum_covers]" min="1" max="99" value="<?php echo $result['Dinner']['maximum_covers']; ?>" required>
                    </div>
                </div>
                */ ?>
                <h3 style="font-weight: bold;text-decoration: underline;">Disable Bookings</h3>
                <div class="grid">            
                    <div class="col col-1-2" style="vertical-align: top;">
                        <label for="MinimumCovers">Disable sessions bookings</label>
                    </div>
                    <div class="col col-1-2">
                        <label class="switch vertical-align--middle">
                            <input type="checkbox" name="day_settings[Dinner][admin_booking]" value="N" <?php if ($result['Dinner']['admin_booking'] == 'N') {echo 'checked="checked"';} ?> />
                            <div class="slider"></div>
                        </label>
                    </div>
                </div>
                <h3 style="font-weight: bold;text-decoration: underline;">Online Availability</h3>
                <div class="grid">            
                    <div class="col col-1-2">
                        <label for="MinimumCovers">Downstairs</label>
                        <label class="switch vertical-align--middle">
                            <input type="checkbox" name="day_settings[Dinner][availability_downstairs]" value="Y" <?php if ($result['Dinner']['availability_downstairs'] == 'Y') {echo 'checked="checked"';} ?> />
                            <div class="slider"></div>
                        </label>
                    </div>
                    <div class="col col-1-2">
                        <label for="MaximumCovers">Upstairs</label>
                        <label class="switch vertical-align--middle">
                            <input type="checkbox" name="day_settings[Dinner][availability_upstairs]" value="Y" <?php if ($result['Dinner']['availability_upstairs'] == 'Y') {echo 'checked="checked"';} ?> />
                            <div class="slider"></div>
                        </label>
                    </div>
                </div>                
            </div>
        </div>

        <div class="input input--full">
            <input type="submit" class="front-end-submit" value="Update Settings" class="text--white"></input>
            <hr style="background-color: rgba(220, 220, 220, 1);" />
        </div>
    </form>

    <h2 style="text-align: center;">Close Availability</h2>
    <form style="text-align: center;" action="settings.php?settings_tab=1" method="POST">
        <input type="hidden" name="close_daycovers" value="<?php echo $date; ?>" />
        <?php if (isset($_GET['returnurl'])) { ?>
            <input type="hidden" name="returnurl" value="<?php echo $_GET['returnurl']; ?>" />
        <?php } ?>
        <div class="grid">            
            <div class="col col-1-2">
                <h3 style="font-weight: bold;text-decoration: underline;">Repeat for</h3>
                <select name="repeatfor">
                <?php 
                for ($i=1; i <=51; $i++) 
                {
                    if ($i==1) {
                        echo '<option value="'.$i.'" selected="selected"'.'>'.$i.' week</option>';
                    }else{
                        echo '<option value="'.$i.'"';
                        echo '>'.$i.' weeks</option>';
                        if ($i == '51')
                        {
                            break;
                        }
                    }    
                }
                ?>
                </select>
            </div>
        </div>
        <h4 style="color: red;font-weight: bold;font-size: 18px;text-align: center;">This will remove all availability from selected date up to the end of the weeks repeated<br />Please double check before clicking 'Close Now'</h4>
        <div class="input input--full">
            <input type="submit" class="front-end-submit" value="Close Now" class="text--white"></input>
            <hr style="background-color: rgba(220, 220, 220, 1);" />
        </div>        
    </form>
</div>

<?php require 'footer.php'; ?>
