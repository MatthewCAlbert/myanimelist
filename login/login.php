<?php
    require "../include/server.php";
    if(isset($_POST["submit"])){
        $id = mysqli_real_escape_string($conn,$_POST["username"]);
        $pwd = mysqli_real_escape_string($conn,$_POST["password"]);
        
        //Error Handlers
        //Check for empty fields
        if( empty($id) || empty($pwd)){
            header("Location: ../login?login=empty");
            exit();
        }else{
            //Checked if input are valid
                $sql = "SELECT * FROM `user` WHERE `username`='$id'";
                $result = mysqli_query($conn,$sql);
                $resultCheck = mysqli_num_rows($result);
                if( $resultCheck > 0){
                    $row = mysqli_fetch_assoc($result);
                    //De-hashing password
                    $hashedPwdCheck = password_verify($pwd, $row['password']);
                    if( $hashedPwdCheck == false ){
                        header("Location: ../login?login=mismatch");
                        exit();
                    }elseif( $hashedPwdCheck == true ){
                        //Log in the user
                        $_SESSION['myanimelist_username'] = $row['username'];
                            //check for remember me
                            if(!empty($_POST["remember"])) {
                                setcookie ("myanimelist_member_login",$id,time()+ (7 * 24 * 60 * 60),"/"); //saved for 1 week
                            }
                            header("Location: ../login?login=success");
                        exit();
                    }else{
                        header("Location: ../login?login=error");
                        exit();
                    }
                }else{
                    header("Location: ../login?login=mismatch");
                }
        }
    }else{
        header("Location: ../login");
        exit();
    }

