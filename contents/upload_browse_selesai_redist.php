<style type="text/css">
    .ambil_redist {
        color: blue;
        cursor: pointer;
    }
    #datepicker-wrapper {
        margin-bottom: 15px;
        position: fixed;
        margin-left: 150px;
        z-index: 1;
    }
    #datepicker-wrapper > input {
        width: 90px;
        text-align: center;
        border: 1px solid #ababab;
        padding: 1.5px 0;
    }
</style>

<p id="pageTitle" hidden>&nbsp;&nbsp;Browse Petugas & Redist</p>


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

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<link rel="stylesheet" type="text/css" href="css/datepicker.min.css">

<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
<script src="js/jquery.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/datepicker.min.js"></script>

<div class="apps-data">

    <div id="datepicker-wrapper">
        FILTER TANGGAL PIB
        <input data-toggle="datepicker" id="min" type="text" placeholder="DD-MM-YYYY" value="01-01-<?php echo date('Y')?>"> 
        -
        <input data-toggle="datepicker" id="max" type="text" placeholder="DD-MM-YYYY" value="<?php echo date('d-m-Y') ?>">
    </div>

    <table id="app-tb1"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
            <thead>
                <tr>
                    <th>PIB</th>
                    <th>Tanggal</th>
                    <th>Importir</th>
                    <th>Jum</th>
                    <th>PFPD</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
    </table>
</div>

<script type="text/javascript">

    $(document).ready(function() {

        $('[data-toggle="datepicker"]').datepicker({
            format: 'dd-mm-yyyy',
            autoHide: true
        });

        var dataTable = $('#app-tb1').DataTable( {
            "processing": true,
            "serverSide": true,
            "ajax": {
                url :"../adek/ajax/ajax_ss_redist.php",
                type: "post",
                data: function(d) {
                    d.minDate = $('#min').val();
                    d.maxDate = $('#max').val();
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": 6 }
            ]
        });

        $("#min, #max").on("change", function() {
            var minDate = $('#min').val();
            var maxDate = $('#max').val();
            dataTable.ajax.reload();
        });

    });

    $(document).on("click", ".ambil_redist", function(e) {
        e.stopImmediatePropagation();
        var r = confirm("Are you sure want to take (redist) the documents?");
        if (r == true) {
            e.stopImmediatePropagation();
            var action = "ambil_redist";
            var pib_nomor = $(this).attr("pib_nomor");
            var pib_tanggal = $(this).attr("pib_tanggal");
            var pfpd_id = $(this).attr("pfpd_id");
            var dataString = "action=" + action + "&pib_nomor=" + pib_nomor + "&pib_tanggal=" + pib_tanggal + "&pfpd_id=" + pfpd_id;
            $.ajax({
                url: "../adek/ajax/ajax_upload_ambil.php",
                method: "POST",
                data: dataString,
                success: function(data) {
                    var pagex = 'upload_browse_selesai_redist';
                    $('#apps-contents').load('contents/' + pagex + '.php?' + dataString);
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

</script>

