	<?php
	if (isset($_GET['bookingamended']))
	{
		?>
		<div id="bookingamended" class="bookingamended">
			Booking Amended
		</div>
		<script type="text/javascript">
			setInterval(function(){ 
				$('#bookingamended').hide('slow');
			}, 4000);
		</script>
		<?php
	}
	if (isset($_GET['bookingdeleted']))
	{
		?>
		<div id="bookingdeleted" class="bookingdeleted">
			Booking Deleted
		</div>
		<script type="text/javascript">
			setInterval(function(){ 
				$('#bookingdeleted').hide('slow');
			}, 4000);
		</script>
		<?php
	}
	if (isset($_SESSION['admin_access']))
	{
		?>
		<script type="text/javascript">
			$(function() {
				$('.confirm').click(function(event) {
					var url = $(this).data('url');
	  				var r = confirm("You are at your maximum amount of covers set for this time slot - Would you like to continue?");
	  				if (r == true) {
	    				window.location = url;
	  				} else {
	    				
	  				}
				});
			});
		</script>
		<?php 
	}
	?>
    </body>
</html>
<?php include 'tracker.php'; ?>