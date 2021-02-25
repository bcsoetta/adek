<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>


<style type="text/css">
    .ambil_link {
        color: blue;
        cursor: pointer;
    }
</style>
<p id="pageTitle" hidden>&nbsp;&nbsp;Browse Penerimaan</p>

<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<div class="apps-data">
    <table id="app-tb1" class="display" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>PIB</th>
                <th>Tanggal</th>
                <th>Importir</th>
                <th>Shipper</th>
                <th>Jum. Dokumen</th>
                <th>Waktu Upload</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<script src="js/jquery.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        var t = $('#app-tb1').DataTable( {
            "ajax": {
            	"url": "ajax/ajax_upload_browse_all.php",
            	"type": "POST"
            },
            oLanguage: {
                sProcessing: "<img src='images/loading_1.gif'>",
                "sSearch": "Search",
                "sLengthMenu": "Showing _MENU_ records",
                //"sInfo": "Showing page _PAGE_ of _PAGES_ records",
                "sInfo": "Showing _START_ to _END_ of _TOTAL_ records"
            },

            // serverSide: true,
            "processing": true,
            "order": [[ 5, 'asc' ]],
            "columns": [
                { "data": "pib_nomor" },
                { "data": "pib_tanggal" },
                { "data": "importir" },
                { "data": "shipper"},
                { "data": "jum_dokumen"},
                { "data": "upload_timestamp" },
                {
                	data: null,
                	render: function ( data, type, row, full, meta ) {
                        var pib_nomor = data.pib_nomor;
                        var pib_tanggal_x = data.pib_tanggal_x;
         				return '<div class="ambil_link" pib_nomor="'+pib_nomor+'" pib_tanggal="'+pib_tanggal_x+'">AMBIL / PRIKSA</div>';

         			}
                },
            ]
        });

        setInterval( function () {
            t.ajax.reload();
        }, 30000 );

        $(document).on("click", ".ambil_link", function(e) {
            e.stopImmediatePropagation();
            var r = confirm("Are you sure? Press OK to continue.");

            if (r == true) {
                
                var action = "ambil_terima";
                var pib_nomor = $(this).attr("pib_nomor");
                var pib_tanggal = $(this).attr("pib_tanggal");
                var dataString = "action=" + action + "&pib_nomor=" + pib_nomor + "&pib_tanggal=" + pib_tanggal;
                $.ajax({
                    url: "../adek/ajax/ajax_upload_ambil.php",
                    method: "POST",
                    data: dataString,
                    success: function(data) {
                        // console.log(data);
                        if (data === "1") {
                            alert("Anda berhasil mengambil dokumen ini");
                            var pagex = 'upload_browse_terima_detil';
                            $('#apps-contents').load('contents/' + pagex + '.php?' + dataString);
                        }
                        if (data === "2") {
                            alert("Anda tidak bisa mengambil dokumen ini");
                            return false;
                        }
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            } else {
                alert("You pressed Cancel!");
            }
            return false;
        }); 

    });

</script>
