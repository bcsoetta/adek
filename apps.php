<?php

include 'inc/header.php';
Session::checkSession();
$level = Session::get("level");

$filepath = realpath(dirname(__FILE__));
include_once ($filepath . '/classes/Apps.php');
$apps = new Apps();

$user_id = Session::get("id");

if ($user_id == 0 OR $user_id == null OR $user_id == false) { 
    Session::redirect();
}

$row_count = $apps->getStatUnread($user_id);
while ($data = mysqli_fetch_array($row_count)) {
    $row_count_unread = $data['JUM'];
};

$row_count = $apps->getStatRead($user_id);
while ($data = mysqli_fetch_array($row_count)) {
    $row_count_read = $data['JUM'];
};

$row_count = $apps->getStatPfpd($user_id);
while ($data = mysqli_fetch_array($row_count)) {
    $row_count_all = $data['JUM'];
};

$getAllNpd = $apps->getAllNpd(Session::get('nip'));
$npdDiterima = $apps->getDiterimaNpd(Session::get('nip'));
$getPfpdNpd = $apps->getPfpdNpd(Session::get('nip'));

?>

<div data-options="region:'east',split:true,hideCollapsedContent:false,collapsed:true" title="." style="width:300px;"></div>
<div data-options="region:'west',split:true,hideCollapsedContent:false" title="Menu" style="width:210px;overflow-x: hidden;">
    
    <div class="easyui-menu" data-options="inline:true" style="width:100%">
        <div data-options=""><a class="link-load" href="home">Home</a></div>
        <div>

            <?php if ($level == 1) { ?>

                <span>Administrator</span>

                <div style="width:200px;">
                    <div data-options="href:''"><a class="link-load" href="upload_dokap">Upload Dokumen</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_edit_rem_kirim">Edit Remove Kirim</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_status">Cek Status</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_browse_terima">Browse Penerimaan</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_browse_pending">Browse Penerimaan Pending</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_browse_selesai">Browse Penerimaan Selesai</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_browse_byid">Browse Dokumen</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_browse_selesai">Browse Dokumen All</a></div>
                    <div>
                        <span>User</span>
                        <div style="width:200px;">
                            <div data-options="href:''"><a class="link-load" href="user">User Manager</a></div>
                            <div data-options="href:''"><a class="link-load" href="user">Online User</a></div>
                            <div data-options="href:''"><a class="link-load" href="set_kegiatan_browse">Log Access</a></div>
                        </div>
                    </div>
                </div>

            <?php } ?>

            <?php if ($level == 2) { ?>
                
                <span>Pengguna Jasa</span>

                <div style="width:200px;">
                    <div data-options="href:''"><a class="link-load" href="upload_dokap">Upload Dokumen</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_edit_rem_kirim">Edit Remove Kirim</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_status_pengguna_jasa">Cek Status</a></div>
                    <div data-options="href:''"><a class="link-load" href="user_update_pass">Update Password</a></div>
                </div>

            <?php } ?>

            <?php if ($level == 3) { ?>
                
                <span>Staff Pemeriksa Dokumen</span>

                <div style="width:200px;">
                    <div data-options="href:''"><a class="link-load" href="upload_browse_terima">Browse Penerimaan</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_browse_pending">Browse Penerimaan Pending</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_status_pfpd">Cek Status</a></div>
                    <div data-options="href:''"><a class="link-load" href="user_update_pass">Update Password</a></div>
                </div>

            <?php } ?>

            <?php if ($level == 4) { ?>
                
                <span>Pemeriksa Dokumen</span>

                <div style="width:200px;">
                    <div data-options="href:''"><a class="link-load" href="upload_browse_all_selesai_byid">Browse Dokumen</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_browse_selesai_redist">Redist (Ambil)</a></div>
                    <div data-options="href:''"><a class="link-load" href="upload_status_pfpd">Cek Status</a></div>
                    <div data-options="href:''"><a class="link-load" href="user_update_pass">Update Password</a></div>
                </div>

            <?php } ?>

            <?php if ($level == 5) { ?>
                
                <span>Customs</span>

                <div style="width:200px;">
                    <div data-options="href:''"><a class="link-load" href="upload_status_pfpd">Cek Status</a></div>
                    <div data-options="href:''"><a class="link-load" href="user_update_pass">Update Password</a></div>
                </div>

            <?php } ?>
            
        </div>
        
        <?php if ($level == 2) { ?>
            <div data-options="href:''"><a id="konf_stats" class="link-load" href="npd_pengguna_jasa">Konf. NPD Manual (<b><?php echo $npdDiterima; ?></b>/<?php echo $getAllNpd; ?>)</a></div>
            <div data-options="href:''"><a id="konf_stats" class="link-load" href="upload_konfirmasi">Konf. Lainnya (<b><?php echo $row_count_unread; ?></b>/<?php echo $row_count_read; ?>)</a></div>
        <?php } ?>

        <?php if ($level == 4) { ?>
            <div data-options="href:''"><a id="konf_pfpd_stats" class="link-load" href="npd_pfpd">Konf. NPD Manual (<b><?php echo $getPfpdNpd; ?></b>)</a></div>
            <div data-options="href:''"><a id="konf_pfpd_stats" class="link-load" href="upload_konfirmasi_pfpd">Konf. Lainnya (<b><?php echo $row_count_all ?></b>)</a></div>
        <?php } ?>

        <div data-options="iconCls:'icon-print',disabled:true">Print</div>
        <div class="menu-sep"></div>
        <div><a href="?action=logout">Exit</a></div>
    </div>

</div>

<div id="apps-contents-wrapper" data-options="region:'center', title:'&nbsp;&nbsp;Home' ">
    <div id="apps-contents"></div>
</div>

<?php include 'inc/footer.php'; ?>