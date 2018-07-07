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
    <title>Edit Character<?php echo $title_dash; ?></title>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <div class="announcement">
        <div class="col-12">
            <h5><b>Edit Character</b></h5>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php
                            $back_button = '<button class="btn btn-primary" type="button" onclick="window.history.back()"><i class="fa fa-chevron-left"></i> Back</button><br><br>';
                            if(isset($_POST['submit'])){
                                $id = mysqli_escape_string($conn,$_POST['id']);
                                $name = mysqli_escape_string($conn,$_POST['name']);
                                $image_link = mysqli_escape_string($conn,$_POST['image-link']);
                                $chara_desc =  mysqli_escape_string($conn,$_POST['summary']);
                                $chara_tags =  mysqli_escape_string($conn,$_POST['chara-tags']);
                                $appeared = mysqli_escape_string($conn,$_POST['appeared']);
                                $rating =  mysqli_escape_string($conn,$_POST['rating']);
                                $anime_id =  mysqli_escape_string($conn,$_POST['anime_id']);
                                $sql = "UPDATE `chara` SET `name`='$name',`image_link`='$image_link',`description`='$chara_desc',`tag`='$chara_tags',`appeared`='$appeared',`rating`='$rating',`anime_id`='$anime_id' WHERE `id`='$id'";
                                $res = $conn->query($sql);
                                if($res){
                                    echo '<p class="alert alert-success">Character update success!</p>';
                                }else{
                                    echo '<p class="alert alert-danger">Character update failed!</p>';
                                }
                            }else if(isset($_POST['delete'])){
                                $id = mysqli_escape_string($conn,$_POST['id']);
                                $sql = "DELETE FROM `chara` WHERE `id`='$id'";
                                $res = $conn->query($sql);
                                if($res){
                                    echo '<p class="alert alert-success">Character deletion success!</p>';
                                }else{
                                    echo '<p class="alert alert-danger">Character deletion failed!</p>';
                                }
                            }
                            echo $back_button;
                        ?>
                        <form class="form-control" method="post" action="edit.php">
                        <?php
                            if(isset($_GET['id'])){
                                $id = mysqli_escape_string($conn,$_GET['id']);
                                $sql = "SELECT * FROM `chara` WHERE `id`='$id'";
                                $res = $conn->query($sql);
                                $warn_text = "(Please seperate tags with comma otherwise it won't work.)";
                                if($res){
                                    if($res->num_rows > 0){
                                        while($row = mysqli_fetch_array($res)){
                                            echo '
                                            <input type="text"  value="'.$row['id'].'" class="form-control" name="id" hidden/>
                                            <label>Name</label>
                                            <input type="text"  value="'.$row['name'].'" class="form-control" name="name"/>
                                            <label>Rating</label>
                                            <input type="number" class="form-control" name="rating" value="'.$row['rating'].'"/>
                                            <label>Summary or Description</label>
                                            <textarea class="form-control" name="summary" rows="4" cols="50" >'.$row['description'].'</textarea>
                                            <label>Appeared on Anime (ID) <small>'.$warn_text.'</small></label>
                                            <input type="text" value="'.$row['anime_id'].'" class="form-control" name="anime_id"/
                                            <label>Chara Tags <small>'.$warn_text.'</small></label>
                                            <input type="text" class="form-control" value="'.$row['tag'].'" name="chara-tags" />
                                            <label>Appeared on Episode <small>'.$warn_text.'</small></label>
                                            <input type="text" value="'.$row['appeared'].'" class="form-control" name="appeared"/>
                                            <label>Image Links <small>'.$warn_text.'</small></label>
                                            <input type="text" class="form-control" value="'.$row['image_link'].'" name="image-link"/>
                                            <div class="hr hr-100"></div>
                                            <br>
                                            <button class="btn btn-primary" name="submit" type="submit"><i class="fas fa-cloud-upload-alt"></i> Update</button>
                                            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal"><i class="fas fa-trash-alt"></i> Delete</button><br><br>
                                            <!-- The Modal -->
                                            <div class="modal" id="myModal">
                                            <div class="modal-dialog">
                                                <div class="modal-content">

                                                <!-- Modal Header -->
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Are You Sure?</h4>
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                </div>

                                                <!-- Modal body -->
                                                <div class="modal-body">
                                                <p>These action cannot be rolled back!</p>
                                                <button class="btn btn-danger" name="delete" type="submit"><i class="fas fa-trash-alt"></i> Delete Now</button>
                                                </div>

                                                <!-- Modal footer -->
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                </div>

                                                </div>
                                            </div>
                                            </div>
                                            ';
                                        }

                                    }else{
                                        echo "<h1>404 Content Not Found!</h1>
                                        <h4>The Anime You Are Looking for Doesn"."'"."t Exists!</h4>
                                        ";
                                    }
                                }
                            }
                            if(!isset($_GET['id']) && !isset($_POST['submit']) && !isset($_POST['delete'])){
                                header('Location: ..');
                                exit();
                            }
                        ?>
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