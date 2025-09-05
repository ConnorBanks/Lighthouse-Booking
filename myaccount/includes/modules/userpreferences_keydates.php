<div class="row">
	<div class="col-sm-12">
		<h3>Key Dates</h3>
	</div>
	<?php 
	$keydates = database("SELECT * FROM useraccounts_keydates WHERE useraccountid='$useraccountid' ORDER BY keydate DESC");
	if (count($keydates) > 0)
	{
		?>
		<div class="col-sm-6">
			<?php 
				echo '<ul>';
				if(isset($_GET['remove']))
				{
					$keydateid = $_GET['remove'];
					echo '<h3>Are you sure you want to remove this Key Date</h3>';
					echo '<a style="color:red" href="'.$_SERVER['PHP_SELF'].'?removedate='.$keydateid.'">Yes</a><br />';
					echo '<a style="color:green" href="'.$_SERVER['PHP_SELF'].'">No</a>';
				}
				else
				{
					foreach($keydates as $keydate)
					{
						$date = date('dS M Y',strtotime($keydate['keydate']));
						$name = $keydate['keydate_name'];
						$keydateid = $keydate['keydateid'];
						echo '<li>'.($name>''?$name.': ':'').$date.' - <a style="color:red" class="confirm_delete" href="'.$_SERVER['PHP_SELF'].'?remove='.$keydateid.'">Remove</a></li>' . "\n";
					} 
				}
				echo '</ul>'
			?>		
		</div>
		<?php
	}
	?>
	<div class="col-sm-6">
		<h4>Add dates:</h4>
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<input type="hidden" name="keydatesubmit">
			<div class="form-group">
				<label>Occasion</label>
				<input type="text" name="keydate_name" class="form-control" required="true">
			</div>
			<div class="form-group">
				<label>Key Dates</label>
				<input type="text" name="keydate" id="keydate" class="form-control" size="30" autocomplete="off" value="<?php echo date('l j F Y'); ?>" readonly="true">
			</div>			
			<div class="form-group">
				<input type="submit" class="btn btn-default">
			</div>
		</form>
	</div>
</div>