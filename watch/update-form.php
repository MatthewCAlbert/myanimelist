
                        <form action="" method="post" id="ep-edit">
                            <h5>Episode Description</h5>
                            <p class="remove2"><?php if( !empty($row['description']) ){echo $row['description'];}else{ echo '--'; } ?></p>
                            <textarea name="desc" class="form-control remove hide" cols="40" rows="8"><?php echo $row['description']; ?></textarea>
                            <br>
                            <h5>Tags</h5>
                            <p class="remove2"><?php if( !empty($row['tag']) ){echo $row['tag'];}else{ echo '--'; } ?></p>
                            <input type="text" name="tag" value="<?php echo $row['tag']; ?>" class="form-control remove hide"/>
                            <br><br>
                            <?php
                                if(isset($_SESSION["myanimelist_username"])){
                                    if($user_row['status'] == 'administrator'){
                                        echo '
                                        <button class="btn btn-primary" type="button" onclick="episodeEdit(1)" id="trigger">Update Episode Info</button>';
                                    }
                                }
                            ?>
                        </form>