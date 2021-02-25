<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<p id="pageTitle" hidden>&nbsp;&nbsp;~ Penerimaan</p>

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
            "order": [[ 1, 'asc' ]],
            "columns": [
                { "data": "pib_nomor" },
                { "data": "pib_tanggal" },
                { "data": "importir" },
                { "data": "shipper"},
                { "data": "jum_dokumen"},
                {
                	data: null,
                	render: function ( data, type, row, full, meta ) {
                        var pib_nomor = data.pib_nomor;
                        var pib_tanggal_x = data.pib_tanggal_x;
         				return '<a class="link1" href="upload_browse_all_detil.php?pib_nomor='+pib_nomor+'&pib_tanggal='+pib_tanggal_x+'">AMBIL</a>';
         			}
                },
            ]
        });
    });

</script>
