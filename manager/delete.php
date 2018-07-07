<?php
    require '../include/server.php';
    require '../include/session.checker.php';
    require '../include/getuserdata.php';
    if($user_row['status']!='administrator'){
        header('Location: ..');
        exit();
    }
    if(isset($_GET['username'])){
        $username = mysqli_escape_string($conn,$_GET['username']);
        $sql = "DELETE FROM `user` WHERE `username`='$username'";
        $res = $conn->query($sql);
        if($res){
            if(count($res) > 0){
                header('Location: ../manager?success');
                exit();
            }else{
                header('Location: ../manager?not-found');
                exit();
            }
        }else{
            header('Location: ../manager?error');
            exit();
        }
    }
?>