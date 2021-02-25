<style type="text/css">

    .konf_contents {
        border: 1px dashed #ddd;
        padding: 10px;
        margin-top: 10px;
        font-size: 1.1em;
    }
    #konf_reply_kirim {
        margin-top: 15px;
    }
    .konf_content_date {
        color: #607d8b;
        margin: 0px 0 5px 0;
    }
    textarea {
        width: 97.84%;
        min-height: 60px;
        border: 1px solid #ddd;
        padding: 10px;
    }
    img {
        width: 20px;
        margin-bottom: 5px;
        float: right;
    }

</style>

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

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

$db = new Database();
$fm = new Format();
$usr = new User();
$apps = new Apps();

$pib_nomor = $_GET['pib_nomor'];
$pib_tanggal = $_GET['pib_tanggal'];
$konf_id = $_GET['konf_id'];

$data = $apps->getDocs($pib_nomor, $pib_tanggal);
$konfss = $apps->getKonfById($konf_id);
$konfs = $apps->getKonfByPib2($pib_nomor, $pib_tanggal);
$logs = $apps->getLogByPib($pib_nomor, $pib_tanggal);
$konf_replies = $apps->getKonfReplies($konf_id);

// var_dump($konf_replies);

?>

<div id="reply-wrapper">
    <div id="konf_title_reply">Konfirmasi #<?php echo $konf_id ?> <?php echo $data['importir'] ?>, PIB <?php echo $data['pib_nomor'] ?> / <?php echo $data['pib_tanggal_x'] ?></div>

    <div class="konf_contents" style="border: 1px solid #786b5845">
        <div class="konf_content_date">Posted by <?php echo $konfss['name'] ?> on <?php echo $konfss['konf_timestamp'] ?></div>
        <div class="konf_contents_text"><?php echo $konfss['konfirmasi'] ?></div>
    </div>

    <?php if ($konf_replies == false) {} ?>

    <?php if ($konf_replies == true) { ?>
        <?php foreach ($konf_replies as $konf) { ?>
        <div class="konf_contents">
            <div class="konf_content_date">Posted by <?php echo $konf['name'] ?> on <?php echo $konf['reply_timestamp'] ?></div>
            <div class="konf_contents_text"><?php echo $konf['reply'] ?></div>
        </div>
        <?php } ?>
    <?php } ?>
    
    <div class="new_reply"></div>
    
    <div id="konf_reply_kirim">
        <img src="images/reply.svg">
        <textarea id="konf_text"></textarea>
        <br>
        <input id="konf_send_rr" style="padding: 1px 2px; cursor: pointer; margin-top: 5px;" type="submit" value="SEND">
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#konf_send_rr").on("click", function(e) {
            e.stopImmediatePropagation();
            var r = confirm("Are you sure want to send the confirmation?");
            if (r == true) {
                var action = "konf_update_reply_process";
                var konf_id = "<?php echo $konf_id ?>";
                var pfpd_id = "<?php echo $data['pfpd_id'] ?>";
                var pib_nomor = "<?php echo $data['pib_nomor'] ?>";
                var pib_tanggal = "<?php echo $data['pib_tanggal'] ?>";
                var customer_id = "<?php echo $data['uploader_id'] ?>";
                var konfirmasi = $("#konf_text").val().trim();
                var dataString = 'pfpd_id=' + pfpd_id + '&pib_nomor=' + pib_nomor + '&pib_tanggal=' + pib_tanggal + '&action=' + action + '&konfirmasi=' + konfirmasi + '&customer_id=' + customer_id + '&konf_id=' + konf_id;
                // console.log(dataString);
                $.ajax({
                    url: "../adek/ajax/ajax_upload_ambil.php",
                    method: "POST",
                    data: dataString,
                    success: function(data) {
                        // console.log(data);
                        var json = JSON.parse(data);
                        console.log(json);
                        var id = json[0].id;
                        var name = json[0].name;
                        var reply = json[0].reply;
                        var reply_timestamp = json[0].reply_timestamp;
                        $(".new_reply").append('<div class="konf_contents"><div class="konf_content_date">Posted by '+name+' on '+reply_timestamp+'</div><div class="konf_contents_text">'+reply+'</div></div>');
                        $("#konf_text").val("");
                    },
                    error: function(data) {
                        alert("Ops! failed.");
                        console.log(data);
                    }
                })
            } else {
                alert("You pressed Cancel!");
            }
            return false;
        })
    }); 
    
</script>