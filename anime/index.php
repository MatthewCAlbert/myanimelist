<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/server.php';
        require '../include/head.php';
    ?>
    <title>Anime<?php echo $title_dash; ?></title>
    <!-- jQuery UI Autocomplete Components -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h4>Search Anime</h4>
                        <form class="form-control" method="get" action="../anime" style="padding:20px 10px;">
                            <input type="hidden" name="page" value="1" hidden/>
                            <input type="text" class="form-control" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>" style="margin:0 20px 0 0;" placeholder="Search Anime Title.." id="search-box"/>
                            <input type="text" name="tag" hidden />
                            <?php
                                if(!isset($_GET['search'])){
                                    $edit = true;
                                    $show_image = false;
                                    $portrait = false;
                                }
                                $sql = "SELECT `tag`,`title` FROM `anime`";
                                //$sql .= " UNION SELECT `tag` FROM `episode`";
                                $res = $conn->query($sql);
                                if( $res ){
                                    if( $res->num_rows > 0 ){
                                        $tags = array();
                                        $tags_compilation = array();
                                        $i = 0;
                                        $titles = array();
                                        while($row = mysqli_fetch_array($res)){
                                            $tags_raw = array();
                                            $tags_raw = explode(',',$row['tag']);
                                            array_push($titles,$row['title']);
                                            foreach($tags_raw as $tag_raw){
                                                if( !in_array($tag_raw,$tags) ){
                                                    array_push($tags,$tag_raw);
                                                }
                                                if(!empty($tag_raw)){
                                                    array_push($tags_compilation,$tag_raw);
                                                }
                                            }
                                        }
                                        echo '<script>
                                        $( function() {
                                            var availableTags = '.json_encode($titles).';
                                            $( "#search-box" ).autocomplete({
                                            source: availableTags
                                            });
                                        } );
                                        </script>';
                                        foreach($tags as $tag){
                                            $i++;
                                            $check_tag = '';
                                            if( isset($_GET['tag']) ){
                                                $taggs = explode(',',mysqli_escape_string($conn,$_GET['tag']));
                                                foreach( $taggs as $tagg ){
                                                    if( $tagg == $tag ){
                                                        $check_tag = ' checked ';
                                                        if( !empty($tag) ){
                                                            echo '<script>tags.push("'.$tag.'");</script>';
                                                        }
                                                    }
                                                }
                                            }
                                            if( !empty($tag) ){
                                                $tag_counted = array_count_values($tags_compilation);
                                                $tag_edit = preg_replace('/\s+/', '', $tag);
                                                $tag_syntax = "'".$tag."'";
                                                echo '<div class="form-check-inline">
                                                <label class="form-check-label" for="f-'.$tag_edit.'"><input type="checkbox" class="form-check-input" id="f-'.$tag_edit.'" onclick="changeTag('.$tag_syntax.')" '.$check_tag.' />'.ucwords($tag).' ('.$tag_counted[$tag].')
                                                </label>
                                                </div>';
                                            }
                                        }
                                    }
                                }
                                $ppg = 10; //query per page (default)
                                $search_page_location = ''; //page query links
                                $extra_param = '';
                                $search = '';
                                $sql = "SELECT * FROM `anime`";
                                $tags_count = 0;
                            if(isset($_GET['search'])){
                                $search = mysqli_escape_string($conn,$_GET['search']);
                                $tags = explode(',',mysqli_escape_string($conn,$_GET['tag']));
                                $tag_raw = mysqli_escape_string($conn,$_GET['tag']);
                                $sql = "SELECT * FROM `anime` WHERE `title` LIKE '%$search%' OR `english_title` LIKE '%$search%'";
                                $extra_param.= "&search=$search";
                                $extra_param.= "&tag=$tag_raw";
                                if( isset($_GET['show-image']) ){
                                    $extra_param.= "&show-image=1";
                                    $show_image = true;
                                }else{
                                    $show_image = false;
                                }
                                if( isset($_GET['edit-mode']) ){
                                    $extra_param.= "&edit-mode=1";
                                    $edit = true;
                                }else{
                                    $edit = false;
                                }

                                if( isset($_GET['ppg']) ){
                                    if( $_GET['ppg'] >= 1 && $_GET['ppg'] <= 1000 ){
                                        $ppg = $_GET['ppg'];
                                        $extra_param.= "&ppg=".$_GET['ppg'];
                                    }
                                }

                                if( isset($_GET['portrait-mode']) ){
                                    $extra_param.= "&portrait-mode=1";
                                    $portrait = true;
                                    $ppg = 40;
                                }else{
                                    $portrait = false;
                                }

                                if( isset($_GET['sort']) && isset($_GET['by']) ){
                                    $by = mysqli_escape_string($conn,$_GET['by']);
                                    $sort = mysqli_escape_string($conn,$_GET['sort']);
                                    $extra_param.= "&sort=$sort&by=$by";
                                    $sort = " ORDER BY `$by` ".strtoupper($sort);
                                    $sql .= $sort;
                                }

                                $tags_count = 0;
                                foreach($tags as $tag){
                                    if( !empty($tag) ){
                                        $tags_count++;
                                    }   
                                }
                            }
                                //echo $tags_count;
                            ?>
                            <br><br>
                            <select name="sort" id="filter1" onchange="switchFilterOption();">
                                <option value="desc">DESC</option>
                                <option value="asc">ASC</option>
                            </select>
                            <select name="by" id="filter2" onchange="switchFilterOption();">
                                <option value="id">ID</option>
                                <option value="title">Title</option>
                                <option value="episode">Episode</option>
                                <option value="rating">Rating</option>
                                <option value="date_from">Release Date</option>
                                <option value="date_to">Finished Airing</option>
                            </select>
                            <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="show-image" value="1" <?php if($show_image==true){echo 'checked';} ?> />Show Image
                            </label>
                            </div>
                            <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="edit-mode" value="1" <?php if($edit==true){echo 'checked';} ?> />Enable Edit Mode
                            </label>
                            </div>
                            <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="portrait-mode" value="1" <?php if($portrait==true){echo 'checked';} ?> />Portrait Mode
                            </label>
                            </div>
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                        <?php 
                        if( isset($_GET['page']) || !isset($_GET['page']) ){ //remove or to limit if user press only
                            //Counting for Pagination (Must be placed before adding LIMIT or generating buttons)
                            //echo $sql;
                            $res = $conn->query($sql);
                            $countres = mysqli_num_rows($res);
                            $pages = ceil($countres/$ppg); //Round UP the decimal
                            if(isset($_GET['page'])){
                                $selectedpage = $_GET['page'];
                            }else{
                                $selectedpage = 1;
                            }

                            //$row = mysqli_fetch_array($res);
                            //print_r($row);

                            //debugging area
                            //echo '<h5 class="text-info">Result Found: '.$countres.'</h5>';
                            //echo '<h4>Pages:'.$pages.'</h4>';
                            
                            //change queries with LIMIT
                            //$countres => total query
                            $start = ($selectedpage-1)*$ppg;
                            //LIMIT (x,y) -> x( start from (0) ) + y(how many query)
                            $sql = $sql." LIMIT $start,$ppg"; 
                            $res = $conn->query($sql);

                            //VERIFY selectedpage
                            if( $selectedpage >= 1 && $selectedpage <= $pages ){
                                $selectedpage_verified = true;
                            }else{
                                $selectedpage_verified = false;
                            }

                            //Generate Result
                            if($selectedpage_verified == true){
                                if( mysqli_num_rows($res) > 0 ){
                                    while($row = mysqli_fetch_array($res)){
                                        $anime_tags = array();
                                        $anime_tags = explode(',',$row['tag']);
                                        $completion_tag = 0;
                                        foreach( $anime_tags as $anime_tag ){
                                            if( in_array($anime_tag,$tags) && !empty($anime_tag) ){
                                                $completion_tag++;
                                            }
                                        }
                                        $edit_mode = '';
                                        $english_title = '';
                                        if( !empty($row['english_title']) ){
                                            $english_title = '('.$row['english_title'].')';
                                        }
                                        if( $edit == true ){
                                            $edit_mode ='
                                                    <a href="edit.php?id='.$row['id'].'" class="btn btn-primary waves-effect waves-dark">
                                                    <i class="fa fa-edit" style="font-size:1.2rem"></i></a>';
                                        }

                                        //fetching image
                                        $images = array();
                                        $images = explode(',',$row['image_link']);
                                        if( $show_image == true ){
                                            $image_div = '
                                            <div class="col-sm-4 col-md-2">
                                                <img width="150px" class="img-sm-list" src ="'.$images[0].'">
                                            </div>';
                                        }else{
                                            $image_div = '';
                                        }

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
                                                    case 'favorite' : $final_tag .= '<span class="sm-icon" style="background-color:maroon;">'.ucwords($tag_rows[$i]).'</span>';break;
                                                    default : $final_tag .= '<span class="sm-icon">'.ucwords($tag_rows[$i]).'</span>';break;
                                                }
                                            }
                                        }

                                        if( !empty($row['date_from']) ){
                                            $date_start = date('Y',strtotime($row['date_from']));
                                        }else{
                                            $date_start = '--';
                                        }
                                        if( !empty($row['date_to']) ){
                                            $date_to = date('Y',strtotime($row['date_to']));
                                            if( $date_start == $date_to ){
                                                $date_to = '';
                                            }else{
                                                $date_to = ' - '.$date_to;
                                            }
                                        }else{
                                            $date_to = '';
                                        }

                                        if( $row['rating'] <= 0 || empty($row['rating']) ){
                                            $rating_final = ' <i>Not Rated Yet</i>';
                                        }else{
                                            $rating_final = $row['rating']; 
                                        }

                                        if( $portrait == false ){
                                            if( ($completion_tag >= $tags_count && isset($_GET['search'])) || !isset($_GET['search']) ){
                                                echo '
                                                <div class="search-result-container">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                    <h5>'.$row['title'].' <small><i class="fas fa-star fa-sm" style="color:orange;"></i>'.$rating_final.' (ID: '.$row['id'].')</small></h5>
                                                    <label>'.$english_title.'</label> <p>'.$date_start.$date_to.'</p>
                                                    </div>'.$image_div.'
                                                    <div class="col-sm-8 col-md-10">
                                                        <p>'.$row['episode'].' Episodes</p>
                                                        <p class="desc-hidden sm-hide">'.$row['description'].'</p>
                                                        <p>'.$final_tag.'</p>
                                                        <a href="view.php?id='.$row['id'].'" class="btn btn-primary waves-effect waves-dark">
                                                        View</a>'.$edit_mode.'
                                                    </div>
                                                </div>
                                                </div>'; 
                                            }
                                        }else{
                                            $img_url = "'".$images[0]."'";
                                            $link_url = "'view.php?id=".$row['id']."'";
                                            echo '
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-6 col-xl-20 portrait-result-container-wrapper">
                                            <div class="portrait-result-container" style="background: url('.$img_url.');" onclick="href('.$link_url.');">
                                            <div style="color:white;background-color:rgba(0,0,0,0.5);height:70px;position:absolute;bottom:20px;left:10%;right:10%;padding:10px;">
                                            <h6>'.$row['title'].' <small><br><i class="fas fa-star fa-sm" style="color:orange;"></i>'.$rating_final.'  (ID: '.$row['id'].')</small></h6>
                                            </div>
                                            </div>
                                            </div>';
                                        }
                                    }
                                }else{
                                    echo '<h5>No Result</h5>';
                                }
                            }else if( !isset($_GET['page']) ){
                                echo '<h5>Undefined Pages</h5>';
                            }else if( $selectedpage_verified == true ){
                                echo '<h5>Such Pages doesn\'t exist!</h5>';
                            }else{
                                echo '<h5>No Result Found!</h5>';
                            }
                        }else{
                            echo '<h5>Start Searching</h5>';
                        }
                                                        
                    ?>
                        <div class="page-number text-center col-12">
                                <?php
                                if( isset($_GET['page']) || $selectedpage_verified == true){
                                    //Generate Page Link Button
                                    if( $selectedpage != 1 && $pages > 1 && isset($_GET['page']) ){
                                        echo '<a href="'.$search_page_location.'?page='.($selectedpage-1).$extra_param.'" class="btn btn-secondary waves-effect waves-dark"><i class="mdi mdi-chevron-left"></i>Prev</a> ';
                                    }
                                    for( $page=$selectedpage-2 ; $page<=$selectedpage+2 ; $page++ ){
                                        if( $page == $selectedpage ){
                                            echo '<a href="'.$search_page_location.'?page='.$page.$extra_param.'" class="btn btn-primary waves-effect waves-light">'.$page.'</a> ';
                                        }else if( $page > 0 && $page <= $pages ){
                                            echo '<a href="'.$search_page_location.'?page='.$page.$extra_param.'" class="btn btn-secondary waves-effect waves-dark">'.$page.'</a> ';
                                        }
                                    }
                                    if( $selectedpage != $pages && $pages > 1 ){
                                        echo '<a href="'.$search_page_location.'?page='.($selectedpage+1).$extra_param.'" class="btn btn-secondary waves-effect waves-dark"><i class="mdi mdi-chevron-right"></i>Next</a> ';
                                    }
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
    <script>
        $(document).ready(function(){
            applyFilterOption();
            changeTag('');
        });
    </script>
</body>
</html>