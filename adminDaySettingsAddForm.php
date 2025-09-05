<?php require 'headerAdmin.php'; ?>

<?php require 'navigationAdmin.php'; ?>

<?php
$date = $_GET['date'];
$showdate = date('d F Y',strtotime($date));
$day = date('N', strtotime($date));

$result['Lunch'] = database("SELECT * FROM day_settings WHERE `date`='$date' AND meal='Lunch' LIMIT 1");
$result['Dinner'] = database("SELECT * FROM day_settings WHERE `date`='$date' AND meal='Dinner' LIMIT 1");

$result['Dinner']['open_time'] = '18:30';
$result['Dinner']['close_time'] = '22:00';
?>

<div class="background--white max-width--800 margin-auto--left margin-auto--right padding-top--large padding-bottom--normal padding-left--normal padding-right--normal">

    <h1>Add Day Settings | From <?php echo $showdate; ?></h1>
    <p>Add settings for one week and repeat week up to a year</p>

    <form action="settings.php?settings_tab=1" method="POST">
        <input type="hidden" name="add_daycovers" value="<?php echo $date; ?>" />
        <?php if (isset($_GET['returnurl'])) { ?>
            <input type="hidden" name="returnurl" value="<?php echo $_GET['returnurl']; ?>" />
        <?php } ?>

        <?php
        $curdate = $date;
        for ($i=1; $i <= 7; $i++) 
        { 
            $day_settings_count = count(database("SELECT * FROM day_settings WHERE `date` = '$curdate'"));
            if ($day_settings_count > 0) 
            {
                echo '<h3 style="color:red;text-align:center;">Unable to add availability from '.date('l, d M Y',strtotime($curdate)).'</h3>';
                $no_repeat = true;
                break;
            }
            echo '<h2><b>' . date('l',strtotime($curdate)) . '</b></h2>';

            switch ($day) {
                case 6:
                case 7:
                    $result['Lunch']['open_time'] = '12:00';
                    $result['Lunch']['close_time'] = '12:30';
                    break;
                
                default:
                    $result['Lunch']['open_time'] = '12:00';
                    $result['Lunch']['close_time'] = '14:00';
                    break;
            }
            ?>
            <div class="grid">
                <div class="col col-1-2">
                    <h3 class="margin-bottom--small">Lunch</h3>
                    <h3 style="font-weight: bold;text-decoration: underline;">Opening &amp; Closing Times</h3>
                    <div class="grid">
                        <div class="col col-1-2">
                            <label for="OpenTime">Open</label>
                            <select name="day_settings[<?php echo $day; ?>][Lunch][open_time]">
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
                            <select name="day_settings[<?php echo $day; ?>][Lunch][close_time]">
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
                    <h3 style="font-weight: bold;text-decoration: underline;">Disable Bookings</h3>
                    <div class="grid">            
                        <div class="col col-1-2" style="vertical-align: top;">
                            <label for="MinimumCovers">Disable sessions bookings</label>
                        </div>
                        <div class="col col-1-2">
                            <label class="switch vertical-align--middle">
                                <input type="checkbox" name="day_settings[<?php echo $day; ?>][Lunch][admin_booking]" value="N" <?php if ($result['Lunch']['admin_booking'] == 'N') {echo 'checked="checked"';} ?> />
                                <div class="slider"></div>
                            </label>
                        </div>
                    </div>                
                    <h3 style="font-weight: bold;text-decoration: underline;">Online Availability</h3>
                    <div class="grid">            
                        <div class="col col-1-2">
                            <label for="MinimumCovers">Downstairs</label>
                            <label class="switch vertical-align--middle">
                                <input type="checkbox" name="day_settings[<?php echo $day; ?>][Lunch][availability_downstairs]" value="Y" <?php if ($result['Lunch']['availability_downstairs'] == 'Y') {echo 'checked="checked"';} ?> />
                                <div class="slider"></div>
                            </label>
                        </div>
                        <div class="col col-1-2">
                            <label for="MaximumCovers">Upstairs</label>
                            <label class="switch vertical-align--middle">
                                <input type="checkbox" name="day_settings[<?php echo $day; ?>][Lunch][availability_upstairs]" value="Y" <?php if ($result['Lunch']['availability_upstairs'] == 'Y') {echo 'checked="checked"';} ?> />
                                <div class="slider"></div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col col-1-2">
                    <h3 class="margin-bottom--small">Dinner</h3>
                    <h3 style="font-weight: bold;text-decoration: underline;">Opening &amp; Closing Times</h3>
                    <div class="grid">
                        <div class="col col-1-2">
                            <label for="OpenTime">Open</label>
                            <select name="day_settings[<?php echo $day; ?>][Dinner][open_time]">
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
                            <select name="day_settings[<?php echo $day; ?>][Dinner][close_time]">
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
                    <h3 style="font-weight: bold;text-decoration: underline;">Disable Bookings</h3>
                    <div class="grid">            
                        <div class="col col-1-2" style="vertical-align: top;">
                            <label for="MinimumCovers">Disable sessions bookings</label>
                        </div>
                        <div class="col col-1-2">
                            <label class="switch vertical-align--middle">
                                <input type="checkbox" name="day_settings[<?php echo $day; ?>][Dinner][admin_booking]" value="N" <?php if ($result['Dinner']['admin_booking'] == 'N') {echo 'checked="checked"';} ?> />
                                <div class="slider"></div>
                            </label>
                        </div>
                    </div>
                    <h3 style="font-weight: bold;text-decoration: underline;">Online Availability</h3>
                    <div class="grid">            
                        <div class="col col-1-2">
                            <label for="MinimumCovers">Downstairs</label>
                            <label class="switch vertical-align--middle">
                                <input type="checkbox" name="day_settings[<?php echo $day; ?>][Dinner][availability_downstairs]" value="Y" <?php if ($result['Dinner']['availability_downstairs'] == 'Y') {echo 'checked="checked"';} ?> />
                                <div class="slider"></div>
                            </label>
                        </div>
                        <div class="col col-1-2">
                            <label for="MaximumCovers">Upstairs</label>
                            <label class="switch vertical-align--middle">
                                <input type="checkbox" name="day_settings[<?php echo $day; ?>][Dinner][availability_upstairs]" value="Y" <?php if ($result['Dinner']['availability_upstairs'] == 'Y') {echo 'checked="checked"';} ?> />
                                <div class="slider"></div>
                            </label>
                        </div>
                    </div>                
                </div>
                <hr style="background-color: #ddd;" />
            </div>
            <?php
            $curdate = date('Y-m-d',strtotime(date("Y-m-d", strtotime($curdate)) . " +1 days"));
            $day = date('N', strtotime($curdate));
        }

        if (!$no_repeat)
        {
            ?>
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
            <?php
        }
        else
        {
            ?>
            <input type="hidden" name="repeatfor" value="1" />
            <?php
        }
        ?>

        <div class="input input--full">
            <input type="submit" value="Update Settings" class="text--white"></input>
        </div>
    </form>

</div>

<?php require 'footer.php'; ?>
