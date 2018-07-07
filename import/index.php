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
        <div class="announcement">
        <div class="col-12">
            <h5><b>Import</b></h5>
        </div>
        </div>
        <section class="content">
            <div class="container">
                <form method="post" action="import.php" enctype="multipart/form-data">
                    <h4>Upload Multiple Database Entry</h4>
                    <input type="text" name="filename" value="output" class="form-control" hidden /><br>
                    <label>Target</label>
                    <select name="target" class="form-control">
                        <option value="anime">Anime</option>
                        <option value="chara">Character</option>
                    </select><br>
                    <label>Import CSV File to Database</label>
                    <input type="file" name="file" class="form-control-file border" style="width:50%;" required /><br>
                    <button type="submit" name="submit" class="btn btn-primary">Import</button>
                </form>
                <button class="btn btn-light" onclick="window.history.back()"><i class="fa fa-chevron-left"></i> Back</button>
            </div>
        </section>
            <?php
                include '../include/footer.php';
            ?>
    </div>
</body>
</html>