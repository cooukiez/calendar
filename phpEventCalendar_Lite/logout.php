<?php
    require_once('conf.php');
    unset($_SESSION['userData']);
    @session_destroy();
    header('Location: index.php');
?>
