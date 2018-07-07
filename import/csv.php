<?php

class csv extends mysqli{

    private $state_csv = false;
    public function __construct(){
        parent::__construct('localhost','root','','myanimelist');
        if ($this->connect_error){
            echo"Fail to connect to DB".$this->connect_error;
        }
    }
    public function import($file){
        global $target, $export, $filename;
        $file = fopen($file,'r');
        $export = array();
        $affected = 0;

        $header = array('JP Name','Anime ID');
        array_push($export,$header);

        while($row=fgetcsv($file)){
            if($target == 'anime'){
                $jp_name = $row[0];
                $en_name = $row[1];
                $episode_count = $row[2];
                $anime_desc = $row[3];
                $anime_tags = $row[4];
                $image_link = $row[5];
                $date_from = $row[6];
                $date_to = $row[7];
                $rating = $row[8];

                //episode links
                $epslinks = array();
                for($i = 0 ; $i < $episode_count ; $i++){
                    array_push($epslinks,$row[9+$i]);
                }
    
                $sql = "INSERT INTO `anime`(`title`,`english_title`,`episode`,`description`,`tag`,`image_link`,`date_from`,`date_to`,`rating`) VALUES ('$jp_name','$en_name','$episode_count','$anime_desc','$anime_tags','$image_link','$date_from','$date_to','$rating')";
                $res = $this->query($sql);
                if($res){
                    echo '<p class="alert alert-success">Anime entry success!</p>';
                    $sql = "SELECT `id`,`title` FROM `anime` WHERE `title`='$jp_name' AND `episode`='$episode_count' ";
                    $res2 = $this->query($sql);
                    if($res2){
                        if( $res2->num_rows> 0 ){
                            $row2 = mysqli_fetch_assoc($res2);
                            $entry = $row2['id'];
                            $row_export= array($row[0],$entry);
                            array_push($export,$row_export);
                        }else{
                            echo '<p class="alert alert-warning">Oops, something went wrong!</p>';
                        }
                    }
                    for( $i = 1 ; $i <= $episode_count ; $i++ ){
                        $episode_tags = '';
                        $episode_desc = '';
                        $episode_links = $epslinks[$i-1];
                        $sql = "INSERT INTO `episode`(`anime_id`,`episode`,`description`,`link`,`tag`) VALUES ('$entry','$i','$episode_desc','$episode_links','$episode_tags')";
                        $res = $this->query($sql);
                        if($res){
                            echo '<p class="alert alert-success">'.$row2['title'].' Episode '.$i.' entry success!</p>';
                        }else{
                            echo '<p class="alert alert-danger">'.$row2['title'].' Episode '.$i.' entry failed!</p>';
                        }
                    }
                }else{
                    echo '<p class="alert alert-danger">Anime entry failed!</p>';
                }
            }else if( $target = 'chara' ){
                $name = $row[0];
                $anime_id = $row[1];
                $appeared_on = $row[2];
                $chara_desc = $row[3];
                $chara_tags = $row[4];
                $image_link = $row[5];
                $rating =  $row[6];

                $animes = array();
                $animes = explode(',',$anime_id);
                $counted_anime = 0;
                $approved = true;
                foreach($animes as $anime){
                    $sql = "SELECT * FROM `anime` WHERE `id`='$anime'";
                    $res = $this->query($sql);
                    if($res){
                        if( $res->num_rows > 0 ){
                            $counted_anime++;
                        }else{
                            $approved = false;
                        }
                    }else{
                        $approved = false;
                    }
                }

                if( $approved == true ){
                    $sql = "INSERT INTO `chara`(`anime_id`,`appeared`,`name`,`description`,`tag`,`image_link`,`rating`) VALUES ('$anime_id','$appeared_on','$name','$chara_desc','$chara_tags','$image_link','$rating')";
                    $res = $this->query($sql);
                    if($res){
                        echo '<p class="alert alert-success">Character entry success!</p>';
                    }else{
                        echo '<p class="alert alert-danger">Character entry failed!</p>';
                    }
                }else{
                    echo '<p class="alert alert-danger">Anime ID not found!</p>';
                }
                
            }
        }
        if( $target == 'anime' ){
            $_SESSION['filename'] = $_POST['filename'];
            $_SESSION['export'] = $export;
            echo '<button class="btn btn-primary" type="button" onclick="href('."'import.res.php'".');">Generate CSV Result</button>';
        }
    }
    
}