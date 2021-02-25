$(document).ready(function() {
	/* Login */
	$("#login-submit").click(function() {
		var username = $("#username").val();
		var password = $("#password").val();
		var datastring = 'username=' + username + '&password=' + password;
		$.ajax({
			type: "POST",
			url: "login_ajax.php",
			data: datastring,
			success: function(data) {
				if ($.trim(data) == "empty") {
					$(".empty").show();
					setTimeout(function() {
						$(".empty").fadeOut();
					}, 4000);
				} else if ($.trim(data) == "disable") {
					$(".disable").show();
					setTimeout(function() {
						$(".empty").fadeOut();
					}, 4000);
				} else if ($.trim(data) == "error") {
					$(".error").show();
					setTimeout(function() {
						$(".empty").fadeOut();
					}, 4000);
				} else {
					window.location = "apps.php";
				}
			}
		});
		return false;
	});

	/* Load content files */
	// Initial
	// alert(user_level);
	if (user_level == 4) {
		$('#apps-contents').load('contents/upload_browse_all_selesai_byid.php');
	} else if (user_level !== 4) {
		$('#apps-contents').load('contents/home.php');
	}
	// Handle menu clicks
	$('.link-load').click(function() {
		var page = $(this).attr('href');
		$('#apps-contents').load('contents/' + page + '.php', function() {
			var title = $('#apps-contents #pageTitle');
			$('#apps-contents-wrapper').prop('title', $(title).text());
			var panel = $('.panel-title');
			// console.log(panel[2]);
			panel[2].innerHTML = $(title).text();
			return true;
		});
		return false;
	});
	
});
