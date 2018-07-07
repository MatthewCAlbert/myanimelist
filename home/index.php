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
    ?>
    <title>Home<?php echo $title_dash; ?></title>
    <style>
    </style>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <div class="announcement">
        <div class="col-12">
            <h5><b>Welcome to MyAnimeList</b></h5>
        </div>
        <div class="col-12" style="background-color:#FFFEFA;margin:0 0 10px 0;padding:10px 15px;border:1px solid #FAEBCC;border-top:0;font-size:.8em;">
            <span style="color:#8A6D3B;"><span style="color:black"><i class="fas fa-info-circle fa-sm"></i> Info :</span> This website is still under-development, you might encounter some bugs somehow.</span>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-9">
                        <h6><i class="fas fa-random"></i> Random Anime</h6>
                        <div class="hr hr-light" style="margin-bottom:10px;"></div>
                        <div class="slider-1">
                        <?php
                            $sql = "SELECT * FROM `anime`";
                            $res = $conn->query($sql);
                            $anime_count = $res->num_rows;

                            $sql = "SELECT * FROM `anime` WHERE ";
                            $selected_id =  array();
                            $gallery = 10;
                            $res = $conn->query($sql);
                            if($res){
                                if($res->num_rows > 0){
                                    for($i=0 ; $i < $gallery ; $i++){
                                        $random_number = rand(1,$anime_count);
                                        while( in_array($random_number,$selected_id) ){
                                            $random_number = rand(1,$anime_count);
                                        }
                                        //echo $random_number.'<br>';
                                        array_push($selected_id,$random_number);
        
                                        if( $i < $gallery-1 ){
                                            $sql.=" `id`='$random_number' OR ";
                                        }else{
                                            $sql.=" `id`='$random_number'";
                                        }
                                    }
                                    while($row=mysqli_fetch_array($res)){
                                        //fetch image
                                        $images = array();
                                        $images = explode(',',$row['image_link']);
                                        echo '<div class="gallery-wrapper"><a href="../anime/view.php?id='.$row['id'].'" class="horizontal-list"><span class="gallery-title">'.$row['title'].'</span>
                                        <img class="gallery-img lazyloaded" src="'.$images[0].'" />
                                        </a></div>';
                                    }
                                }else{
                                    echo '<div class="gallery-wrapper">Nothing Found</div>';
                                }
                            }
                        ?>
                        </div>
                        <h6><i class="far fa-clock"></i> Latest Anime</h6>
                        <div class="hr hr-light" style="margin-bottom:10px;"></div>
                        <div class="slider-1">
                        <?php
                            $sql = "SELECT * FROM `anime`";
                            $res = $conn->query($sql);
                            $anime_count = $res->num_rows;

                            $sql = "SELECT * FROM `anime` WHERE `date_from` IS NOT NULL ORDER BY `date_from` DESC LIMIT $gallery";
                            $selected_id =  array();
                            $res = $conn->query($sql);
                            if($res){
                                if($res->num_rows > 0){
                                    while($row=mysqli_fetch_array($res)){
                                        //fetch image
                                        $images = array();
                                        $images = explode(',',$row['image_link']);
                                        echo '<div class="gallery-wrapper"><a href="../anime/view.php?id='.$row['id'].'" class="horizontal-list"><span class="gallery-title">'.$row['title'].'</span>
                                        <img class="gallery-img lazyloaded" src="'.$images[0].'" />
                                        </a></div>';
                                    }
                                }else{
                                    echo '<div class="gallery-wrapper">Nothing Found</div>';
                                }
                            }
                        ?>
                        </div>
                        <h6><i class="far fa-star"></i> Top Rated Anime</h6>
                        <div class="hr hr-light" style="margin-bottom:10px;"></div>
                        <div class="slider-1">
                        <?php
                            $sql = "SELECT * FROM `anime`";
                            $res = $conn->query($sql);
                            $anime_count = $res->num_rows;

                            $sql = "SELECT * FROM `anime` WHERE `rating` IS NOT NULL ORDER BY `rating` DESC LIMIT $gallery";
                            $selected_id =  array();
                            $res = $conn->query($sql);
                            if($res){
                                if($res->num_rows > 0){
                                    while($row=mysqli_fetch_array($res)){
                                        //fetch image
                                        $images = array();
                                        $images = explode(',',$row['image_link']);
                                        echo '<div class="gallery-wrapper"><a href="../anime/view.php?id='.$row['id'].'" class="horizontal-list"><span class="gallery-title">'.$row['title'].'</span>
                                        <img class="gallery-img lazyloaded" src="'.$images[0].'" />
                                        </a></div>';
                                    }
                                }else{
                                    echo '<div class="gallery-wrapper">Nothing Found</div>';
                                }
                            }
                        ?>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3">
                        <div style="padding:5px;height:100%;">
                        <span style="background-color:#E1E7F5;display:block;padding:15px;border-radius:5px;">
                        <h5 style="font-family:'Viga';"><i class="fas fa-list fa-sm"></i> Entry</h5>
                        <div class="hr ht-80 hr-light" style="margin-bottom:10px;"></div>
                        <?php
                            $sql = "SELECT * FROM `chara`";
                            $res = $conn->query($sql);
                            $chara_count = $res->num_rows;
                            $sql = "SELECT * FROM `episode`";
                            $res = $conn->query($sql);
                            $episode_count = $res->num_rows;
                            echo '<h6>Registered Anime : '.$anime_count.'</h6>';
                            echo '<h6>Registered Character : '.$chara_count.'</h6>';
                            echo '<h6>Total Episode : '.$episode_count.'</h6>';
                        ?>
                        </span>
                        </div>
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
            $('.slider-1').slick({
                dots: true,
                infinite: false,
                speed: 300,
                slidesToShow: 5,
                slidesToScroll: 5,
                responsive: [
                    {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        infinite: true,
                        dots: true
                    }
                    },
                    {
                    breakpoint: 840,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true,
                        dots: true
                    }
                    },
                    {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                    },
                    {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });
        });
    </script>
</body>
</html>