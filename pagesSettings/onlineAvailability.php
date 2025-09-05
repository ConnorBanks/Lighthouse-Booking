<?php
function checked($location,$meal,$day)
{
	$results = database("SELECT * FROM onlineavailability WHERE location='$location' AND meal='$meal' AND day='$day'");
	return count($results);
}

if (isset($_POST['update']))
{
	$availability = $_POST['availability'];
	//echo '<pre>'.print_r($availability,true).'</pre>';

	database("TRUNCATE TABLE `onlineavailability`");

	foreach ($availability as $location => $meal_array) 
	{
		foreach ($meal_array as $meal => $day_array) 
		{
			foreach ($day_array as $day_number => $value) 
			{
				//echo '<p>'.$location.' - '.$meal.' - '.$day_number.'</p>';	

				$sql = "INSERT INTO onlineavailability SET
					location='$location',
					meal='$meal',
					day='$day_number'";
				database($sql);
			}
		}
	}
}
?>

<h1>Seating Availability</h1>
<h4>Default online availability per day of week</h4>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<input type="hidden" name="update" value="true" />

<h2>Downstairs</h2>
<table>
	<thead>
		<tr>
			<th></th>
			<th>Monday</th>
			<th>Tuesday</th>
			<th>Wednesday</th>
			<th>Thursday</th>
			<th>Friday</th>
			<th>Saturday</th>
			<th>Sunday</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Lunch</th>
			<td>            
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][lunch][1]" value="Y" <?php if (checked('downstairs','lunch',1)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][lunch][2]" value="Y" <?php if (checked('downstairs','lunch',2)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][lunch][3]" value="Y" <?php if (checked('downstairs','lunch',3)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][lunch][4]" value="Y" <?php if (checked('downstairs','lunch',4)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][lunch][5]" value="Y" <?php if (checked('downstairs','lunch',5)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][lunch][6]" value="Y" <?php if (checked('downstairs','lunch',6)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][lunch][7]" value="Y" <?php if (checked('downstairs','lunch',7)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>				
			</td>
		</tr>
		<tr>
			<th>Dinner</th>
			<td>            
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][dinner][1]" value="Y" <?php if (checked('downstairs','dinner',1)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][dinner][2]" value="Y" <?php if (checked('downstairs','dinner',2)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][dinner][3]" value="Y" <?php if (checked('downstairs','dinner',3)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][dinner][4]" value="Y" <?php if (checked('downstairs','dinner',4)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][dinner][5]" value="Y" <?php if (checked('downstairs','dinner',5)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][dinner][6]" value="Y" <?php if (checked('downstairs','dinner',6)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[downstairs][dinner][7]" value="Y" <?php if (checked('downstairs','dinner',7)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>				
			</td>			
		</tr>
	</tbody>
</table>

<br /><br />

<h2>Upstairs</h2>
<table>
	<thead>
		<tr>
			<th></th>
			<th>Monday</th>
			<th>Tuesday</th>
			<th>Wednesday</th>
			<th>Thursday</th>
			<th>Friday</th>
			<th>Saturday</th>
			<th>Sunday</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>Lunch</th>
			<td>            
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][lunch][1]" value="Y" <?php if (checked('upstairs','lunch',1)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][lunch][2]" value="Y" <?php if (checked('upstairs','lunch',2)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][lunch][3]" value="Y" <?php if (checked('upstairs','lunch',3)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][lunch][4]" value="Y" <?php if (checked('upstairs','lunch',4)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][lunch][5]" value="Y" <?php if (checked('upstairs','lunch',5)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][lunch][6]" value="Y" <?php if (checked('upstairs','lunch',6)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][lunch][7]" value="Y" <?php if (checked('upstairs','lunch',7)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>				
			</td>			
		</tr>
		<tr>
			<th>Dinner</th>
			<td>            
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][dinner][1]" value="Y" <?php if (checked('upstairs','dinner',1)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][dinner][2]" value="Y" <?php if (checked('upstairs','dinner',2)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][dinner][3]" value="Y" <?php if (checked('upstairs','dinner',3)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][dinner][4]" value="Y" <?php if (checked('upstairs','dinner',4)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][dinner][5]" value="Y" <?php if (checked('upstairs','dinner',5)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>					
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][dinner][6]" value="Y" <?php if (checked('upstairs','dinner',6)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>
			</td>
			<td>
				<label class="switch vertical-align--middle">
            		<input type="checkbox" name="availability[upstairs][dinner][7]" value="Y" <?php if (checked('upstairs','dinner',7)>0) {echo 'checked="checked"';} ?> />
            		<div class="slider"></div>
        		</label>				
			</td>			
		</tr>
	</tbody>
</table>
<div style="display: block;margin-top: 15px;float: right;">	
	<input type="submit" value="Update" class="text--white"></input>
</div>
</form>