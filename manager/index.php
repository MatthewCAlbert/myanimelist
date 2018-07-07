<?php
    require '../include/server.php';
    require '../include/session.checker.php';
    require '../include/getuserdata.php';
    if($user_row['status']!='administrator'){
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
    <title>Manager<?php echo $title_dash; ?></title>
    <style>
        td, th{
            width:100px;
            overflow-x:hidden;
        }
    </style>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <div class="announcement">
        <div class="col-12">
            <h5><b>Manager</b></h5>
        </div>
        <div class="col-12" style="background-color:#FFFEFA;margin:0 0 10px 0;padding:10px 15px;border:1px solid #FAEBCC;border-top:0;font-size:.8em;">
            <span style="color:#8A6D3B;"><span style="color:black"><i class="fas fa-info-circle fa-sm"></i> Info :</span> This website is still under-development, you might encounter some bugs somehow.</span>
        </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="d-flex justify-content-center">
                    <div class="p-1 table-responsive" style="width:100%;">
                        <table class="table-hover table table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>Username</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sql = "SELECT * FROM `user`";
                            $res = $conn->query($sql);
                            if($res){
                                if($res->num_rows>0){
                                    while($row=mysqli_fetch_array($res)){
                                        echo '
                                        <tr>
                                            <td>'.$row['username'].'</td>
                                            <td>'.$row['name'].'</td>
                                            <td>'.$row['status'].'</td>
                                            <td>
                                            <div class="d-flex flex-wrap">
                                            <div class="p-1">
                                            <a href="edit.php?username='.$row['username'].'" class="btn btn-primary"><i class="fas fa-user-edit"></i></a>
                                            </div>
                                            <div class="p-1">
                                            <a href="delete.php?username='.$row['username'].'" class="btn btn-primary"><i class="fas fa-trash-alt"></i></a>
                                            </div>
                                            </div></td>
                                        </tr>';
                                    }
                                }
                            }
                        ?>
                            <tr>
                                <td colspan="4" onclick="href('register.php');" class="text-primary" style="cursor:pointer;"><i class="fas fa-plus"></i> Add New User</td>
                            </tr>
                        </tbody>
                        </table>
                        </div>
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