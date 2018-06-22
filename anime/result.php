<div class="row">
    <div class="col-12">
    <?php
    if( !empty($row['english_title']) ){
        $english_title = ''.$row['english_title'].'';
    }else{
        $english_title = '';
    }
    echo '<h3>'.$row['title'].' <small>ID: '.$row['id'].'</small></h3> '; ?>
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
    <h6><br>Synonyms</h6>
    <p><?php echo $english_title; ?></p>
    <h6><br>Airing Date</h6>
    <p><?php 
    if( !empty($row['date_from']) ){
        echo date('F-Y',strtotime($row['date_from']));
    }else{
        echo '--';
    }
    echo ' to ';
    if( !empty($row['date_to']) ){
        echo date('F-Y',strtotime($row['date_to']));
    }else{
        echo '--';
    }
    ?></p>
    </div>
    <div class="col-md-9 col-sm-12 col-12">
        <p><?php echo $final_tag; ?></p>
        <h6>Number Of Episodes</h6>
        <p><?php echo $row['episode']; ?></p>
        <h6>Description</h6>
        <p class="text-justify"><?php echo $row['description']; ?></p>
        <div class="hr hr-100"></div>
        <h6>Episode Details</h6>
        <?php 
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
                        $eps_desc .= '<textarea class="form-control hide cursor-default" name="epsdesc-'.$i.'" rows="4" cols="50" readonly>'.$row2['description'].'</textarea>';
                        $eps_tags .= '<input type="text" class="form-control hide cursor-default" value="'.$row2['tag'].'" name="epstag-'.$i.'" readonly />';
                        $links = array();
                        $links = explode(',',$row2['link']);
                        foreach( $links as $link ){
                            $eps_link .= '<a href="../watch?a-id='.$row['id'].'&episode='.$i.'" class="">Watch Episode '.$i.'</a><br>';
                        }
                    }
                }
            }
        }
        echo '<br>
        <select id="episode-selector" onclick="changeEpisode(this.value)">
            '.$eps_option.'
        </select>
        <br><br>
        <div id="episode-text">
            '.$eps_desc.'
        </div>
        <div id="episode-tag">
            <label>Episode Tags</label>
            '.$eps_tags.'
        </div>
        <div id="episode-link">
            <label>Episode Links</label><br>
            '.$eps_link.'
        </div>
        <br>';
        ?>
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
                    <img src="'.$images[$i].'" alt="'.$i.'" />
                    </div>';
                }else{
                    $gallery_indicator .= '<li data-target="#demo" data-slide-to="'.$i.'"></li>';
                    $gallery_img .= '
                    <div class="carousel-item">
                    <img src="'.$images[$i].'" alt="'.$i.'" />
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