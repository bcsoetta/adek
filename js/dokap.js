$(document).ready(function() {

	// SELECT OPTIONS DETECT ON CHANGE
	$("#doc_type").change(function() {
		var selectedType = $('option:selected', this).attr('value');
		if (selectedType == "5") {
			$("#doc_type_lain").show();
		} else {
			$("#doc_type_lain").hide();
			$("#doc_type_lain").val("");
		}
	});

	$("#image-form").submit(function(e) {
		$("#upwrapper").show();
		e.stopImmediatePropagation();
		var image_name = $("#image").val();
		if (image_name == '') {
			alert("Please select document");
			return false;
		} else {
			var extension = $("#image").val().split(".").pop().toLowerCase();
			if (jQuery.inArray(extension, ['pdf']) == -1) {
				alert("Invalid document type");
				$("#image").val("");
				return false;
			} else {
				var dataString = new FormData(this);
				var doc_type_x = $("#doc_type").val();
				var row = $(".row_id").attr("row_id");
				var jalur = $( ".select option:selected" ).val();
				console.log(row);

				if ($("#action").val() == "insert") {
					dataString.append('id_edit', '');
				}

				if ($("#action").val() == "update") {
					if (typeof row == 'undefined') {
						dataString.append('id_edit', '');
					}

					if (typeof row != 'undefined') {
						dataString.append('id_edit', row);
					}
				}

				dataString.append('jalur', jalur);
				dataString.append('doc_type', doc_type_x);

				$.ajax({
					url: "../adek/ajax/ajax_upload_process.php",
					method: "POST",
					data: dataString,
					contentType: false,
					processData: false,
					crossDomain: true,
					cache: false,
					xhr: function() {
						var myXHR = $.ajaxSettings.xhr();
						if (myXHR.upload) {
							myXHR.upload.addEventListener('progress', function(e) {
								if (e.lengthComputable) {
									var percent = Math.round(e.loaded/e.total*100);
									console.log('Upload:' + percent + '%');
									$("#upload-progress").width(percent+'%');
								}
							}, false);
						}
						// console.log(myXHR);
						return myXHR;
					},
					success: function(data) {
						// console.log(data);
						alert(data);
						console.log(data);
						$("#image").val("");
						$("#upload-progress").width(0+'%');
						$("#upwrapper").hide();
					},
					error: function(data) {
						alert("Error");
						$("#upload-progress").width(0+'%');
						$("#upwrapper").hide();
					}
				});
			}
		}
		return false;
	});

});
