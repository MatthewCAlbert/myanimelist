<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/server.php';
        require '../include/head.php';
    ?>
    <title>Home<?php echo $title_dash; ?></title>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <h1>Some Content</h1>
                        <p>Lorem Ipsum</p>
                        <p>Do I need to fill it?</p>
                        <div style="padding:20px;border-radius:20px;background-color:#E1E7F5;">
                        <h5>FYI</h5>
                        <?php
                            $sql = "SELECT * FROM `chara`";
                            $res = $conn->query($sql);
                            $chara_count = $res->num_rows;
                            $sql = "SELECT * FROM `episode`";
                            $res = $conn->query($sql);
                            $episode_count = $res->num_rows;
                            $sql = "SELECT * FROM `anime`";
                            $res = $conn->query($sql);
                            $anime_count = $res->num_rows;
                            echo '<h6>Registered Anime : '.$anime_count.'</h6>';
                            echo '<h6>Registered Character : '.$chara_count.'</h6>';
                            echo '<h6>Total Episode : '.$episode_count.'</h6>';
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
</body>
</html>