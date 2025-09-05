<div class="col-sm-12 bookings-bar">
	<div class="row">
		<div class="col-sm-4">			
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<div class="booking-date">
					<input type="text" name="selected_date" id="AdminDate" onChange="this.form.submit();" <?php if ($cur_date>'') { ?>value="<?php echo date('d/m/Y',strtotime($cur_date)); ?>"<?php } ?>>
				</div>
			</form>
		</div>
		<div class="col-sm-4">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<select name="selected_meal" onChange="this.form.submit();" style="width:100%;">
					<option value="Lunch" <?php echo ($selected_meal=='Lunch'?' selected="selected"':'') ?>>Lunch</option>
					<option value="Dinner" <?php echo ($selected_meal=='Dinner'?' selected="selected"':'') ?>>Dinner</option>
				</select>
			</form>
		</div>
		<div class="col-sm-4">
			<a href="../index.php?useraccountid=<?php echo $useraccountid; ?>" class="btn btn-default" target="_blank">Add a Booking</a>
		</div>
	</div>
</div>
<?php $searchsql.= " AND meal='$selected_meal'"; ?>
<div class="standard-box form-container">
	<div class="inner">
		<div class="col-sm-12 bookings">
			<div class="table-container">
				<?php				
				if ($cur_date > '')
				{
					$results = database("SELECT * FROM bookings WHERE useraccountid ='$useraccountid' AND party_date='$cur_date' $searchsql ORDER BY party_date DESC, party_time DESC, booking_id DESC LIMIT 20");
				}
				else
				{
					$results = database("SELECT * FROM bookings WHERE useraccountid ='$useraccountid' $searchsql ORDER BY party_date DESC, party_time DESC, booking_id DESC LIMIT 20");
				}
				?>
				<table>
		            <thead>
		                <tr>
		                    <th>Time</th>
		                    <th>Party Size</th>                  
		                    <th>Requests</th>
		                    <th>Edit</th>
		                    <th>Cancel</th>
		                </tr>
		            </thead>
			        <tbody>
					<?php
					
				  	foreach ($results as $result)
				  	{
				  		$booking_id = $result['booking_id'];
				  		$party_date = date('dS M Y',strtotime($result['party_date']));
					  	$party_time = $result['party_time'];
					  	$party_duration = $result['duration'];
					  	$party_size = $result['party_size'];
					  	$requests = $result['requests'];

					  	echo '<tr>';
						  	echo '<td>' . $party_date.' From '. $party_time .'</td>' ;
					  		echo '<td>'.$party_size.'</td>' ;
					  		echo '<td>'.$requests.'</td>' ; ;
					  		echo '<td><a class="icon icon--small icon--edit" href="amendBooking.php?booking_id='.$booking_id.'&amp;returnurl=index.php"></a></td>';
					  		echo '<td><a class="icon icon--small icon--cancel" href="'.$_SERVER['PHP_SELF'].'?cancelbooking='.$booking_id.'"></a></td>';
						echo '</tr>';
				  	} 
					?>
					</tbody>
		  		</table>
			</div>
		</div>
	</div>
</div>