<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/server.php';
        require '../include/head.php';
        $warning = '';
        $echo_all ="";
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
            </div>
        </section>
            <?php
                include '../include/footer.php';
            ?>
    </div>
</body>
</html>