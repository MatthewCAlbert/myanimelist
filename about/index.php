<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/server.php';
        require '../include/head.php';
    ?>
    <title>About<?php echo $title_dash; ?></title>
    <style>
        i{
            vertical-align:middle;
        }
    </style>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col" class="icon">
                        <h3>Fun Open-Source Project</h3>
                        <h5>About</h5>
                        <p>A PHP web application to create your own custom anime list. Btw, this is an online application, so make sure you're online.</p>
                        <h5>Features</h5>
                        <p>- Add, search, edit your list.</p>
                        <p>- A movie player, create your own anime library, so make sure episode link you've inserted is a valid playable .mp4 videos.</p>
                        <p>- Responsive mobile website, unlike MAL ofc. And lightweight.</p>
                        <h5>Design</h5>
                        <p>You know it, it's from <a href="http://myanimelist.net" target="_blank">MyAnimeList.net</a> website lol.</p>
                        <h5>Documentation</h5>
                        <p>No, I don't provide one, I'm too lazy :v. Find out yourself.</p>
                        <h5>Created on June 2018</h5>
                        <p>Built using PHP <i class="fab fa-php fa-lg"></i>, MySQL <i class="fas fa-database fa-lg"></i> , JavaScript(ES6) <i class="fab fa-js-square fa-lg"></i> , jQuery, Bootstrap 4, Font Awesome 5 <i class="fab fa-font-awesome-alt fa-lg"></i></p>
                        <p>Tested on Chrome <i class="fab fa-chrome fa-lg"></i>.</p>
                        <h5>Licenses</h5>
                        <a rel="license" href="http://creativecommons.org/licenses/by/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by/4.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/4.0/">Creative Commons Attribution 4.0 International License</a>.
                        <br>
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