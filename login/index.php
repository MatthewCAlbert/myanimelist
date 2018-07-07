<?php
    require '../include/server.php';
    if(isset($_SESSION['myanimelist_username'])){
        header('Location: ..');
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/head.php';
    ?>
    <title>Login<?php echo $title_dash; ?></title>
    <style>
    </style>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <div class="announcement">
        <div class="col-12">
            <h5><b>Welcome to MyHList.net</b></h5>
        </div>
        <div class="col-12" style="background-color:#FFFEFA;margin:0 0 10px 0;padding:10px 15px;border:1px solid #FAEBCC;border-top:0;font-size:.8em;">
            <span style="color:#8A6D3B;"><span style="color:black"><i class="fas fa-info-circle fa-sm"></i> Info :</span> This website is still under-development, you might encounter some bugs somehow.</span>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-8 col-xl-6" style="display:table;margin:auto;">
                        <form action="login.php" class="form-control" method="post" style="border-color:#2E51A2;padding:25px;outline:10px solid #E1E7F5;">
                            <?php
                            if($private_col == true){
                                echo '<p class="alert alert-warning">This website list is a private collection!</p>';
                            }
                            ?>
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" />
                            <br>
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" />
                            <br>
                            <input type="checkbox" id="remember" name="remember" />
                            <label for="remember"><small>Always stay login in?</small></label>
                            <br>
                            <button class="btn btn-primary" type="submit" name="submit" style="display:table;margin:auto;width:100px;">Login</button>
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