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
        <div class="announcement">
        <div class="col-12">
            <h5><b>Search Anime</b></h5>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <form class="form-control" method="get" action="../anime" style="padding:20px 10px;">
                            <input type="hidden" name="page" value="1" hidden/>
                            <input type="text" class="form-control" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search'];} ?>" style="margin:0 20px 10px 0;" placeholder="Search Anime Title.." id="search-box"/>
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

                            if(isset($_GET['search'])){
                                if( isset($_GET['sort']) && isset($_GET['by']) ){
                                    $by = mysqli_escape_string($conn,$_GET['by']);
                                    $sort = mysqli_escape_string($conn,$_GET['sort']);
                                    $extra_param.= "&sort=$sort&by=$by";
                                    $sort = " ORDER BY `$by` ".strtoupper($sort);
                                    $sql .= $sort;
                                }
                            }
                            //debugging area
                            //echo '<h5 class="text-info">Result Found: '.$countres.'</h5>';
                            //echo '<h4>Pages:'.$pages.'</h4>';
                            
                            //change queries with LIMIT
                            //$countres => total query
                            $start = ($selectedpage-1)*$ppg;
                            //LIMIT (x,y) -> x( start from (0) ) + y(how many query)
                                $sql = $sql." LIMIT $start,$ppg"; 
                                $res = $conn->query($sql);
                                if($res){
                                    $res_count = mysqli_num_rows($res);
                                }

                            //VERIFY selectedpage
                            if( $selectedpage >= 1 && $selectedpage <= $pages ){
                                $selectedpage_verified = true;
                            }else{
                                $selectedpage_verified = false;
                            }

                            //Generate Result
                            if($selectedpage_verified == true){
                                if( $res_count > 0 ){
                                    if( $mode == 'small' ){
                                        echo '<table width="100%" style="margin-top:20px;" class="table table-hover table-responsive-sm">
                                        <thead class="thead-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th class="text-center">Episode</th>
                                            <th class="text-center">Airing</th>
                                            <th>Rating</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>';
                                    }while($row = mysqli_fetch_array($res)){
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
                                        $final_tag .= "<tr>";
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
                                        $final_tag .= "</tr>";

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
                                        }else if( $row['rating'] > 10){
                                            $rating_final = 10;
                                        }else{
                                            $rating_final = $row['rating']; 
                                        }

                                        if( (isset($_GET['search'])) || !isset($_GET['search']) ){
                                            if( $mode == 'big' || $mode == false ){
                                                    echo '
                                                    <div class="search-result-container">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                        <h5>'.$row['title'].' <small><i class="fas fa-star fa-sm" style="color:orange;"></i>'.$rating_final.' (ID: '.$row['id'].')</small></h5>
                                                        <label>'.$english_title.'</label> <p>'.$date_start.$date_to.'</p>
                                                        </div>'.$image_div.'
                                                        <div class="col-sm-8 col-md-10 col-12">
                                                            <p>'.$row['episode'].' Episodes</p>
                                                            <p class="desc-hidden sm-hide">'.$row['description'].'</p>
                                                            <table class="table table-responsive table-cut-padding">'.$final_tag.'</table>
                                                            <a href="view.php?id='.$row['id'].'" class="btn btn-primary waves-effect waves-dark">
                                                            View</a>'.$edit_mode.'
                                                        </div>
                                                    </div>
                                                    </div>'; 
                                            }else if( $mode == 'portrait' ){
                                                echo '<div class="gallery-wrapper portrait col"><a href="../anime/view.php?id='.$row['id'].'" class="horizontal-list"><span class="gallery-title">'.$row['title'].' <span style="white-space:nowrap;font-size:.8em;"><i class="fas fa-star fa-sm" style="color:orange;"></i>'.$rating_final.'</span></span>
                                                <img class="gallery-img lazyloaded" src="'.$images[0].'" />
                                                </a></div>';
                                            }else if( $mode == 'small' ){
                                                echo '<tr>
                                                <td>'.$row['id'].'</td>
                                                <td>'.$row['title'].'</td>
                                                <td class="text-center">'.$row['episode'].'</td>
                                                <td class="text-center">'.$date_start.$date_to.'</td>
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
                            <br>
                                <?php
                                if( isset($_GET['page']) || $selectedpage_verified == true){
                                    //Generate Page Link Button
                                    if( $pages > 1 && $selectedpage > 2 ){
                                        echo '<a href="'.$search_page_location.'?page=1'.$extra_param.'" class="btn btn-secondary waves-effect waves-dark">First</a> ';
                                    }
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
                                    if( $pages > 1 && $selectedpage < $pages-2 ){
                                        echo '<a href="'.$search_page_location.'?page='.$pages.$extra_param.'" class="btn btn-secondary waves-effect waves-dark">Last</a> ';
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