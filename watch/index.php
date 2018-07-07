<?php
    require '../include/server.php';
    require '../include/getuserdata.php';
    require '../include/private.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/head.php';
        $warning = '';
        $echo_all ="";
        $form_enable = false; //validator
        $update_enable = true; //enabler
        if(  (isset($_GET['a-id']) && isset($_GET['episode'])) || isset($_GET['id'])  ){
            if(isset($_GET['a-id']) && isset($_GET['episode'])){
                $id = mysqli_escape_string($conn,$_GET['a-id']);
                $eps = mysqli_escape_string($conn,$_GET['episode']);
                $sql = "SELECT * FROM `episode` WHERE `anime_id`='$id' AND `episode`='$eps'";
            }else{
                $id = mysqli_escape_string($conn,$_GET['id']);
                $sql = "SELECT * FROM `episode` WHERE `id`='$id'";
            }
            $res = $conn->query($sql);
            if($res){
                if($res->num_rows > 0){
                    $row = mysqli_fetch_assoc($res);
                    //getting anime info
                    $anime_id = $row['anime_id'];
                    $eps = $row['episode'];
                    $sql = "SELECT * FROM `anime` WHERE `id`='$anime_id'";
                    $res_a = $conn->query($sql);
                    if($res_a){
                        if($res_a->num_rows > 0){
                            $form_enable = true;
                            $row_a = mysqli_fetch_assoc($res_a);
                            $echo_all .='<h5>'.$row_a['title'].' Episode '.$eps.'</h5>';
                            $echo_all .= '<select onchange="href(this.value);">';
                            $episode_count = $row_a['episode'];
                            for($i = 1 ; $i <= $episode_count ; $i++){
                                $link_fix = "../watch?a-id=$anime_id&episode=$i";
                                if( $i == $eps ){
                                    $selected = ' selected ';
                                }else{
                                    $selected = '';
                                }
                                $echo_all .= '<option value="'.$link_fix.'" '.$selected.' >Episode '.$i.'</option>';
                            }
                            $echo_all .= '</select><br><br>';
                            if( isset($_POST['submit']) ){
                                $desc = mysqli_escape_string($conn,$_POST['desc']);
                                $tags = mysqli_escape_string($conn,$_POST['tag']);
                                $a_id = $row['anime_id'];
                                $eps = $row['episode'];
                                $id = $row['id'];
                                $sql = "UPDATE `episode` SET `description`='$desc',`tag`='$tags' WHERE `anime_id`='$a_id' AND `episode`='$eps'";
                                $res_b = $conn->query($sql);
                                if($res_b){
                                    $warning ='<p class="alert alert-success">Update Success!</p>';
                                }else{
                                    $warning ='<p class="alert alert-warning">Update Failed!</p>';
                                }
                                $sql = "SELECT * FROM `episode` WHERE `id`='$id'";
                                $res = $conn->query($sql);
                                if($res){
                                    if($res->num_rows > 0){
                                        $row = mysqli_fetch_assoc($res);
                                    }else{
                                        $warning .='<p class="alert alert-warning">Oops something went wrong!</p>';
                                    }
                                }else{
                                    $warning .='<p class="alert alert-warning">Oops something went wrong!</p>';
                                }
                            }
                        }else{
                            $warning = '
                            <h1>404 Data Not Found</h1>
                            <p><i>Baka yaro</i></p>';
                        }
                    }

                    //fetching links
                    $links=array();
                    $links = explode(',',$row['link']);
                    if(isset($_GET['source'])){
                        $source = $_GET['source'];
                    }else{
                        $source = 1;
                    }
                    for($i = 1; $i <= count($links) ; $i++ ){
                        if( !empty($links[$i-1]) ){
                            $link = "'".$links[$i-1]."'";
                            $echo_all .= '<button class="btn btn-info" style="margin-right:10px;" onclick="changeVideoLink('.$link.');" id="source-'.$i.'">Source 1</button>';
                            $echo_all .= '<button class="btn btn-info" style="margin-right:10px;" onclick="copyToClipboard('.$link.');" id="source-'.$i.'">Source 1 RAW</button>';
                        }
                    }
                }else{
                    $warning = '
                    <h1>404 Data Not Found</h1>
                    <p><i>Baka yaro</i></p>';
                }
            }
        }
        $echo_all .= $warning;
    ?>
    <title>Watch <?php echo $row_a['title'].' Episode '.$row['episode'].$title_dash; ?></title>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <div class="announcement">
        <div class="col-12">
            <h5><b>Watch</b></h5>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php
                            echo $echo_all;
                        ?>
                        <br><br>
                        <div id="video-wrapper">
                        <video id="video-player" width="100%" controls>
                            <source src="<?php if( (isset($_GET['a-id']) && isset($_GET['episode'])) || isset($_GET['id']) ){echo $links[0];} ?>" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                    <br>
                        <div class="d-flex flex-wrap">
                            <div class="p-1"><button class="btn btn-primary" onclick="toggleSlide('1');">Anime Information</button></div>
                            <div class="p-1"><button class="btn btn-primary" onclick="toggleSlide('2');">Episode Information</button></div>
                        </div>
                    </div>
                    <div class="col-12 slide-hidden" id="slide-container-1">
                        <?php
                            echo '
                            <br>
                            <a href="../anime/view.php?id='.$row_a['id'].'"><h5>'.$row_a['title'].'</h5></a>
                            <div class="hr hr-light" style="margin-bottom:10px;"></div>
                            <h5>Summary</h5>
                            <p class="description text-justify">'.$row_a['description'].'</p>
                            ';
                        ?>
                    </div>
                    <div class="col-12 slide-hidden" id="slide-container-2">
                        <br>
                        <div style="border:1px solid rgba(0,0,0,0.3);padding: 0 20px 20px 20px;">
                        <br>
                        <?php
                            if($form_enable == true && $update_enable == true){
                                include 'update-form.php';
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <br>
                        <h5>Similar</h5>
                        <div class="hr-light hr" style="margin-bottom:10px;"></div>
                        <?php
                            $sql = "SELECT * FROM `anime` WHERE ";
                            $selected_id =  array();
                            $gallery = 5;
                            
                            $tags = explode(',',$row_a['tag']);
                            $valid_tags = array();
                            foreach($tags as $tag){
                                if( !empty($tag) ){
                                    array_push($valid_tags,$tag);
                                }
                            }

                            for($i=0 ; $i < count($valid_tags) ; $i++){
                                if( $i < count($valid_tags)-1 ){
                                    $sql.=" `tag` LIKE '%".$valid_tags[$i]."%' OR ";
                                }else{
                                    $sql.=" `tag` LIKE '%".$valid_tags[$i]."%'";
                                }
                            }
                            $res = $conn->query($sql);
                            if($res){
                                $anime_count = $res->num_rows;
                            }

                            for($i=0 ; $i < $gallery ; $i++){
                                $random_number = rand(1,$anime_count);
                                while( in_array($random_number,$selected_id) ){
                                    $random_number = rand(1,$anime_count);
                                }
                                //echo $random_number.'<br>';
                                array_push($selected_id,$random_number);
                            }

                            $select_check = 1;
                            while($row_b = mysqli_fetch_array($res)){
                                if( in_array($select_check,$selected_id) ){
                                    //fetch image
                                    $images = array();
                                    $images = explode(',',$row_b['image_link']);
                                    echo '<div style="margin: 10px 0;background-color:#E1E7F5;border-radius:5px;padding:10x 0"><a href="../anime/view.php?id='.$row_b['id'].'" style="padding:20px;"><img src="'.$images[0].'" width="70px" height="100px" class="small-img" />
                                    <span style="color:black;">'.$row_b['title'].'</span>
                                    </a></div>';
                                }
                                $select_check++;
                            }
                        ?>
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