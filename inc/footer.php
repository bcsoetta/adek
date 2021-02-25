	<style type="text/css">

		/*@media  screen and (min-width: 1340px) and (min-height: 497px) {
			.footer-text {
			display: none;
		}*/

	</style>

	</div>

	<div class="app-footer" data-options="region:'south', split:true">
	    <!-- footer here -->
	    <div class="footer-text">
	        <code>
	            <?php echo "© 2018-" . date('Y') . ' '; ?><a target="_blank" href="https://bcsoetta.org/">
                    KPU Bea dan Cukai Tipe C Soekarno Hatta
                </a>
	        </code>
	        <br>
	        <code>
	            Phone: 021-5502072,
	            Email: pdad.soetta@gmail.com
	        </code>
	    </div>
	</div>
	<style type="text/css">
		.loader {
		    position: fixed;
		    left: 0px;
		    top: 0px;
		    width: 100%;
		    height: 100%;
		    z-index: 9999;
		    background: url('images/loading3.gif') 50% 40% no-repeat rgb(249,249,249);
		    /*background-size: 100%;*/
		    /*background-color: white;*/
		    /*opacity: .8;*/
		}
	</style>
	<div class="loader"></div>

</body>

</html>

<script type="text/javascript">
	$(document).ready(function() {
	    $(document).on('click', '.link1', function() {
	        var page = $(this).attr('href');
	        $('#apps-contents').load('contents/' + page);
	        return false;
	    });
	});

	// $(window).load(function() {
	//     $(".loader").fadeOut("slow");
	// });	
    
	setTimeout(function() {
		$(".loader").fadeOut();
	}, 2500);

	$(document).on("click", ".konfirmasi", function(e) {
        e.stopImmediatePropagation();
        var action = "konf_update_status";
        var user_id = "<?php echo $user_id ?>";
        var konf_id = $(this).attr("konf_id");
        var pib_nomor = $(this).attr("pib_nomor");
        var pib_tanggal = $(this).attr("pib_tanggal");
        var dataString = "action=" + action + "&pib_nomor=" + pib_nomor + "&pib_tanggal=" + pib_tanggal + "&konf_id=" + konf_id + "&user_id=" + user_id;
        $.ajax({
            url: "../adek/ajax/ajax_upload_ambil.php",
            method: "POST",
            data: dataString,
            success: function(data) {
                console.log(data);
                // $("#konf_stats").html(data);
                // $(".konfirmasi[konf_id='"+konf_id+"']").css({"color": "#e91e63"});
                window.open('jspdf.php?konf_id=' + konf_id, '_blank');
            },
            error: function(data) {
                console.log(data);
            }
        });
        return false;
    });

    $(document).on("click", ".konfirmasi_r", function(e) {
        e.stopImmediatePropagation();
        var action = "konf_update_reply";
        var konf_id = $(this).attr("konf_id");
        var pib_nomor = $(this).attr("pib_nomor");
        var pib_tanggal = $(this).attr("pib_tanggal");
        var dataString = "action=" + action + "&pib_nomor=" + pib_nomor + "&pib_tanggal=" + pib_tanggal + "&konf_id=" + konf_id;
        $.ajax({
            url: "../adek/ajax/ajax_upload_ambil.php",
            method: "POST",
            data: dataString,
            success: function(data) {
                console.log(data);
                $("#konf_stats").html(data);
                var pager = 'upload_konfirmasi_detil';
                $('#apps-contents').load('contents/' + pager + '.php?' + dataString);
            },
            error: function(data) {
                console.log(data);
            }
        });
        return false;
    }); 

    $(document).on("click", ".konfirmasi_pfpd_r", function(e) {
        e.stopImmediatePropagation();
        var action = "konf_update_pfpd_reply";
        var konf_id = $(this).attr("konf_id");
        var pib_nomor = $(this).attr("pib_nomor");
        var pib_tanggal = $(this).attr("pib_tanggal");
        var dataString = "action=" + action + "&pib_nomor=" + pib_nomor + "&pib_tanggal=" + pib_tanggal + "&konf_id=" + konf_id;
        $.ajax({
            url: "../adek/ajax/ajax_upload_ambil.php",
            method: "POST",
            data: dataString,
            success: function(data) {
                console.log(data);
                $("#konf_stats").html(data);
                var pager = 'upload_konfirmasi_pfpd_detil';
                $('#apps-contents').load('contents/' + pager + '.php?' + dataString);
            },
            error: function(data) {
                console.log(data);
            }
        });
        return false;
    }); 

</script>