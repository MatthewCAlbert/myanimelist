<?php
    require '../include/server.php';
    require '../include/session.checker.php';
    require '../include/getuserdata.php';
    if($user_row['status']!='administrator'){
        header('Location: ..');
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/head.php';
    ?>
    <title>Import<?php echo $title_dash; ?></title>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <section class="content">
            <div class="container">
            <?php 
                $conn->close();
                include("csv.php"); 
                $csv = new csv();
                if(isset($_POST['submit'])){
                    $filename= $_POST['filename'].'.csv';
                    $target = $_POST['target'];
                    if(isset($_FILES['file'])){
                        $csv->import($_FILES['file']['tmp_name']);
                    }else{
                        echo "No CSV Found!";
                    }
                    }else{
                        header("Location: ..");
                        exit();
                    
                }
            ?>
            </div>
        </section>
            <?php
                include '../include/footer.php';
            ?>
    </div>
</body>
</html>