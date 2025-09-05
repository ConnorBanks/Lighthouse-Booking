<?php require 'headerAdmin.php'; ?>

<?php
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
            first_name='$firstname',
            last_name='$lastname',
            telephone='$telephonenumber',
            email_address='$emailaddress',
            requests='$comments',
            party_date='$partydate',
            party_time='$partytime',
            party_size='$partysize',
            meal='$meal',
            locked='$locked',
            clear_tables='$clear_tables',
            staffcomments='$staffcomments',
            staffname='$staffname',
            duration='$duration'
        WHERE booking_id='$booking_id'";
        database($sql);

        if (is_array($table_numbers) OR $table_numbers > '')
        {
            $location = '';
            if ($table_numbers <> 'custom' && count($table_numbers) > 0 && is_array($table_numbers) && trim($_POST['table_numbers']) > '')
            {
                database("DELETE FROM bookings_tables WHERE booking_id='$booking_id'");

                foreach ($table_numbers as $number) 
                {
                    //$table_id = table_data($number,'table_id');
                    if ($location == '') {$location = table_data($number,'location');}
                    database("INSERT INTO bookings_tables SET booking_id='$booking_id', table_id='$number'");
                }
            }
            elseif ($table_numbers == 'custom')
            {
                $table_numbers_custom = explode(',', trim($_POST['table_numbers_custom']));

                database("DELETE FROM bookings_tables WHERE booking_id='$booking_id'");

                foreach ($table_numbers_custom as $number) 
                {
                    $table_id = table_data($number,'table_id');
                    if ($location == '') {$location = table_data($table_id,'location');}
                    database("INSERT INTO bookings_tables SET booking_id='$booking_id', table_id='$table_id'");
                }
            }
            elseif ($table_numbers == 'unallocate')
            {
                database("DELETE FROM bookings_tables WHERE booking_id='$booking_id'");
            }

            $sql = "UPDATE bookings SET 
                location='$location'
            WHERE booking_id='$booking_id' AND location<>'$location'";
            database($sql);
        }

        if (strpos($_POST['returnurl'], '?'))
        {
            header('Location: '.$_POST['returnurl']);
            exit();
        }
        elseif ($_POST['returnurl'] == 'adminSearch.php')
        {
            header('Location: '.$_POST['returnurl'].'?bookingamended#tabs-3');
            exit();
        }
        else
        {
            header('Location: '.$_POST['returnurl'].'?bookingamended');
            exit();
        }
    }
    elseif (isset($_GET['delete_booking']))
    {
        $booking_id = $_GET['delete_booking'];
    }
    elseif (isset($_GET['delete_booking_now']))
    {
        $booking_id = $_GET['delete_booking_now'];
        database("DELETE FROM bookings WHERE booking_id='$booking_id'");
        database("DELETE FROM bookings_tables WHERE booking_id='$booking_id'");

        if ($_GET['returnurl'] == 'adminSearch.php')
        {
            header('Location: '.$_GET['returnurl'].'?bookingdeleted#tabs-3');
        }
        else
        {
            header('Location: '.$_GET['returnurl'].'?bookingdeleted');
        }
    }
?>

    <?php require 'navigationAdmin.php'; ?>

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
        <a href="<?php echo $_GET['returnurl']; ?>" style="float: right;">
            <img src="images/cancel.png" style="width: 10px" /> Back
        </a>
        <a class='icon icon--small icon--profile' style="float: right; background-color: white; margin-right: 10px;" target="_blank" href="profile.php?viewprofile=<?php echo $result['useraccountid'] ?>"></a>
        <h1>Amend Booking <?php echo $result['booking_id']; ?></h1>
        <?php if (isset($_GET['delete_booking'])) { ?>
            <h2>Are you sure you want to cancel this booking?</h2>
        <?php } ?>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>" />
            <?php if (isset($_GET['returnurl'])) { ?><input type="hidden" name="returnurl" value="<?php echo $_GET['returnurl']; ?>" /><?php } ?>
            <?php if (!isset($_GET['delete_booking'])) { ?>
            <div class="grid">
                <div class="col col-1-2" style="vertical-align: top;">
                    <div class="grid">
                        <div class="col col-1-2" style="vertical-align: top;">
                            <label>Lock Booking</label>
                            <label class="switch vertical-align--middle">
                                <input type="checkbox" name="locked" value="Y" <?php if ($result['locked'] == 'Y') {echo 'checked="checked"';} ?>>
                                <div class="slider"></div>
                            </label>
                            <!--<label>Clear Table(s)</label>
                            <label class="switch vertical-align--middle">
                                <input type="checkbox" name="clear_tables" value="Y" <?php //if ($result['clear_tables'] == 'Y') {echo 'checked="checked"';} ?>>
                                <div class="slider"></div>
                            </label>-->
                        </div>
                        <div class="col col-1-2" style="vertical-align: top;">
                            
                        </div>
                    </div> 
                </div>
            </div>      
            <?php } ?>
            <div class="grid">
                <div class="col col-1-2" style="vertical-align: top;">
                    <label for="FirstName">First Name</label>
                    <input type="text" id="FirstName" name="firstname" value="<?php echo $result['first_name']; ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?>>
                    <label for="LastName">Last Name *</label>
                    <input type="text" id="LastName" name="lastname" value="<?php echo $result['last_name']; ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?> required>
                    <label for="TelephoneNumber">Mobile Number *</label>
                    <input type="text" id="TelephoneNumber" name="telephonenumber" value="<?php echo $result['telephone']; ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?> required>
                    <label for="EmailAddress">Email Address</label>
                    <input type="email" id="EmailAddress" name="emailaddress" value="<?php echo $result['email_address']; ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?>>
                    <?php /*
                    <label for="StaffComments">Staff Comments</label>
                    <textarea id="StaffComments" name="StaffComments" class="rezisable--none" rows=6 required></textarea>
                    <label for="StaffName">Who Booked?</label>
                    <input type="text" id="StaffName" name="StaffName" required>
                    */ ?>
                </div>
                <div class="col col-1-2" style="vertical-align: top;">
                    <label for="PartyDate">Date of Booking *</label>
                    <input type="text" id="AdminDateEdit" name="partydate" value="<?php echo date('l d F Y',strtotime($result['party_date'])); ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?> data-cur-date="<?php echo date('l d F Y',strtotime($result['party_date'])); ?>" required>
                    <label for="Meal">Lunch or Dinner *</label>
                    <select id="Meal" name="meal" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?> required>
                        <option value="Lunch" <?php if ($result['meal']=='Lunch') {echo 'selected="selected"';} ?>>Lunch</option>
                        <option value="Dinner" <?php if ($result['meal']=='Dinner') {echo 'selected="selected"';} ?>>Dinner</option>
                    </select>
                    <div class="grid">
                        <div class="col col-1-2">
                            <label for="PartyTime">Time of Booking *</label>
                            <select name="partytime_lunch" style="<?php echo ($result['meal']=='Lunch'?'':'display: none'); ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?>>
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
                            <select name="partytime_dinner" style="<?php echo ($result['meal']=='Dinner'?'':'display: none'); ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?>>
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
                            <?php /*<input type="text" id="PartyTime" name="partytime" value="<?php echo date('H:i',strtotime($result['party_time'])); ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?> required>*/ ?>
                        </div>
                        <div class="col col-1-2">
                            <label for="Duration<?php echo $result['meal']; ?>">Duration *</label>
                            <select id="DurationLunch" name="duration_lunch" style="<?php echo ($result['meal']=='Lunch'?'':'display: none'); ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?>>
                            <?php 
                                for ($i=15; $i <= (60*3); $i=$i+15) 
                                { 
                                    echo '<option value="'.$i.'"'.($result['duration']==$i?' selected="selected"':'').'>'.$i.' mins</option>';
                                }
                            ?>
                            </select>
                            <select id="DurationDinner" name="duration_dinner" style="<?php echo ($result['meal']=='Dinner'?'':'display: none'); ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?>>
                            <?php 
                                for ($i=15; $i <= (60*4); $i=$i+15) 
                                { 
                                    echo '<option value="'.$i.'"'.($result['duration']==$i?' selected="selected"':'').'>'.$i.' mins</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="grid">
                        <div class="col" style="display: inline-block;width: 25%;padding-right: 10px;">
                            <label for="PartySize">Party Size *</label>
                            <input type="text" id="PartySize" name="partysize" value="<?php echo $result['party_size']; ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?> required>
                        </div>
                        <div class="col" style="display: inline-block;width: 75%;padding-left: 10px;">
                            <label for="TableNumbers">Table Numbers <?php /*<small>(Only if booking is changed)</small>*/ ?></label>
                            <!--<input type="text" id="TableNumbers" name="table_numbers" value="" disabled required>-->
                            <select id="TableNumbers" name="table_numbers" data-cur-combi="<?php echo $cur_table_combi_str; ?>" disabled></select>
                        </div>
                        <div class="col" style="width: 25%; padding-right: 10px; display: none;">
                            <input name="table_numbers_custom" type="text">
                        </div>
                    </div>
                </div>
                <div class="col col-1-2" style="vertical-align: top;">
                    <label for="CustomerComments">Staff Comments</label>
                    <textarea id="CustomerComments" name="staffcomments" class="rezisable--none" rows=6 <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?>><?php echo $result['staffcomments']; ?></textarea>
                    <label for="CustomerComments">Staff Name *</label>
                    <input type="text" name="staffname" value="<?php echo $result['staffname']; ?>" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?> required />
                </div>
                <div class="col col-1-2" style="vertical-align: top;">
                    <label for="CustomerComments">Customer Comments</label>
                    <textarea id="CustomerComments" name="comments" class="rezisable--none" rows="11" style="max-height: 220px;margin-bottom: 0px;" <?php if (isset($_GET['delete_booking'])) { ?> disabled <?php } ?>><?php echo $result['requests']; ?></textarea>
                </div>
            </div>
            <?php if (!isset($_GET['delete_booking'])) { ?>
                <div class="input input--full">
                    <input type="submit" value="Update Booking" class="text--white"></input>
                </div>
            <?php } ?>
        </form>
        <?php if (isset($_GET['delete_booking'])) { ?>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?booking_id=<?php echo $booking_id; ?>&returnurl=<?php echo $_GET['returnurl']; ?>">
                <button class="text--white padding-top--small padding-bottom--small">No</button>
            </a>
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete_booking_now=<?php echo $booking_id; ?>&returnurl=<?php echo $_GET['returnurl']; ?>">
                <button class="text--white padding-top--small padding-bottom--small" style="background-color: red;">Yes</button>
            </a>
        <?php } else { ?>        
            <a href="<?php echo $_SERVER['PHP_SELF']; ?>?delete_booking=<?php echo $booking_id; ?>&returnurl=<?php echo $_GET['returnurl']; ?>">
                <button class="text--white padding-top--small padding-bottom--small" style="background-color: red;">Delete Booking</button>
            </a>
            <?php
            $email = $result['email_address'];
            $past_results = database("SELECT * FROM bookings WHERE email_address='$email'");
        
            if (count($past_results) == 0) 
            { 
                ?>
                <h4 class="display--block text-align--center">No Past Visits</h4>
                <?php 
            } 
            else 
            { 
                ?>
                <h4 id="toggle_past_visits" class="display--block text-align--center" style="cursor: pointer;">View Past Visits</h4>
                <table id="past_visits" class="margin-bottom--normal background--white text--black padding--small" style="display: none;">
                    <tr>
                        <th class="padding-top--small padding-bottom--small padding-left--small">Date of Booking</th>
                        <th class="padding-top--small padding-bottom--small">Comments</th>
                        <th class="padding-top--small padding-bottom--small padding-right--small">Staff Comments</th>
                    </tr>
                    <tbody>
                    <?php
                        foreach ($past_results as $result) 
                        {
                            $date = date('d-m-Y',strtotime($result['party_date']));
                            ?>
                            <tr>
                                <td class="padding-left--small"><?php echo $date; ?></td>
                                <td><?php echo $result['requests']; ?></td>
                                <td class="padding-right--small"><?php echo $result['staffcomments']; ?></td>
                            </tr>
                            <?php 
                        }
                    ?>
                    </tbody>
                </table>
                <?php 
            } 
        } 
        ?>
        </div>
    </div>

    <script type="text/javascript">
        $('#toggle_past_visits').on('click', function(event) {
           $('#past_visits').toggle(); 
        });

        $('select:disabled option').css('color', '#fff');

        $('select[name="meal"]').on('change', function(event) {
            if ($('select[name="meal"] option:selected').val() == 'Lunch')
            {
                $('select[name="duration_lunch"]').show().val($('select[name="duration_dinner"]').val());
                $('select[name="partytime_lunch"]').show().find('option[value="12:00"]').prop('selected', 'selected');
            }
            else
            {
                $('select[name="duration_lunch"]').hide();
                $('select[name="partytime_lunch"]').hide();
            }

            if ($('select[name="meal"] option:selected').val() == 'Dinner')
            {
                $('select[name="duration_dinner"]').show().val($('select[name="duration_lunch"]').val());
                $('select[name="partytime_dinner"]').show().find('option[value="17:00"]').prop('selected', 'selected');
            }
            else
            {
                $('select[name="duration_dinner"]').hide();   
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

        check_tables();

        function check_tables()
        {
            setTimeout(function() {
                data_array = {
                    'booking_id': $('input[name="booking_id"]').val(),
                    'date': $('input[name="partydate"]').val(),
                    'meal': $('select[name="meal"] option:selected').val(),
                    'time': $('select[name="partytime_'+$('select[name="meal"] option:selected').val().toLowerCase()+'"]').val(),
                    'duration': $('select[name="duration_'+$('select[name="meal"] option:selected').val().toLowerCase()+'"] option:selected').val(),
                    'size': $('input[name="partysize"]').val()
                };    
                console.log(data_array);

                if (
                    data_array.booking_id > 0 && 
                    data_array.date > '' && 
                    data_array.size > '' && 
                    data_array.size != 0
                ) {
                    console.log('start_ajax');
                    $.ajax({
                        url: 'ajax.php?request=check_tables',
                        type: 'POST',
                        data: data_array,
                        beforeSend: function() {
                            $('input[name="table_numbers"]').val('Checking Availability ...');
                        },
                        /*complete: function() {
                            $('input[name="table_numbers"]').val('')
                        } */                       
                    })
                    .done(function(data) {
                        if (data == 'No Tables Available')
                        {
                            $('select[name="table_numbers"]').html('<option value="">'+data+'</option>').removeAttr('disabled').css('border-color', '');
                            $('select[name="table_numbers"] option:first').prop("selectedIndex", 0).prop('selected','selected');
                            $('input[type="submit"]').attr('disabled', 'true');
                        }
                        else
                        {
                            $('select[name="table_numbers"]').html(data).removeAttr('disabled').css('border-color', '');
                            $('select[name="table_numbers"] option:first').prop("selectedIndex", 0).prop('selected','selected');
                            $('input[type="submit"]:disabled').removeAttr('disabled');

                            //console.log($('select[name="table_numbers"]').find('option[value="'+$('select[name="table_numbers"]').data('cur-combi')+'"]').length);
                            if ($('input[name="partydate"]').val() != $('input[name="partydate"]').data('cur-date') && $('select[name="table_numbers"]').find('option[value="'+$('select[name="table_numbers"]').data('cur-combi')+'"]').length == 0)
                            {
                                $('select[name="table_numbers"] option:first').html('Current table not available').parent().css('border-color', 'red');
                                $('input[type="submit"]').attr('disabled', 'true');
                            }
                            else
                            {
                                //$('select[name="table_numbers"] option:first').html('Please select below').parent().css('border-color', '');
                                $('select[name="table_numbers"] option:first').parent().css('border-color', '');
                                $('input[type="submit"]:disabled').removeAttr('disabled');
                            }

                            $('select[name="table_numbers"]').on('change', function(event) {
                                var selected_val = $(this).val();
                                
                                if (selected_val > '')
                                {
                                    $('input[type="submit"]:disabled').removeAttr('disabled');
                                }
                                else
                                {
                                    $('input[type="submit"]').attr('disabled', 'true');
                                }
                                
                                if (selected_val == 'custom')
                                {
                                    $('select[name="table_numbers"]').parent().css('width', '50%').next().css('display', 'inline-block');
                                }
                                else
                                {
                                    $('select[name="table_numbers"]').parent().css('width', '75%').next().css('display', 'none').find('input').val(''); 
                                }
                            });
                        }

                    })
                    .fail(function() {
                        console.log("error");
                    })
                }
            }, 250);
        }
    </script>

<?php require 'footer.php'; ?>

