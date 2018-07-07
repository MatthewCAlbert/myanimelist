<?php
    require '../include/server.php';
    require '../include/session.checker.php';
    require '../include/getuserdata.php';
    if($user_row['status']!='administrator'){
        header('Location: ..');
        exit();
    }
    if(isset($_POST['submit'])){
        $username = mysqli_escape_string($conn,$_POST['username']);
        $name = mysqli_escape_string($conn,$_POST['name']);
        $password = mysqli_escape_string($conn,$_POST['password']);
        $status = mysqli_escape_string($conn,$_POST['status']);
        $hashedPw = password_hash($password,PASSWORD_BCRYPT);
        $sql = "INSERT INTO `user`(`username`,`name`,`password`,`status`) VALUES ('$username','$name','$hashedPw','$status')";
        $res = $conn->query($sql);
        if($res){
            if(count($res) > 0){
                header('Location: ../manager?success');
                exit();
            }else{
                header('Location: ../manager?error');
                exit();
            }
        }else{
            header('Location: ../manager?error-server');
            exit();
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/head.php';
    ?>
    <title>Register<?php echo $title_dash; ?></title>
    <style>
    </style>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <div class="announcement">
        <div class="col-12">
            <h5><b>Register</b></h5>
        </div>
        <div class="col-12" style="background-color:#FFFEFA;margin:0 0 10px 0;padding:10px 15px;border:1px solid #FAEBCC;border-top:0;font-size:.8em;">
            <span style="color:#8A6D3B;"><span style="color:black"><i class="fas fa-info-circle fa-sm"></i> Info :</span> This website is still under-development, you might encounter some bugs somehow.</span>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-lg-10" style="margin:auto;display:table;">
                        <form action="register.php" class="form-control" style="padding:20px;" method="post">
                            <label>Username</label>
                            <input type="text" class="form-control" name="username" required />
                            <br>
                            <label>Name</label>
                            <input type="text" class="form-control" name="name" required />
                            <br>
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" required />
                            <br>
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="member">Member</option>
                                <option value="administrator">Admin</option>
                            </select>
                            <br>
                            <button class="btn btn-primary" type="submit" name="submit">Register</button>
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