<?php  
	$preferences = database("SELECT allergyinfo, seatingpref, winepref FROM useraccounts WHERE useraccountid='$useraccountid' LIMIT 1"); 
	$allergyinfo = $preferences['allergyinfo'];
	$seatingpref = $preferences['seatingpref'];
	$winepref = $preferences['winepref'];
?>
<div class="row">
	<div class="col-sm-12">
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<input type="hidden" name="prefrencesubmit">
			<div class="form-group col-sm-4">
				<label>Allergy Information</label>
				<textarea class="form-control" style="height:150px" name="allergyinfo"><?php echo $allergyinfo ?></textarea>
			</div>
			<div class="form-group col-sm-4">
				<label>Preferred Seating Area</label>
				<textarea class="form-control" style="height:150px" name="seatingpref"><?php echo $seatingpref ?></textarea>
			</div>
			<div class="form-group col-sm-4">
				<label>Wine Preferences</label>
				<textarea class="form-control" style="height:150px" name="winepref"><?php echo $winepref ?></textarea>
			</div>
			<input type="submit" class="btn btn-default">
		</form>
	</div>
</div>