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
    <title>Character<?php echo $title_dash; ?></title>
    <!-- jQuery UI Autocomplete Components -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <div class="announcement">
        <div class="col-12">
            <h5><b>Search Character</b></h5>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <form class="form-control" method="get" action="../chara" style="padding:20px 10px;">
                            <input type="hidden" name="page" value="1" hidden/>
                            <input type="text" class="form-control" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>" style="margin:0 20px 10px 0;" placeholder="Search Character Name.." id="search-box"/>
                            <input type="text" name="tag" hidden/>
                            <?php
                                require 'search.php';
                            ?>
                            <br><br>
                            <select name="sort" id="filter1" onchange="switchFilterOption();">
                                <option value="desc">DESC</option>
                                <option value="asc">ASC</option>
                            </select>
                            <select name="by" id="filter2" onchange="switchFilterOption();">
                                <option value="id">ID</option>
                                <option value="name">Name</option>
                                <option value="rating">Rating</option>
                            </select>
                            <div class="form-check-inline">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="show-image" value="1" <?php if($show_image==true){echo 'checked';} ?> />Show Image
                            </label>
                            </div>
                            <select name="mode" id="type-filter" onchange="switchFilterOption();">
                                <option value="big">Big List</option>
                                <option value="small">Small List</option>
                                <option value="portrait">Portrait</option>
                            </select>
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Search</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                        <?php 
                        if( isset($_GET['page']) || !isset($_GET['page']) ){ //remove or to limit if user press only
                            //Counting for Pagination (Must be placed before adding LIMIT or generating buttons)
                            //echo $sql;
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
                                    if( $mode == 'small' ){
                                        echo '<table width="100%" style="margin-top:20px;" class="table table-hover table-responsive-sm">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Anime</th>
                                            <th class="text-center">Appeared On</th>
                                            <th>Rating</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>';
                                    }
                                    while($row = mysqli_fetch_array($res)){
                                        $edit_mode = '';
                                        $english_title = '';
                                        if( !empty($row['english_title']) ){
                                            $english_title = '('.$row['english_title'].')';
                                        }
                                        if(isset($_SESSION["myanimelist_username"])){
                                            if($user_row['status'] == 'administrator'){
                                                if( $edit == true ){
                                                    $edit_mode ='
                                                            <a href="edit.php?id='.$row['id'].'" class="btn btn-primary waves-effect waves-dark">
                                                            <i class="fa fa-edit" style="font-size:1.2rem"></i></a>';
                                                }
                                            }
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
                                                    case 'favorite' : $final_tag .= '<td><span class="sm-icon" style="background-color:maroon;">'.ucwords($tag_rows[$i]).'</span></td>';break;
                                                    default : $final_tag .= '<td><span class="sm-icon">'.ucwords($tag_rows[$i]).'</span></td>';break;
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
                                        
                                        $animes = array();
                                        $animes = explode(',',$row['anime_id']);
                                        $appear_on_anime = '';
                                        $i = 1;
                                        foreach($animes as $anime){
                                            if(!empty($anime)){
                                                $sql2 = "SELECT `title`,`id` FROM `anime` WHERE `id`='$anime'";
                                                $res2 = $conn->query($sql2);
                                                if( $res2 ){
                                                    if( $res2->num_rows>0 ){
                                                        $row2 = mysqli_fetch_assoc($res2);
                                                        $appear_on_anime .= '<a href="../anime/view.php?id='.$row2['id'].'" >'.$row2['title'].'</a>';
                                                        if( $i != count($animes) ){
                                                            $appear_on_anime .=", ";
                                                        }
                                                        $i++;
                                                    }
                                                }
                                            }
                                        }

                                        if( (isset($_GET['search'])) || !isset($_GET['search']) ){
                                            if( $mode == 'big' || $mode == false ){
                                                    echo '
                                                    <div class="search-result-container">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                        <h5>'.$row['name'].' <small><i class="fas fa-star fa-sm" style="color:orange;"></i>'.$rating_final.' (ID: '.$row['id'].')</small></h5>
                                                        </div>'.$image_div.'
                                                        <div class="col-sm-8 col-md-10">
                                                            <p>'.$appear_on_anime.' on Episode '.$row['appeared'].'</p>
                                                            <p class="desc-hidden sm-hide">'.$row['description'].'</p>
                                                            <table class="table table-responsive table-cut-padding">'.$final_tag.'</table>
                                                            <a href="view.php?id='.$row['id'].'" class="btn btn-primary waves-effect waves-dark">
                                                            View</a>'.$edit_mode.'
                                                        </div>
                                                    </div>
                                                    </div>'; 
                                            }else if( $mode == 'portrait' ){
                                                echo '<div class="gallery-wrapper portrait col"><a href="../anime/view.php?id='.$row['id'].'" class="horizontal-list"><span class="gallery-title">'.$row['name'].' <span style="white-space:nowrap;font-size:.8em;"><i class="fas fa-star fa-sm" style="color:orange;"></i>'.$rating_final.'</span></span>
                                                <img class="gallery-img lazyloaded" src="'.$images[0].'" />
                                                </a></div>';
                                            }else if( $mode == 'small' ){
                                                echo '<tr>
                                                <td>'.$row['id'].'</td>
                                                <td>'.$row['name'].'</td>
                                                <td>'.$appear_on_anime.'</td>
                                                <td class="text-center">'.$row['appeared'].'</td>
                                                <td><i class="fas fa-star fa-sm" style="color:orange;"></i>'.$rating_final.'</td>
                                                <td class="text-center small-list">
                                                <a href="view.php?id='.$row['id'].'" class="btn btn-primary waves-effect waves-dark">
                                                View</a>'.$edit_mode.'
                                                </td>
                                                </tr>';
                                            }   
                                        }
                                    }
                                    if( $mode == 'small' ){
                                        echo '</tbody></table>';
                                    }
                                }else{
                                    echo '<h5 style="padding:20px;">No Result</h5>';
                                }
                            }else if( !isset($_GET['page']) ){
                                echo '<h5 style="padding:20px;">Undefined Pages</h5>';
                            }else if( $selectedpage_verified == true ){
                                echo '<h5 style="padding:20px;">Such Pages doesn\'t exist!</h5>';
                            }else{
                                echo '<h5 style="padding:20px;">No Result Found!</h5>';
                            }
                        }else{
                            echo '<h5 style="padding:20px;">Start Searching</h5>';
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