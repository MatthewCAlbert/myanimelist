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
    <title>Edit Anime<?php echo $title_dash; ?></title>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <div class="announcement">
        <div class="col-12">
            <h5><b>Edit Anime</b></h5>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php
                            $back_button = '<button class="btn btn-primary" type="button" onclick="window.history.back()"><i class="fa fa-chevron-left"></i> Back</button><br><br>';
                            if(isset($_POST['submit-anime'])){
                                $id = mysqli_escape_string($conn,$_POST['anime_id']);
                                $jp_name = mysqli_escape_string($conn,$_POST['name']);
                                $en_name = mysqli_escape_string($conn,$_POST['english-name']);
                                $episode_count = mysqli_escape_string($conn,$_POST['episode-count']);
                                $image_link = mysqli_escape_string($conn,$_POST['image-link']);
                                $anime_desc =  mysqli_escape_string($conn,$_POST['summary']);
                                $anime_tags =  mysqli_escape_string($conn,$_POST['anime-tags']);
                                $date_from =  mysqli_escape_string($conn,$_POST['date-from']);
                                $date_to =  mysqli_escape_string($conn,$_POST['date-to']);
                                $rating =  mysqli_escape_string($conn,$_POST['rating']);
                                $prev_episode =  mysqli_escape_string($conn,$_POST['prev-episode']);
                                $sql = "UPDATE `anime` SET `title`='$jp_name',`english_title`='$en_name',`episode`='$episode_count',`description`='$anime_desc',`tag`='$anime_tags',`image_link`='$image_link',`date_from`='$date_from',`date_to`='$date_to',`rating`='$rating' WHERE `id`='$id'";
                                $res = $conn->query($sql);
                                if($res){
                                    echo '<p class="alert alert-success">Anime update success!</p>';
                                }else{
                                    echo '<p class="alert alert-danger">Anime update failed!</p>';
                                }
                                if( $episode_count == $prev_episode ){
                                    for( $i = 1 ; $i <= $episode_count ; $i++ ){
                                        $episode_tags = mysqli_escape_string($conn,$_POST["epstag-$i"]);
                                        $episode_desc = mysqli_escape_string($conn,$_POST["epsdesc-$i"]);
                                        $episode_links = mysqli_escape_string($conn,$_POST["epslink-$i"]);
                                        $sql = "UPDATE `episode` SET `description`='$episode_desc',`link`='$episode_links',`tag`='$episode_tags' WHERE `episode`='$i' AND `anime_id`='$id' ";
                                        $res = $conn->query($sql);
                                        if($res){
                                            echo '<p class="alert alert-success">Episode '.$i.' update success!</p>';
                                        }else{
                                            echo '<p class="alert alert-danger">Episode '.$i.' update failed!</p>';
                                        }
                                    }
                                }else{
                                    if($episode_count > $prev_episode){
                                        for( $i = $prev_episode+1 ; $i <= $episode_count ; $i++ ){
                                            $episode_tags = mysqli_escape_string($conn,$_POST["epstag-$i"]);
                                            $episode_desc = mysqli_escape_string($conn,$_POST["epsdesc-$i"]);
                                            $episode_links = mysqli_escape_string($conn,$_POST["epslink-$i"]);
                                            $sql = "INSERT INTO `episode`(`anime_id`,`episode`,`description`,`link`,`tag`) VALUES ('$id','$i','$episode_desc','$episode_links','$episode_tags')";
                                            $res = $conn->query($sql);
                                            if($res){
                                                echo '<p class="alert alert-success">Episode '.$i.' entry success!</p>';
                                            }else{
                                                echo '<p class="alert alert-danger">Episode '.$i.' entry failed!</p>';
                                            }
                                        }
                                    }else if($episode_count < $prev_episode){
                                        for( $i = $prev_episode ; $i > $episode_count ; $i-- ){
                                            $sql = "DELETE FROM `episode` WHERE `episode`='$i' AND `anime_id`='$id' ";
                                            $res = $conn->query($sql);
                                            if($res){
                                                echo '<p class="alert alert-danger">Episode '.$i.' deletion success!</p>';
                                            }else{
                                                echo '<p class="alert alert-warning">Episode '.$i.' deletion failed!</p>';
                                            }
                                        }
                                    }
                                }
                            }else if(isset($_POST['delete'])){
                                $id = mysqli_escape_string($conn,$_POST['anime_id']);
                                $episode_count = mysqli_escape_string($conn,$_POST['episode-count']);
                                $sql = "DELETE FROM `anime` WHERE `id`='$id'";
                                $res = $conn->query($sql);
                                if($res){
                                    echo '<p class="alert alert-success">Anime deletion success!</p>';
                                }else{
                                    echo '<p class="alert alert-danger">Anime deletion failed!</p>';
                                }
                                for( $i = 1 ; $i <= $episode_count ; $i++ ){
                                    $sql = "DELETE FROM `episode` WHERE `episode`='$i' AND `anime_id`='$id' ";
                                    $res = $conn->query($sql);
                                    if($res){
                                        echo '<p class="alert alert-success">Episode '.$i.' deletion success!</p>';
                                    }else{
                                        echo '<p class="alert alert-danger">Episode '.$i.' deletion failed!</p>';
                                    }
                                }
                            }
                            echo $back_button;
                        ?>
                        <form class="form-control" method="post" action="edit.php">
                        <?php
                            if(isset($_GET['id'])){
                                $id = mysqli_escape_string($conn,$_GET['id']);
                                $sql = "SELECT * FROM `anime` WHERE `id`='$id'";
                                $res = $conn->query($sql);
                                $warn_text = "(Please seperate tags with comma otherwise it won't work.)";
                                if($res){
                                    if($res->num_rows > 0){
                                        while($row = mysqli_fetch_array($res)){
                                            $episode_count = $row['episode'];
                                            $eps_option = '';
                                            $eps_desc = '';
                                            $eps_tags = '';
                                            $eps_link = '';
                                            for( $i = 1 ; $i <= $episode_count ; $i++ ){
                                                $sql2 = "SELECT * FROM `episode` WHERE `episode`='$i' AND `anime_id`='$id'";
                                                $res2 = $conn->query($sql2);
                                                if($res2){
                                                    if($res2->num_rows == 1){
                                                        while($row2 = mysqli_fetch_array($res2) ){
                                                            $eps_option .= '<option value="'.$i.'">Episode '.$i.'</option>';
                                                            $eps_desc .= '<textarea class="form-control hide" name="epsdesc-'.$i.'" rows="4" cols="50">'.$row2['description'].'</textarea>';
                                                            $eps_tags .= '<input type="text" class="form-control hide" value="'.$row2['tag'].'" name="epstag-'.$i.'" />';
                                                            $eps_link .= '<input type="text" class="form-control hide" value="'.$row2['link'].'" name="epslink-'.$i.'" />';
                                                        }
                                                    }
                                                }
                                            }
                                            echo '
                                            <input type="text"  value="'.$row['id'].'" class="form-control" name="anime_id" hidden/>
                                            <label>Japanese Name</label>
                                            <input type="text"  value="'.$row['title'].'" class="form-control" name="name"/>
                                            <label>English Name</label>
                                            <input type="text" class="form-control" value="'.$row['english_title'].'" name="english-name"/>
                                            <label>Airing Date</label>
                                            <input type="text" class="form-control" value="'.$row['date_from'].'" name="date-from" placeholder="yyyy-mm"/>
                                            To
                                            <input type="text" class="form-control" value="'.$row['date_to'].'" name="date-to" placeholder="yyyy-mm"/>
                                            <label>Rating</label>
                                            <input type="number" class="form-control" name="rating" value="'.$row['rating'].'"/>
                                            <label>Summary or Description</label>
                                            <textarea class="form-control" name="summary" rows="4" cols="50" >'.$row['description'].'</textarea>
                                            <label>Anime Tags <small>'.$warn_text.'</small></label>
                                            <input type="text" class="form-control" value="'.$row['tag'].'" name="anime-tags" />
                                            <input type="number" min="1" max="1000" value="'.$row['episode'].'" class="form-control" name="prev-episode" readonly hidden/>
                                            <label>Episodes</label>
                                            <input type="number" min="1" max="1000" value="'.$row['episode'].'" class="form-control" onchange="changeEpisodeCount(this.value)" name="episode-count"/>
                                            <label>Image Links <small>'.$warn_text.'</small></label>
                                            <input type="text" class="form-control" value="'.$row['image_link'].'" name="image-link"/>
                                            <div class="hr hr-100"></div>
                                            <label>Episode Information</label><br>
                                            <select id="episode-selector" onclick="changeEpisode(this.value)">
                                                '.$eps_option.'
                                            </select>
                                            <br><br>
                                            <div id="episode-text">
                                                '.$eps_desc.'
                                            </div>
                                            <div id="episode-tag">
                                                <label>Episode Tags <small>'.$warn_text.'</small></label>
                                                '.$eps_tags.'
                                            </div>
                                            <div id="episode-link">
                                                <label>Episode Links <small>'.$warn_text.'</small></label>
                                                '.$eps_link.'
                                            </div>
                                            <br>
                                            <button class="btn btn-primary" name="submit-anime" type="submit"><i class="fas fa-cloud-upload-alt"></i> Update</button>
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
                            if(!isset($_GET['id']) && !isset($_POST['submit-anime']) && !isset($_POST['delete'])){
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
    <script>
        $(document).ready(function(){
            <?php echo 'episode_count ='.$episode_count; ?>
            //changeEpisodeCount(<?php echo $episode_count; ?>);
            changeEpisode(1);
        });
    </script>
</body>
</html>