<?php
                                    if(!isset($_GET['search'])){
                                    $edit = true;
                                    if(isset($_SESSION['show-image'])){
                                        $show_image = $_SESSION['show-image'];
                                    }else{
                                        $show_image = false;
                                    }
                                }
                                $sql = "SELECT `tag`,`name` FROM `chara`";
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
                                            array_push($titles,$row['name']);
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
                                $sql = "SELECT * FROM `chara`";
                                $tags_count = 0;
                                if(isset($_SESSION['view-mode'])){
                                    $mode = $_SESSION['view-mode'];
                                }else{
                                    $mode = false;
                                }
                            if(isset($_GET['search'])){
                                $search = mysqli_escape_string($conn,$_GET['search']);
                                $tags_not_filtered = explode(',',mysqli_escape_string($conn,$_GET['tag']));
                                $tag_raw = mysqli_escape_string($conn,$_GET['tag']);
                                $sql = "SELECT * FROM `chara` WHERE `name` LIKE '%$search%'";
                                $extra_param.= "&search=$search";
                                $extra_param.= "&tag=$tag_raw";
                                
                                $tags = array();
                                for($i=0 ; $i < count($tags_not_filtered) ; $i++){
                                    if(!empty($tags_not_filtered[$i])){
                                        $tags[$i] = $tags_not_filtered[$i];
                                    }
                                }

                                if( isset($_GET['show-image']) ){
                                    $extra_param.= "&show-image=1";
                                    $_SESSION['show-image']=true;
                                    $show_image = true;
                                }else{
                                    $show_image = false;
                                    $_SESSION['show-image']=false;
                                }

                                if( isset($_GET['ppg']) ){
                                    if( $_GET['ppg'] >= 1 && $_GET['ppg'] <= 1000 ){
                                        $ppg = $_GET['ppg'];
                                        $extra_param.= "&ppg=".$_GET['ppg'];
                                    }
                                }

                                if( isset($_GET['mode']) ){
                                    $mode = mysqli_escape_string($conn,$_GET['mode']);
                                    $_SESSION['view-mode'] = $mode;
                                    $extra_param.= "&mode=$mode";
                                    switch($mode){
                                        case 'big': $ppg=10;break;
                                        case 'small': $ppg=30;break;
                                        case 'portrait': $ppg=40;break;
                                    }
                                }else if( $mode != false ){
                                    switch($mode){
                                        case 'big': $ppg=10;break;
                                        case 'small': $ppg=30;break;
                                        case 'portrait': $ppg=40;break;
                                    }
                                }else{
                                    $mode = false;
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
                            $res = $conn->query($sql);
                            if( isset($_GET['search']) && isset($_GET['tag']) ){
                                if( count($tags) != 0 ){
                                    $countres = 0;
                                    $sql = "SELECT * FROM `chara` WHERE ";
                                    $sql_add = array();
                                    while($row=mysqli_fetch_array($res)){
                                        $anime_tags = array();
                                        $anime_tags = explode(',',$row['tag']);
                                            for( $i = 0 ; $i < count($anime_tags) ; $i++ ){
                                                if( in_array($anime_tags[$i],$tags) && !empty($anime_tags[$i]) ){
                                                    $countres++;
                                                    array_push($sql_add," `id`='".$row['id']."'");
                                                }
                                            }
                                    }
                                    for( $i = 0 ; $i < count($sql_add) ; $i++ ){
                                        if( $i < count($sql_add)-1 ){
                                            $sql .= $sql_add[$i].' OR ';
                                        }else{
                                            $sql .= $sql_add[$i];
                                        }
                                    }
                                    $tag_available = true;
                                }else{
                                    $countres = mysqli_num_rows($res);
                                }
                            }else{
                                $countres = mysqli_num_rows($res);
                            }