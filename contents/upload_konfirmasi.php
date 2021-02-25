<style type="text/css">
    .konfirmasi_r {
        color: blue;
        cursor: pointer;
    }
    .loading {
        width: 95.6%;
        height: 50px;
        position: absolute;
        left: 50;
        top: 40%;
        background-color: #ffffff9c;
        text-align: center;
        font-size: 1.5em;
        display: none;
    }
    .unread {
        color: blue;
        /*font-weight: 700;*/
    }
    .read {
        color: #e91e63;
        /*font-weight: 700;*/
    }
</style>

<p id="pageTitle" hidden>&nbsp;&nbsp;Konfirmasi</p>


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
$data = $apps->getDocsKonfById($user_id);

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
                <th>Waktu Konf.</th>
                <th>PIB</th>
                <th>Tanggal</th>
                <th>Importir</th>
                <th>Shipper</th>
                <th>PFPD</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($data == true) { 
                    $no = 1; foreach ($data as $r) { ?> 
                    <tr>
                        <td><?php echo $r['konf_timestamp'] ?></td>
                        <td><?php echo $r['pib_nomor'] ?></td>
                        <td><?php echo $r['pib_tanggal_x'] ?></td>
                        <td><?php echo $r['importir'] ?></td>
                        <td><?php echo $r['shipper'] ?></td>
                        <td><?php echo $r['name'] ?></td>
                        <?php if ($r['status'] === '0') { ?>
                            <td><div class="konfirmasi_r unread" konf_id="<?php echo $r['konf_id'] ?>" pib_nomor="<?php echo $r['pib_nomor'] ?>" pib_tanggal="<?php echo $r['pib_tanggal'] ?>"> &nbsp;UNREAD</div></td>
                        <?php } ?>
                        <?php if ($r['status'] === '1') { ?>
                            <td><div class="konfirmasi_r read" konf_id="<?php echo $r['konf_id'] ?>" pib_nomor="<?php echo $r['pib_nomor'] ?>" pib_tanggal="<?php echo $r['pib_tanggal'] ?>"> &nbsp;READ</div></td>
                        <?php } ?>
                        

                    </tr>
            <?php $no++; } } else {  } ?>

        </tbody>
    </table>

    <div class="loading">LOADING.. PLEASE WAIT</div>

</div>



<script type="text/javascript">

    $(document).ready(function() {

        var table = $('#app-tb1').DataTable({
            "order": [[ 0, "desc" ]]
        });

        // setTimeout(function() {
        //     $(".loading").fadeOut();
        // }, 2000);

    });

</script>

