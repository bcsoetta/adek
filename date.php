<script type="text/javascript" src="js/jquery.js"></script>
<input type="date" id="startDate" name="startDate" ></p>

<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('change', '#startDate', function() {
			var d = new Date($('#startDate').val());
			var day = d.getDate() + '-' + d.getMonth();
			alert(day);
		});
	})
</script>