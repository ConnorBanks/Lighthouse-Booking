<?php 
include_once("includes/beginnings.php");
include 'includes/secure.inc.php';

if (isset($_POST['selected_date'])) 
{ 
    if($_POST['selected_date'] == '') 
    {
         $searchsql = ''; 
    }
    else
    {
        $selected_date = date('Y-m-d',strtotime($_POST['selected_date'])); 
        $searchsql = "AND party_date='$selected_date'"; 
    }
}
else 
{ 
    $selected_date = ''; 
}

if (isset($_POST['selected_meal'])) 
{ $selected_meal = $_POST['selected_meal']; }
else { $selected_meal = 'Lunch'; }

if (isset($_POST['booking_id']))
{
    extract($_POST);
    $partydate = date('Y-m-d',strtotime(str_replace('/', '-', $_POST['partydate'])));
    $partytime = $_POST['partytime_'.strtolower($_POST['meal'])];
    $duration = $_POST['duration_'.strtolower($_POST['meal'])];
    if (strpos($_POST['table_numbers'], ',') OR is_numeric($_POST['table_numbers']))
    {
        $table_numbers = explode(',', trim($_POST['table_numbers']));
    }
    else
    {
        $table_numbers = $_POST['table_numbers'];
    }

    $sql = "UPDATE bookings SET 
        party_date='$partydate',
        party_time='$partytime',
        party_size='$partysize',
        meal='$meal',
        requests='$comments'
    WHERE booking_id='$booking_id'";
    database($sql);

    if (is_array($table_numbers) OR $table_numbers > '')
    {
        $location = '';
        database("DELETE FROM bookings_tables WHERE booking_id='$booking_id'");

        foreach ($table_numbers as $number) 
        {
            //$table_id = table_data($number,'table_id');
            if ($location == '') {$location = table_data($number,'location');}
            database("INSERT INTO bookings_tables SET booking_id='$booking_id', table_id='$number'");
        }        
        
        $sql = "UPDATE bookings SET 
            location='$location'
        WHERE booking_id='$booking_id' AND location<>'$location'";
        database($sql);
    }
    
    header('Location: index.php?selected_meal='.$meal.'&bookingamended');
    exit();
} 
?>
<!DOCTYPE html>
<html>
<head>
<?php include 'includes/meta.php'; ?>
<?php include 'includes/css.php'; ?>
<!--[if IE ]><meta http-equiv="X-UA-Compatible" content="IE=Edge"><![endif]-->
<!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if lt IE 9]>
    <script src="js/modernizr.custom.11889.js" type="text/javascript"></script>
    <![endif]-->
<!-- HTML5 Shiv events (end)-->
</head>

<body>
<div class="main">
    <?php include 'includes/navigation.php'; ?>
    <div class="container">
<style type="text/css">
    input[disabled],
    textarea[disabled],
    select[disabled] {
        background-color: #aaa;
        color: #fff
    }
</style>


<div class="background--grey-dark padding-top--normal padding-bottom--normal padding-left--normal padding-right--normal">

    <div class="padding--large max-width--1000 margin-auto--left margin-auto--right background--grey-dark text--white">
    <?php
        if (!isset($booking_id)) {$booking_id = $_GET['booking_id'];}
        $result = database("SELECT * FROM bookings WHERE booking_id='$booking_id' LIMIT 1");

        $cur_table_combi = array();
        $table_data = database("SELECT * FROM bookings_tables WHERE booking_id='$booking_id'");
        foreach ($table_data as $table_data_row) 
        {
            $cur_table_combi[] = $table_data_row['table_id'];
        }
        $cur_table_combi_str = implode(',', $cur_table_combi);
        ?>
        <p><br /><a href="<?php echo $_GET['returnurl']; ?>" class="btn btn-default" style="width: auto;">Back</a></p>
        <h1>Amend Booking <?php echo $result['booking_id']; ?></h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>" />
            <input type="hidden" name="duration_lunch" value="<?php echo $result['duration']; ?>" />
            <input type="hidden" name="duration_dinner" value="<?php echo $result['duration']; ?>" />
            <input type="hidden" name="table_numbers" value="" />
            <?php if (isset($_GET['returnurl'])) { ?><input type="hidden" name="returnurl" value="<?php echo $_GET['returnurl']; ?>" /><?php } ?>
            <div class="row">
                <div class="col-xs-6" style="vertical-align: top;">
                    <div class="form-group">
                        <label for="PartyDate">Date of Booking *</label>
                        <input class="form-control" type="text" id="AdminDateEdit" name="partydate" value="<?php echo date('l d F Y',strtotime($result['party_date'])); ?>" data-cur-date="<?php echo date('l d F Y',strtotime($result['party_date'])); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="Meal">Lunch or Dinner *</label>
                        <select class="form-control" id="Meal" name="meal" required>
                            <option value="Lunch" <?php if ($result['meal']=='Lunch') {echo 'selected="selected"';} ?>>Lunch</option>
                            <option value="Dinner" <?php if ($result['meal']=='Dinner') {echo 'selected="selected"';} ?>>Dinner</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="PartyTime">Time of Booking *</label>
                                <select class="form-control" name="partytime_lunch" style="<?php echo ($result['meal']=='Lunch'?'':'display: none'); ?>">
                                <?php
                                    $curtime = '12:00';
                                    while ($curtime <= '15:00') 
                                    {
                                        echo '<option value="'.$curtime.'"'.($curtime==date('H:i',strtotime($result['party_time']))?' selected="selected"':'').'>'.$curtime.'</option>';
                                        $strdate = strtotime(date("H:i", strtotime($curtime)) . " +15 mins");
                                        $curtime=date('H:i',$strdate);
                                    }
                                ?>
                                </select>
                                <select class="form-control" name="partytime_dinner" style="<?php echo ($result['meal']=='Dinner'?'':'display: none'); ?>">
                                <?php
                                    $curtime = '17:00';
                                    while ($curtime <= '22:00') 
                                    {
                                        echo '<option value="'.$curtime.'"'.($curtime==date('H:i',strtotime($result['party_time']))?' selected="selected"':'').'>'.$curtime.'</option>';
                                        $strdate = strtotime(date("H:i", strtotime($curtime)) . " +15 mins");
                                        $curtime=date('H:i',$strdate);
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <?php /*
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="Duration<?php echo $result['meal']; ?>">Duration *</label>
                                <select class="form-control" id="DurationLunch" name="duration_lunch" style="<?php echo ($result['meal']=='Lunch'?'':'display: none'); ?>">
                                <?php 
                                    for ($i=15; $i <= (60*3); $i=$i+15) 
                                    { 
                                        echo '<option value="'.$i.'"'.($result['duration']==$i?' selected="selected"':'').'>'.$i.' mins</option>';
                                    }
                                ?>
                                </select>
                                <select class="form-control" id="DurationDinner" name="duration_dinner" style="<?php echo ($result['meal']=='Dinner'?'':'display: none'); ?>">
                                <?php 
                                    for ($i=15; $i <= (60*4); $i=$i+15) 
                                    { 
                                        echo '<option value="'.$i.'"'.($result['duration']==$i?' selected="selected"':'').'>'.$i.' mins</option>';
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        */ ?>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="PartySize">Party Size *</label>
                                <input class="form-control" type="text" id="PartySize" name="partysize" value="<?php echo $result['party_size']; ?>" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" style="vertical-align: top;">
                    <label for="CustomerComments">Requests</label>
                    <textarea class="form-control" id="CustomerComments" name="comments" class="rezisable--none" rows="11" style="max-height: 180px;margin-bottom: 0px;"><?php echo $result['requests']; ?></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <span id="checking" style="text-align:center;color: red;width: 100%;display: block;"></span>
                    <br />
                    <input class="btn btn-default" type="submit" value="Update Booking"></input>
                </div>
            </div>
        </form>
        </div>
    </div>
    </div>
</div>
</body>
<?php include 'includes/script.php'; ?>
<script type="text/javascript">
    $('select[name="meal"]').on('change', function(event) {
        if ($('select[name="meal"] option:selected').val() == 'Lunch')
        {
            //$('select[name="duration_lunch"]').show().val($('select[name="duration_dinner"]').val());
            $('select[name="partytime_lunch"]').show().find('option[value="12:00"]').prop('selected', 'selected');
        }
        else
        {
            //$('select[name="duration_lunch"]').hide();
            $('select[name="partytime_lunch"]').hide();
        }

        if ($('select[name="meal"] option:selected').val() == 'Dinner')
        {
            //$('select[name="duration_dinner"]').show().val($('select[name="duration_lunch"]').val());
            $('select[name="partytime_dinner"]').show().find('option[value="17:00"]').prop('selected', 'selected');
        }
        else
        {
            //$('select[name="duration_dinner"]').hide();   
            $('select[name="partytime_dinner"]').hide();
        }
    });
    $('input[name="partydate"], select[name="meal"], select[name="duration_lunch"], select[name="duration_dinner"], select[name="partytime_lunch"], select[name="partytime_dinner"]').on('change', function(event) {
        event.preventDefault();
        check_tables();
    });


    $('input[name="partysize"]').on('change, keyup, keydown', function(event) {
        check_tables();
    });

    //check_tables();

    function check_tables()
    {
        setTimeout(function() {
            data_array = {
                'booking_id': $('input[name="booking_id"]').val(),
                'date': $('input[name="partydate"]').val(),
                'meal': $('select[name="meal"] option:selected').val(),
                'time': $('select[name="partytime_'+$('select[name="meal"] option:selected').val().toLowerCase()+'"]').val(),
                //'duration': $('select[name="duration_'+$('select[name="meal"] option:selected').val().toLowerCase()+'"] option:selected').val(),
                'duration': $('input[name="duration_'+$('select[name="meal"] option:selected').val().toLowerCase()+'"]').val(),
                'size': $('input[name="partysize"]').val()
            };    
            console.log(data_array);

            if (
                data_array.booking_id > 0 && 
                data_array.date > '' && 
                data_array.size > '' && 
                data_array.size != 0
            ) {
                //console.log('start_ajax');
                $.ajax({
                    url: '../ajax.php?request=check_tables_myaccount',
                    type: 'POST',
                    data: data_array,
                    beforeSend: function() {
                        $('#checking').html('Checking Availability ...');
                    },
                    complete: function() {
                        $('#checking').html('')
                    }
                })
                .done(function(data) {
                    if (data == '')
                    {
                        $('input[name="table_numbers"]').val('');
                        $('input[type="submit"]').attr('disabled', 'true');
                        setTimeout(function() {
                            $('#checking').html('No Availability, please try again');
                        }, 250);
                    }
                    else
                    {
                        $('input[name="table_numbers"]').val(data);
                        $('input[type="submit"]:disabled').removeAttr('disabled');
                    }

                })
                .fail(function() {
                    console.log("error");
                })
            }
        }, 250);
    }
</script>
</html>