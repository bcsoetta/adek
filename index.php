<?php include 'inc/header.php'; ?>
<?php Session::checkSession(); ?>

Hi, <?php echo Session::get("name") ?>

