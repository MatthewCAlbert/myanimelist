<?php
    session_start();
    $_SESSION = array();
    if(isset($_COOKIE['myanimelist_member_login'])){
        unset($_COOKIE['myanimelist_member_login']);
        setcookie("myanimelist_member_login", '', time()-3600,"/");
    }
    session_destroy();
    header("Location: ../index.php?logout=true");
    exit();