<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/server.php';
        require '../include/head.php';
    ?>
    <title>Entry<?php echo $title_dash; ?></title>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                            if(isset($_POST['submit-anime'])){
                                $sql = "SELECT `tag` FROM `anime`";
                                $res = $conn->query($sql);
                                if($res){
                                    $entry = $res->num_rows + 1;
                                }
                                $jp_name = mysqli_escape_string($conn,$_POST['name']);
                                $en_name = mysqli_escape_string($conn,$_POST['english-name']);
                                $episode_count = mysqli_escape_string($conn,$_POST['episode-count']);
                                $image_link = mysqli_escape_string($conn,$_POST['image-link']);
                                $anime_desc =  mysqli_escape_string($conn,$_POST['summary']);
                                $anime_tags =  mysqli_escape_string($conn,$_POST['anime-tags']);
                                $date_from =  mysqli_escape_string($conn,$_POST['date-from']);
                                $date_to =  mysqli_escape_string($conn,$_POST['date-to']);
                                $rating =  mysqli_escape_string($conn,$_POST['rating']);
                                $sql = "INSERT INTO `anime`(`title`,`english_title`,`episode`,`description`,`tag`,`image_link`,`date_from`,`date_to`,`rating`) VALUES ('$jp_name','$en_name','$episode_count','$anime_desc','$anime_tags','$image_link','$date_from','$date_to','$rating')";
                                $res = $conn->query($sql);
                                if($res){
                                    echo '<p class="alert alert-success">Anime entry success!</p>';
                                }else{
                                    echo '<p class="alert alert-danger">Anime entry failed!</p>';
                                }
                                for( $i = 1 ; $i <= $episode_count ; $i++ ){
                                    $episode_tags = mysqli_escape_string($conn,$_POST["epstag-$i"]);
                                    $episode_desc = mysqli_escape_string($conn,$_POST["epsdesc-$i"]);
                                    $episode_links = mysqli_escape_string($conn,$_POST["epslink-$i"]);
                                    $sql = "INSERT INTO `episode`(`anime_id`,`episode`,`description`,`link`,`tag`) VALUES ('$entry','$i','$episode_desc','$episode_links','$episode_tags')";
                                    $res = $conn->query($sql);
                                    if($res){
                                        echo '<p class="alert alert-success">Episode '.$i.' entry success!</p>';
                                    }else{
                                        echo '<p class="alert alert-danger">Episode '.$i.' entry failed!</p>';
                                    }
                                }
                            }
                            if(isset($_POST['submit-character'])){
                                $name = mysqli_escape_string($conn,$_POST['name']);
                                $anime_id = mysqli_escape_string($conn,$_POST['anime-id']);
                                $appeared_on = mysqli_escape_string($conn,$_POST['episode-tag']);
                                $chara_desc = mysqli_escape_string($conn,$_POST['chara-desc']);
                                $chara_tags = mysqli_escape_string($conn,$_POST['chara-tag']);
                                $image_link = mysqli_escape_string($conn,$_POST['image-link']);
                                $rating =  mysqli_escape_string($conn,$_POST['rating']);

                                $sql = "SELECT * FROM `anime` WHERE `id`='$anime_id'";
                                $res = $conn->query($sql);
                                if($res){
                                    $approved = $res->num_rows;
                                }else{
                                    $approved = 0;
                                }

                                if( $approved > 0 ){
                                    $sql = "INSERT INTO `chara`(`anime_id`,`appeared`,`name`,`description`,`tag`,`image_link`,`rating`) VALUES ('$anime_id','$appeared_on','$name','$chara_desc','$chara_tags','$image_link','$rating')";
                                    $res = $conn->query($sql);
                                    if($res){
                                        echo '<p class="alert alert-success">Character entry success!</p>';
                                    }else{
                                        echo '<p class="alert alert-danger">Character entry failed!</p>';
                                    }
                                }else{
                                    echo '<p class="alert alert-danger">Anime ID not found!</p>';
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="row">
                    <select class="form-control" onchange="switchFilterOption();changeEntry(this.value);" id="type-filter">
                        <option value="anime">Anime</option>
                        <option value="chara">Character</option>
                    </select>
                    <br><br>
                    <div class="col" id="anime">
                        <h4>Add Anime</h4>
                        <form class="form-control" method="post" action="index.php">
                            <label>Japanese Name</label>
                            <input type="text" class="form-control" name="name"/>
                            <label>English Name</label>
                            <input type="text" class="form-control" name="english-name"/>
                            <label>Airing Date</label>
                            <input type="text" class="form-control" value="" name="date-from" placeholder="yyyy-mm"/>
                                To
                            <input type="text" class="form-control" value="" name="date-to" placeholder="yyyy-mm"/>
                            <label>Rating</label>
                            <input type="number" min="0" class="form-control" name="rating"/>
                            <label>Summary or Description</label>
                            <textarea class="form-control" name="summary" rows="4" cols="50"></textarea>
                            <label>Anime Tags <small>(Please seperate tags with comma otherwise it won't work.)</small></label>
                            <input type="text" class="form-control" name="anime-tags" />
                            <label>Episodes</label>
                            <input type="number" min="1" max="1000" value="1" class="form-control" onchange="changeEpisodeCount(this.value)" name="episode-count"/>
                            <label>Image Links <small>(Please seperate tags with comma otherwise it won't work.)</small></label>
                            <input type="text" class="form-control" name="image-link"/>
                            <div class="hr hr-100"></div>
                            <label>Episode Information</label><br>
                            <select id="episode-selector" onclick="changeEpisode(this.value)">
                                <option value="1">Episode 1</option>
                            </select>
                            <br><br>
                            <div id="episode-text">
                                <textarea class="form-control" name="epsdesc-1" rows="4" cols="50"></textarea>
                            </div>
                            <div id="episode-tag">
                                <label>Episode Tags <small>(Please seperate tags with comma otherwise it won't work.)</small></label>
                                <input type="text" class="form-control" name="epstag-1" />
                            </div>
                            <div id="episode-link">
                                <label>Episode Links <small>(Please seperate tags with comma otherwise it won't work.)</small></label>
                                <input type="text" class="form-control" name="epslink-1" />
                            </div>
                            <br>
                            <button class="btn btn-primary" name="submit-anime" type="submit"><i class="fa fa fa-plus"></i> Submit</button>
                        </form>
                    </div>
                    <div class="col hide" id="chara">
                        <h4>Add Character</h4>
                        <form class="form-control" method="post" action="index.php">
                            <label>Name</label>
                            <input type="text" class="form-control" name="name"/>
                            <label>Anime ID <small>(Get anime ID at the anime search page.)</small></label>
                            <input type="number" min="1" class="form-control" name="anime-id"/>
                            <label>Appear on Episode <small>(Please seperate tags with comma otherwise it won't work.)</small></label>
                            <input type="text" class="form-control" name="episode-tag" />
                            <label>Description</label>
                            <textarea class="form-control" name="chara-desc" rows="4" cols="50"></textarea>
                            <label>Rating</label>
                            <input type="number" min="0" class="form-control" name="rating"/>
                            <label>Character Tags <small>(Please seperate tags with comma otherwise it won't work.)</small></label>
                            <input type="text" class="form-control" name="chara-tag" />
                            <label>Image Links <small>(Please seperate tags with comma otherwise it won't work.)</small></label>
                            <input type="text" class="form-control" name="image-link"/>
                            <br>
                            <button class="btn btn-primary" name="submit-character" type="submit"><i class="fa fa-plus"></i> Submit</button>
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
            applyFilterOption();
        });
    </script>
</body>
</html>