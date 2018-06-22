<div class="row">
    <div class="col-12">
    <?php
        $animes = array();
        $animes = explode(',',$row['anime_id']);
        $appear_on_anime = '';
        $i = 1 ;
        foreach($animes as $anime){
            if(!empty($anime)){
                $sql2 = "SELECT `title`,`id` FROM `anime` WHERE `id`='$anime'";
                $res2 = $conn->query($sql2);
                if( $res2 ){
                    if( $res2->num_rows>0 ){
                        $row2 = mysqli_fetch_assoc($res2);
                        $appear_on_anime .= '<a href="../anime/view.php?id='.$row2['id'].'" >'.$row2['title'].'</a> ';
                        if( $i != count($animes) ){
                            $appear_on_anime .=", ";
                        }
                        $i++;
                    }
                }
            }
        }

        if( $row['rating'] <= 0 || empty($row['rating']) ){
            $rating_final = ' <i>Not Rated Yet</i>';
        }else if( $row['rating'] > 10){
            $rating_final = 10;
        }else{
            $rating_final = $row['rating']; 
        }
    echo '<h3>'.$row['name'].' <small><i class="fas fa-star fa-sm" style="color:orange;"></i>'.$rating_final.' (ID: '.$row['id'].')</small></h3> '; ?>
    </div>
    <div class="col-md-3 col-sm-12">
        <?php
        $images = array();
        $images = explode(',',$row['image_link']);
        $image_div = '
            <img width="200px" class="img-expand" src="'.$images[0].'">
        ';
        echo $image_div;

        //getting tags
        $final_tag =''; $tag_rows = array();
        $tag_rows = explode(',',$row['tag']);
        for( $i = 0 ; $i < count($tag_rows) ; $i++ ){
            /* comma style
            if( $i == count($tag_rows)-1 ){
                $final_tag .= ucwords($tag_rows[$i]);
            }else{
                $final_tag .=  ucwords($tag_rows[$i]).", ";
            }
            */
            if(!empty($tag_rows[$i])){
            switch($tag_rows[$i]){
                case 'favorite' : $final_tag .= '<span class="sm-icon md" style="background-color:maroon;">'.ucwords($tag_rows[$i]).'</span>';break;
                default : $final_tag .= '<span class="sm-icon md">'.ucwords($tag_rows[$i]).'</span>';break;
            }
            }
        }
        ?>
    </div>
    <div class="col-md-9 col-sm-12 col-12">
        <h6>Anime</h6>
        <p><?php echo $appear_on_anime; ?></p>
        <p><?php echo $final_tag; ?></p>
        <h6>Appeared on Episode</h6>
        <p><?php echo $row['appeared']; ?></p>
        <h6>Description</h6>
        <p class="text-justify"><?php echo $row['description']; ?></p>
        <div class="hr hr-100"></div>
        <h6>Gallery</h6>
        <?php
            $gallery_indicator = '';
            $gallery_img = '';
            for($i = 0 ; $i < count($images); $i++){
                if($i == 0){
                    $gallery_indicator .= '<li data-target="#demo" data-slide-to="0" class="active"></li>';
                    $gallery_img .= '
                    <div class="carousel-item active">
                    <img src="'.$images[$i].'" width="200px" alt="'.$i.'" />
                    </div>';
                }else{
                    $gallery_indicator .= '<li data-target="#demo" data-slide-to="'.$i.'"></li>';
                    $gallery_img .= '
                    <div class="carousel-item">
                    <img src="'.$images[$i].'" width="100px" alt="'.$i.'" />
                    </div>';
                }
            }
        ?>
        <div id="demo" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ul class="carousel-indicators">
            <?php echo $gallery_indicator; ?>
        </ul>
        
        <!-- The slideshow -->
        <div class="carousel-inner">
            <?php echo $gallery_img; ?>
        </div>
        
        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#demo" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#demo" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
        </div>
    </div>
</div>