<style type="text/css">
    .selesaix_detil_link {
        color: blue;
        cursor: pointer;
    }
</style>

<p id="pageTitle" hidden>&nbsp;&nbsp;Browse Dokumen All</p>


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
$data = $apps->getDocsAllSel($user_id);

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
                <th>Status</th>
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
                        <td><?php echo $r['status'] ?></td>
                        <td><div class="selesaix_detil_link" pib_nomor="<?php echo $r['pib_nomor'] ?>" pib_tanggal="<?php echo $r['pib_tanggal_x'] ?>">VIEW DETIL</div></td>
                    </tr>
            <?php $no++; } } else {  } ?>

        </tbody>
    </table>
</div>

<script type="text/javascript">

    $(document).ready(function() {
        $('#app-tb1').DataTable();
    } );

    $(document).on("click", ".selesaix_detil_link", function(e) {
        e.stopImmediatePropagation();
        var action = "get_all_selesai_byid";
        var pib_nomor = $(this).attr("pib_nomor");
        var pib_tanggal = $(this).attr("pib_tanggal");
        var dataString = "action=" + action + "&pib_nomor=" + pib_nomor + "&pib_tanggal=" + pib_tanggal;
        $.ajax({
            url: "../adek/ajax/ajax_upload_ambil.php",
            method: "POST",
            data: dataString,
            success: function(data) {
                console.log(data);
                var pagex = 'upload_browse_all_selesai_detil';
                $('#apps-contents').load('contents/' + pagex + '.php?' + dataString);
            },
            error: function(data) {
                console.log(data);
            }
        });
        return false;
    }); 

</script>

