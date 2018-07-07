<?php
    require '../include/server.php';
    require '../include/session.checker.php';
    require '../include/getuserdata.php';
    $username = $user_row['username'];
    if(isset($_POST['submit'])){
        $name = mysqli_escape_string($conn,$_POST['name']);
        $password_change = mysqli_escape_string($conn,$_POST['password-change']);
        if( $password_change == 1 ){
            $old_password = mysqli_escape_string($conn,$_POST['old-password']);
            $new_password = mysqli_escape_string($conn,$_POST['new-password']);
            $re_password = mysqli_escape_string($conn,$_POST['re-password']);
            $verify = password_verify($old_password,$user_row['password']);
            if( $verify == true && $new_password == $re_password ){
                $hashedPw = password_hash($new_password,PASSWORD_BCRYPT);
                $sql = "UPDATE `user` SET `password`='$hashedPw',`name`='$name' WHERE `username`='$username'";
                $res = $conn->query($sql);
                if($res){
                    $notice = 'Success!';
                }else{
                    $notice = 'Failed!';
                }
            }else{
                $notice = 'Mismatch!';
            }
        }else{
            $sql = "UPDATE `user` SET `name`='$name' WHERE `username`='$username'";
            $res = $conn->query($sql);
            if($res){
                $notice = 'Success!';
            }else{
                $notice = 'Failed!';
            }
        }
        require '../include/getuserdata.php';
    }else{
        $sql = "SELECT * FROM `user` WHERE `username`='$username'";
        $res = $conn->query($sql);
        if($res){
            $row = mysqli_fetch_assoc($res);
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/head.php';
    ?>
    <title>Account<?php echo $title_dash; ?></title>
    <style>
    </style>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <div class="announcement">
        <div class="col-12">
            <h5><b>Account</b></h5>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-10" style="margin:auto;display:table;">
                        <form action="index.php" class="form-control" style="padding:20px;" method="post">
                            <?php
                                if(isset($notice)){
                                    echo '<p class="alert alert-info">'.$notice.'</p>';
                                }
                            ?>
                            <input type="hidden" name="password-change" value="0" required hidden />
                            <label>Username</label>
                            <input type="text" class="form-control" name="username" value="<?php echo $user_row['username']; ?>" readonly />
                            <br>
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $user_row['name']; ?>" required />
                            <br>
                            <button class="btn btn-primary" type="button" onclick="changePassword();">Change Password</button>
                            <br>
                            <div class="hide" id="password-change">
                                <br>
                                <label>Old Password</label>
                                <input type="password" class="form-control" name="old-password" />
                                <label>New Password</label>
                                <input type="password" class="form-control" name="new-password" />
                                <label>Retype New Password</label>
                                <input type="password" class="form-control" name="re-password" />
                            </div>
                            <br>
                            <button class="btn btn-primary" type="submit" name="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
            <?php
                include '../include/footer.php';
            ?>
    </div>
</body>
</html>