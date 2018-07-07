<?php
if(isset($_SESSION["myanimelist_username"])){
    $uid = $_SESSION["myanimelist_username"];
    $sql = "SELECT * FROM `user` WHERE `username`='$uid'";
    $result = mysqli_query($conn,$sql);
    if(!$result){
        header('Location: ../login');
        exit();
    }
    $user_row = mysqli_fetch_assoc($result);
}