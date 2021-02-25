<?php

$filepath = realpath(dirname(__FILE__));
include_once ($filepath. '/../lib/Session.php');
Session::init();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

?>

<link rel="stylesheet" type="text/css" href="css/datepicker.min.css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">

<style type="text/css">
    #user-wrap-feature {
        /* //border: 1px solid red; */
        margin-bottom: 20px;
    }
    .user-feature {
        border: 1px solid #ddd;
        display: table-cell;
        padding: 1.5px 4.5px;
        cursor: pointer;
        font-size: 1.5em;
        color: #000;
    }
    .status_active {
        color: green;
    }
    .status_inactive {
        color: red;
    }
    #apps-contents {
        /* min-width: 50%;
        max-width: 70%; */
        /* display: inline-flex; */
    }
    #datepicker-wrapper {
        margin-bottom: 15px;
        position: fixed;
        /* margin-left: 150px; */
        z-index: 1;
    }
    #datepicker-wrapper > input {
        width: 90px;
        text-align: center;
        border: 1px solid #ababab;
        padding: 1.5px 0;
    }
    #app-tb1_filter {
        margin-bottom: 15px;
    }
</style>

<p id="pageTitle" hidden>&nbsp;&nbsp;~ Konf. NPD Manual</p>

<div class="apps-data">
    <div id="user-wrap-feature">
        <a class="link-load-back" href="npd_create">
            <div class="user-feature" title="Create"><i class="fa fa-plus-circle" aria-hidden="true"></i></div>
        </a>
    </div>

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
                <th>PPJK</th>
                <th>PFPD</th>
                <th>Status</th>
                <!-- <th></th> -->
            </tr>
        </thead>
    </table>

</div>

<script src="js/jquery.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script src="js/dataTables.bootstrap.min.js"></script>
<script src="js/datepicker.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('[data-toggle="datepicker"]').datepicker({
            format: 'dd-mm-yyyy',
            autoHide: true
        });

        $('.link-load-back').click(function() {
            var page = $(this).attr('href');
            $('#apps-contents').load('contents/' + page + '.php');
            return false;
        });

        var dataTable = $('#app-tb1').DataTable( {
            "processing": true,
            "serverSide": true,
            "bLengthChange": false,
            "ajax": {
                url :"../adek/ajax/ajax_ss_npd_pfpd.php",
                type: "post",
                data: function(d) {
                    d.minDate = $('#min').val();
                    d.maxDate = $('#max').val();
                }
            }
        });

        $("#min, #max").on("change", function() {
            var minDate = $('#min').val();
            var maxDate = $('#max').val();
            dataTable.ajax.reload();
        });
    });   
</script>
