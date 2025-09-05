<style type="text/css">
body {
    padding-bottom: 30px;
}
.diarybtn {
    display: block;
    padding: 0px 5px;
    font-size: 14px;
    font-weight: bold;
    background: #faae18;
    text-decoration: none;
    width: 100%;
    text-align: center;
    margin: 0;
    line-height: 50px;
    color: #fff;
}
.rowgroup {
    float: left;
    width: 100%;
}
.mobileonly {
    display: none;
}

.diary h2 {
    text-align: center;
    display: block;
    text-transform: uppercase;
}

.cellheader {
    height: 30px!important;
}

.weekday {
    float: left;
    width: 13%;
    padding: 5px;
    margin: 2px 2px 0px 0px;
    height: 160px;
    background-color: #EEEEEE;
}

.weekendday {
    float: left;
    width: 16%;
    padding: 5px;
    margin: 1px;
    background-color: #DDDDDD;
    height: 160px;
}

.days {
    width: 50%;
    display: inline-block;
    font-weight: bolder;
    padding: 1px;
    margin-bottom: 0px;
    color: #000;
}

.days a {
    color: #000;
}

.today {
    float: left;
    width: 13%;
    padding: 5px;
    margin: 2px 2px 0px 0px;
    background-color: #faae18;
    color: #fff;
    height: 160px;
}

.datechange {
    margin-bottom: 5px;
    margin-top: 0px;
    width: 50%;
    display: inline-block;
}
.datechange select,.datechange input {
    display: inline-block;
    width: 32%!important;
}
.datechange form {
    width: 100%;
}
.showdate {
    float: left;
    width: 100%;
}
.showdate a {
    display: inline-block;
    width: 22%;
    width: calc(25% - 10px);
    float: left;
    padding: 0px;
}
.showdate a:first-child {
    margin-right: 10px;
}
.showdate a:last-child {
    margin-left: 10px;
}
.showdate h2 {
    display: inline-block;
    width: 49%;
    text-align: center;
    float: left;
}

.edit_days {
    background-color: #363636;
}
.edit_days:hover {
    background-color: #5c5c5c;
}

.view_week {
  width: 50%;
  display: inline-block;
  text-align: right;
  font-size: 12px;
  vertical-align: top;
}
.view_week a {
  color: #5c5c5c!important;
}
@media screen and (max-width: 767px) {
    .nomobile {
        display: none!important;
    }
    .mobileonly {
        display: block!important;
    }
    .rowgroup > div {
        width: 100%;
        display: block;
        height: auto;
    }
    .datechange {
        width: 100%;
    }
    
    h1 {
        width: 100%!important;
        padding-top: 20px;
    }
}

</style>
<div style="padding-bottom: 30px;float: left;width:100%;">
<?php
if (isset($_POST['add_daycovers'])):
    $date = $_POST['add_daycovers'];
    $repeatfor = $_POST['repeatfor'];

    $curdate = $date;
    for ($i=1; $i <= $repeatfor; $i++) 
    { 
        //echo '<p>Week '.$i.'</p>';
        $day_count = 1;
        while ($day_count <= 7) 
        {
            $day = date('N', strtotime($curdate));

            if (count($_POST['day_settings'][$day]) > 0)
            {
                foreach ($_POST['day_settings'][$day] as $meal => $day_settings) 
                {
                    if ($day_settings['availability_downstairs'] == '') {$day_settings['availability_downstairs'] = 'N';}
                    if ($day_settings['availability_upstairs'] == '') {$day_settings['availability_upstairs'] = 'N';}
                    if ($day_settings['admin_booking'] == '') {$day_settings['admin_booking'] = 'Y';}

                    $sql = "INSERT INTO day_settings SET 
                        `date`='$curdate',
                        meal='$meal',
                        minimum_covers='".$day_settings['minimum_covers']."',
                        maximum_covers='".$day_settings['maximum_covers']."',
                        open_time='".$day_settings['open_time']."',
                        close_time='".$day_settings['close_time']."',
                        availability_downstairs='".$day_settings['availability_downstairs']."',
                        availability_upstairs='".$day_settings['availability_upstairs']."',
                        admin_booking='".$day_settings['admin_booking']."'";
                    //echo '<p>'.$sql.'</p>';
                    database($sql);
                }
            }

            $curdate = date('Y-m-d',strtotime(date("Y-m-d", strtotime($curdate)) . " +1 days"));
            $day_count++;
        }
    }
?>
<?php 
elseif (isset($_POST['update_daycovers'])):
    $date = $_POST['update_daycovers'];

    database("DELETE FROM day_settings WHERE `date` = '$date'");

    foreach ($_POST['day_settings'] as $meal => $day_settings) 
    {
        if ($day_settings['availability_downstairs'] == '') {$day_settings['availability_downstairs'] = 'N';}
        if ($day_settings['availability_upstairs'] == '') {$day_settings['availability_upstairs'] = 'N';}
        if ($day_settings['admin_booking'] == '') {$day_settings['admin_booking'] = 'Y';}
        $sql = "INSERT INTO day_settings SET 
            `date`='$date',
            meal='$meal',
            minimum_covers='".$day_settings['minimum_covers']."',
            maximum_covers='".$day_settings['maximum_covers']."',
            open_time='".$day_settings['open_time']."',
            close_time='".$day_settings['close_time']."',
            availability_downstairs='".$day_settings['availability_downstairs']."',
            availability_upstairs='".$day_settings['availability_upstairs']."',
            admin_booking='".$day_settings['admin_booking']."'";
        //echo '<p>'.$sql.'</p>';
        database($sql);
    }
    echo '<p style="color:green;">Settings Successfully Updated</p>';

    if (isset($_POST['returnurl']))
    {
        echo '<a href="'.$_POST['returnurl'].'" style="color:black;">Return to previous page</a>';
        $nomore = 'true';
    }
?>
<?php
elseif (isset($_POST['close_daycovers'])):
    $date = $_POST['close_daycovers'];
    $repeatfor = $_POST['repeatfor'];

    $curdate = $date;
    for ($i=1; $i <= $repeatfor; $i++) 
    { 
        //echo '<p>Week '.$i.'</p>';
        $day_count = 1;
        while ($day_count <= 7) 
        {
            $sql = "DELETE FROM day_settings WHERE `date`='$curdate'";
            //echo '<p>'.$sql.'</p>';
            database($sql);

            $curdate = date('Y-m-d',strtotime(date("Y-m-d", strtotime($curdate)) . " +1 days"));
            $day_count++;
        }
    }
?>
<?php else: endif;
if ($nomore<>'true') 
{
  if (isset($_GET['viewweek']))
  {
    $start_date = $_GET['viewweek'];
    $end_date = date('Y-m-d',strtotime(date("Y-m-d", strtotime($start_date)) . " +6 days"));

    $showdate_start = date('d F Y',strtotime($start_date));
    $showdate_end = date('d F Y',strtotime($end_date));

    $pweek = date('Y-m-d',strtotime(date("Y-m-d", strtotime($start_date)) . " -7 days"));
    $nweek = date('Y-m-d',strtotime(date("Y-m-d", strtotime($end_date)) . " +1 days"));

    $days = 1;
    $nmbrdays = 7;
    
    echo '        <div class="showdate">'."\n";
    echo '        <a class="diarybtn" href="'.$_SERVER['PHP_SELF'].'?viewweek='.$pweek.'">< Previous week</a>'."\n";
    echo '        <h2>'.$showdate_start.' - '.$showdate_end.'</h2>'."\n";
    echo '        <a class="diarybtn" href="'.$_SERVER['PHP_SELF'].'?viewweek='.$nweek.'">Next week ></a>'."\n";
    echo '        </div>'."\n"; 
    echo '        <div class="clearfix"></div>'."\n"; 
    echo '        <hr />'."\n";   

    echo '        <div class="nomobile">'."\n";
    echo '          <div class="weekday cellheader"><strong>Monday</strong></div>'."\n";
    echo '          <div class="weekday cellheader"><strong>Tuesday</strong></div>'."\n";
    echo '          <div class="weekday cellheader"><strong>Wednesday</strong>y</div>'."\n";
    echo '          <div class="weekday cellheader"><strong>Thursday</strong></div>'."\n";
    echo '          <div class="weekday cellheader"><strong>Friday</strong></div>'."\n";
    echo '          <div class="weekendday cellheader"><strong>Saturday</strong></div>'."\n";
    echo '          <div class="weekendday cellheader"><strong>Sunday</strong></div>'."\n";
    echo '        </div>'."\n";
    echo '        <div class="clearfix"></div>'."\n"; 

    $counter = '1';
    $curdate = $start_date;
    while ($days <= $nmbrdays) 
    {
      for ($cols=0;$cols<7;$cols++) 
      {
        if ($cols == 0) {echo '<span class="rowgroup">';}
        if ($days < '1') 
        {
          echo '<div class="nomobile">'."\n";
        }
        else
        {
          $strdate= strtotime("$curdate");
          $weekday= date('l', $strdate);
          $day_week_num = date('N', $strdate);
        }

        /*if ($curdate == $today)     {echo '<div class="today">'."\n"; }
        else*/if ($cols > 4)          {echo '<div class="weekendday">'."\n"; }
        else                    {echo '<div class="weekday">'."\n"; }   

        if ($days <=  $nmbrdays)
        {
          if ($days < '1') 
          {
            echo '&nbsp;';
          }
          else
          {
            // MAIN WORK AREA
            //echo $curdate;

            $result['Lunch'] = database("SELECT * FROM day_settings WHERE `date`='$curdate' AND meal='Lunch' LIMIT 1");
            $result['Dinner'] = database("SELECT * FROM day_settings WHERE `date`='$curdate' AND meal='Dinner' LIMIT 1");

            echo '<table>';
              echo '<tr>';
                echo '<td></td>';
                echo '<td style="text-align:center;">Min</td>';
                echo '<td style="text-align:center;">Max</td>';
              echo '</tr>';
              echo '<tr>';
                echo '<td>Lunch</td>';
                echo '<td style="text-align:center;">'.($result['Lunch']['minimum_covers'] > 0?$result['Lunch']['minimum_covers']:0).'</td>';
                echo '<td style="text-align:center;">'.($result['Lunch']['maximum_covers'] > 0?$result['Lunch']['maximum_covers']:0).'</td>';
              echo '</tr>';
              echo '<tr>';
                echo '<td>Dinner</td>';
                echo '<td style="text-align:center;">'.($result['Dinner']['minimum_covers'] > 0?$result['Dinner']['minimum_covers']:0).'</td>';
                echo '<td style="text-align:center;">'.($result['Dinner']['maximum_covers'] > 0?$result['Dinner']['maximum_covers']:0).'</td>';
              echo '</tr>';
            echo '</table>';

            if ($curdate >= $today) 
            {
                $day_settings_count = count(database("SELECT * FROM day_settings WHERE `date` = '$curdate'"));
                echo '<a class="edit_days diarybtn" href="adminDaySettings'.($day_settings_count>0?'Edit':'Add').'Form.php?date='.$curdate.'">Edit Covers</a>';
            }            
            // END MAIN WORK AREA
          } // end if days < 1
        } // end if ($days <=  $nmbrdays)
        echo '</div>'."\n";

        if ($days < 1) { echo '</div>'."\n"; }
        $days++;
        if ($cols == 6) {echo '</span>';}
        $curdate = date('Y-m-d',strtotime(date("Y-m-d", strtotime($curdate)) . " +1 days"));
      } // end for loop
      echo '<div class="clearfix"></div>'."\n"; 
      $counter++; if ($counter == 100) exit();
    }
  }
  else
  {
    // Obtaining infor about the first day of the current month
    $makedate= mktime(22, 0, 0, $month,  1,  $year);
    $writemonth= date('F',$makedate);   
    $nmbrdays = date('t',$makedate);
    $fday =date('N',$makedate);

    $nextmonth= mktime(22, 0, 0, $month+1,  1,  $year);
        $nmonth =date('m',$nextmonth);
        $nyear =date('Y',$nextmonth);
    $nextmonth= mktime(22, 0, 0, $month-1,  1,  $year);
        $pmonth =date('m',$nextmonth);
        $pyear =date('Y',$nextmonth);
    $days= 2 - $fday;


    /*
    echo '<ul>';
    echo '<li>Day '.$day.'</li>';
    echo '<li>Month '.$month.'</li>';
    echo '<li>Year '.$year.'</li>';
    echo '<li>F-Day '.$fday.'</li>';
    echo '<li>Nbr Days '.$nmbrdays.'</li>';
    echo '<li>Days '.$days.'</li>';
    echo '</ul>';
    */
    echo '        <h1 style="display:inline-block;width:49%">Day Settings</h1>'."\n";
    echo '        <div class="datechange">'."\n";
    echo '        <form action="'.$_SERVER['PHP_SELF'].'" method="get">'."\n";
    echo '        <select name="month">'."\n";
    echo '          <option value="01"'; if ($month == '01') { echo ' selected="selected"';} echo '>January</option>'."\n";
    echo '          <option value="02"'; if ($month == '02') { echo ' selected="selected"';} echo '>February</option>'."\n";
    echo '          <option value="03"'; if ($month == '03') { echo ' selected="selected"';} echo '>March</option>'."\n";
    echo '          <option value="04"'; if ($month == '04') { echo ' selected="selected"';} echo '>April</option>'."\n";
    echo '          <option value="05"'; if ($month == '05') { echo ' selected="selected"';} echo '>May</option>'."\n";
    echo '          <option value="06"'; if ($month == '06') { echo ' selected="selected"';} echo '>June</option>'."\n"; 
    echo '          <option value="07"'; if ($month == '07') { echo ' selected="selected"';} echo '>July</option>'."\n";
    echo '          <option value="08"'; if ($month == '08') { echo ' selected="selected"';} echo '>August</option>'."\n";
    echo '          <option value="09"'; if ($month == '09') { echo ' selected="selected"';} echo '>September</option>'."\n";
    echo '          <option value="10"'; if ($month == '10') { echo ' selected="selected"';} echo '>October</option>'."\n";
    echo '          <option value="11"'; if ($month == '11') { echo ' selected="selected"';} echo '>November</option>'."\n";
    echo '          <option value="12"'; if ($month == '12') { echo ' selected="selected"';} echo '>December</option>'."\n"; 
    echo '        </select>'."\n"; 
    echo '        <select name="year">';  
    $dropyear = '2017'; 
    $end_i = ((date('Y') - $dropyear)+2); 
    for ($i=0;$i<$end_i;$i++)
    {
        echo '<option'; if ($year == $dropyear) { echo ' selected="selected"';} echo '>'.$dropyear.'</option>'; $dropyear=$dropyear+1;
    }
    echo '        </select>'."\n"; 
    echo '        <input class="diarybtn" type="submit" value="Go" />'."\n"; 
    echo '        </div>'."\n"; 
    echo '        <div class="clearfix"></div>';
    echo '        <div class="showdate">'."\n";
    echo '        <a class="diarybtn" href="'.$_SERVER['PHP_SELF'].'?year='.$pyear.'&amp;month='.$pmonth.'"><<span class="nomobile"> Previous month</span></a>'."\n";
    echo '        <h2>'.$writemonth.' '.$year.'</h2>'."\n";
    echo '        <a class="diarybtn" href="'.$_SERVER['PHP_SELF'].'?year='.$nyear.'&amp;month='.$nmonth.'"><span class="nomobile">Next month </span>></a>'."\n";
    echo '        </div>'."\n"; 
    echo '        <div class="clearfix"></div>'."\n"; 
    echo '        <hr />'."\n"; 

    echo '        <div class="nomobile">'."\n";
    echo '          <div class="weekday cellheader"><strong>Monday</strong></div>'."\n";
    echo '          <div class="weekday cellheader"><strong>Tuesday</strong></div>'."\n";
    echo '          <div class="weekday cellheader"><strong>Wednesda</strong>y</div>'."\n";
    echo '          <div class="weekday cellheader"><strong>Thursday</strong></div>'."\n";
    echo '          <div class="weekday cellheader"><strong>Friday</strong></div>'."\n";
    echo '          <div class="weekendday cellheader"><strong>Saturday</strong></div>'."\n";
    echo '          <div class="weekendday cellheader"><strong>Sunday</strong></div>'."\n";
    echo '        </div>'."\n";
    echo '        <div class="clearfix"></div>'."\n"; 

    $counter = '1';
    while ($days <= $nmbrdays) 
    {
      for ($cols=0;$cols<7;$cols++) 
      {
        if ($cols == 0) {echo '<span class="rowgroup">';}
        if ($days < '1') 
        {
          echo '<div class="nomobile">'."\n";
        }
        else
        {
          if ($days < '10'){$days ='0'.$days;}
          $curdate = $year.'-'.$month.'-'.$days;
          $strdate= strtotime("$curdate");
          $weekday= date('l', $strdate);
          $day_week_num = date('N', $strdate);
        }

        if ($curdate == $today)     {echo '<div class="today">'."\n"; }
        elseif ($cols > 4)          {echo '<div class="weekendday">'."\n"; }
        else                    {echo '<div class="weekday">'."\n"; }   

        if ($days <=  $nmbrdays)
        {
          if ($days < '1') 
          {
            echo '&nbsp;';
          }
          else
          {
            echo '  <div class="nomobile">'."\n";
            echo '    <div class="days">'.$days.'</div>'.($day_week_num==1?'<div class="view_week"><a href="'.$_SERVER['PHP_SELF'].'?viewweek='.$curdate.'">View Week</a></div>':'')."\n";
            echo '    </div>'."\n";
            echo '  <div class="mobileonly">'."\n";
            echo '    <div class="days">'.$weekday.' '.$days.'</div>'."\n";
            echo '    </div>'."\n";
            echo '<div class="clearfix"></div>'."\n"; 


            // MAIN WORK AREA
            if ($curdate >= $today) 
            {
                $day_settings_count = count(database("SELECT * FROM day_settings WHERE `date` = '$curdate'"));
                echo '<a class="edit_days diarybtn" href="adminDaySettings'.($day_settings_count>0?'Edit':'Add').'Form.php?date='.$curdate.'">'.($day_settings_count>0?'View / Edit':'Add').'</a>';
            }

            // END MAIN WORK AREA
          } // end if days < 1
        } // end if ($days <=  $nmbrdays)
        echo '</div>'."\n";

        if ($days < 1) { echo '</div>'."\n"; }
        $days++;
        if ($cols == 6) {echo '</span>';}
      } // end for loop
      echo '<div class="clearfix"></div>'."\n"; 
      $counter++; if ($counter == 100) exit();
    }
  }
?>
<?php }?>
</div>