<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/server.php';
        require '../include/head.php';
        if(isset($_GET['id'])){
            $id = mysqli_escape_string($conn,$_GET['id']);
            $sql = "SELECT * FROM `anime` WHERE `id`='$id'";
            $warning = '';
            $res = $conn->query($sql);
            if($res){
                if($res->num_rows > 0){
                    $row = mysqli_fetch_assoc($res);
                }else{
                    $warning = '
                    <h1>404 Anime Not Found</h1>
                    <p><i>Baka yaro</i></p>';
                }
            }
        }else{
            header('Location: ..');
            exit();
        }
    ?>
    <title><?php if($res->num_rows>0){ echo $row['title'].$title_dash;}else{echo '404'.$title_dash;} ?></title>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <?php echo $warning; ?>
                    </div>
                </div>
                <?php if($warning == ''){include 'result.php';} ?>
            </div>
        </section>
            <?php
                include '../include/footer.php';
            ?>
    </div>
</body>
    <script>
        $(document).ready(function(){
            <?php echo 'episode_count ='.$episode_count.';'; ?>
            changeEpisode(1);
        });
    </script>
</html>