<style type="text/css">
    .selesai_byid_detil_link {
        color: blue;
        cursor: pointer;
    }

    #widget-setiadi {
        border: 1px solid #ddd;
        padding: 8px 8px 0px 8px;
        display: inline-flex;
        margin-bottom: 20px;
    }
    #datepicker-wrapper {
        display: inline-block;
        float: left;
        margin-bottom: 10px;
    }
    #datepicker-wrapper > input {
        width: 90px;
        text-align: center;
        border: 1px solid #ababab;
        padding: 1.5px 0;
    }
    #selected-jalur {
        display: inline-block;
        //margin-left: 5px;
    }

    .y-jalur {
        background: linear-gradient(to right, #fff , #ffff85);
    }
    .g-jalur {
        background: linear-gradient(to right, #fff , #a9ffa9);
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
    <div id="widget-setiadi">
        <div id="datepicker-wrapper">
            Filter Tanggal PIB
            <input data-toggle="datepicker" id="min" type="text" placeholder="DD-MM-YYYY" value="01-01-<?php echo date('Y')?>"> 
            -
            <input data-toggle="datepicker" id="max" type="text" placeholder="DD-MM-YYYY" value="<?php echo date('d-m-Y') ?>">
        </div>

        <div>
            &nbsp;&nbsp;&nbsp;JALUR
            <select id="selected-jalur">
                <option value="">All</option>
                <option value="K">Kuning</option>
                <option value="H">Hijau</option>
            </select>
        </div>
    </div>

    <table id="app-tb1"  cellpadding="0" cellspacing="0" border="0" class="display" width="100%">
            <thead>
                <tr>
                    <th style="width: 90px">Waktu Upload</th>
                    <th>PIB</th>
                    <th>Tanggal</th>
                    <th>Importir</th>
                    <th>Jum</th>
                    <th>PPJK</th>
                    <th>Konf.</th>
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
                url :"../adek/ajax/ajax_ss_periksa_pfpd.php",
                type: "post",
                data: function(d) {
                    d.minDate = $('#min').val();
                    d.maxDate = $('#max').val();
                    d.jalur = $("#selected-jalur").val();
                }
            },
            "columnDefs": [
                { "orderable": false, "targets": 7 }
            ]
        });

        $("#min, #max").on("change", function() {
            // var minDate = $('#min').val();
            // var maxDate = $('#max').val();
            dataTable.ajax.reload();
        });

        $('#selected-jalur').on('change', function() {
            dataTable.ajax.reload();
        });

    });

    $(document).on("click", ".selesai_byid_detil_link", function(e) {
        e.stopImmediatePropagation();
        var action = "get_all_selesai_byid";
        var pib_nomor = $(this).attr("pib_nomor");
        var pib_tanggal = $(this).attr("pib_tanggal");
        var pfpd_id = $(this).attr("pfpd_id");
        var dataString = "action=" + action + "&pib_nomor=" + pib_nomor + "&pib_tanggal=" + pib_tanggal;
        $.ajax({
            url: "../adek/ajax/ajax_upload_ambil.php",
            method: "POST",
            data: dataString,
            success: function(data) {
                var pagex = 'upload_browse_all_selesai_byid_detil';
                $('#apps-contents').load('contents/' + pagex + '.php?' + dataString);
            },
            error: function(data) {
                console.log(data);
            }
        });
        return false;
    }); 

</script>

