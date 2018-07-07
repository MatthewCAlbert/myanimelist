<?php
 if(isset($_SESSION['myanimelist_username'])){
 }else if(isset($_COOKIE['myanimelist_member_login'])){
    $_SESSION["myanimelist_username"] = $_COOKIE['myanimelist_member_login'];
    header("Location: index.php?report=cookie-detected");
    exit();
 }else{
    header("Location: ../login");
    exit();
 }