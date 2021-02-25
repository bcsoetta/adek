<style type="text/css">
    .pending_link {
        color: blue;
        cursor: pointer;
    }
</style>

<p id="pageTitle" hidden>&nbsp;&nbsp;Browse Dokumen</p>


<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();
include_once ($filepath.'/../lib/Database.php');
include_once ($filepath.'/../helpers/Format.php');
include_once ($filepath . '/../classes/Apps.php');

spl_autoload_register(function($class) {
    include_once "../classes/" . $class . ".php";
});

$db = new Database();
$fm = new Format();
$usr = new User();
$apps = new Apps();

$user_id = Session::get("id");
$data = $apps->getDocsById($user_id);

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<script src="js/jquery.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>

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
        <tbody>
            <?php if ($data == true) { 
                    $no = 1; foreach ($data as $r) { ?> 
                    <tr>
                        <td><?php echo $r['pib_nomor'] ?></td>
                        <td><?php echo $r['pib_tanggal'] ?></td>
                        <td><?php echo $r['importir'] ?></td>
                        <td><?php echo $r['shipper'] ?></td>
                        <td><?php echo $r['jum_dokumen'] ?></td>
                        <td>dsd</td>
                    </tr>
            <?php $no++; } } else {  } ?>

        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#app-tb1').DataTable();
    } );
</script>

<!-- <script>
    $(document).ready(function() {
        var t = $('#app-tb1').DataTable( {
            "ajax": {
            	"url": "ajax/ajax_upload_browse_byid.php",
            	"type": "POST",
                "data": data
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
         				return '<div class="pending_link" pib_nomor="'+pib_nomor+'" pib_tanggal="'+pib_tanggal_x+'">DOWNLOAD or VIEW</div>';

         			}
                },
            ]
        });

        $(document).on("click", ".pending_link", function(e) {
            e.stopImmediatePropagation();
            var action = "ambil_selesai";
            var pib_nomor = $(this).attr("pib_nomor");
            var pib_tanggal = $(this).attr("pib_tanggal");
            var dataString = "action=" + action + "&pib_nomor=" + pib_nomor + "&pib_tanggal=" + pib_tanggal;
            $.ajax({
                url: "../adek/ajax/ajax_upload_ambil.php",
                method: "POST",
                data: dataString,
                success: function(data) {
                    console.log(data);
                    var pagex = 'upload_browse_selesai_detil';
                    $('#apps-contents').load('contents/' + pagex + '.php?' + dataString);
                },
                error: function(data) {
                    console.log(data);
                }
            });
            return false;
        }); 

    });

</script> -->
