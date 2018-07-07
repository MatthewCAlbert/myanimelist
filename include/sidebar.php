
    <nav class="col-12 col-sm-12 col-md-10">
        <div class="d-flex p-1 navbar-top sm-hide">
            <div class="p-1 mr-auto"><a href=".." class="navbar-brand text-bold">MyAnimeList</a></div>
            <div class="p-1 ml-auto"><?php
                    if(isset($_SESSION["myanimelist_username"])){
                        echo '<span style="position:relative;top:5px;right:20px;cursor:default;"><b>'.$user_row['name'].'</b></span>';
                        echo '<a href="../include/logout.php" class="btn btn-primary" style="margin-top:5px;"><i class="fas fa-sign-out-alt"></i> Log Out</a>';
                    }else{
                        echo '<a href="../login" class="btn btn-primary" style="margin-top:5px;"><i class="fas fa-sign-in-alt"></i> Login</a>';
                    }
                ?>
            </div>
        </div>
        <div class="d-flex p-1 navbar">
            <div class="p-1 mr-auto sm-hide">
                <a href="../home" class="nav-link">Home</a>
                <a href="../anime" class="nav-link">Anime</a>
                <a href="../chara" class="nav-link">Character</a>
                <?php
                    if(isset($_SESSION["myanimelist_username"])){
                        if($user_row['status'] == 'administrator'){
                            echo '
                            <a href="../entry" class="nav-link">Add Entry</a>
                            <a href="../manager" class="nav-link">Manager</a>';
                        }
                        echo '<a href="../account" class="nav-link">Account</a>';
                    }
                ?>
            </div>
            <div class="p-1 mr-auto sm-show">
                <div class="p-1 mr-auto"><a href=".." class="navbar-brand brand-invert text-bold">MyAnimeList</a></div>
            </div>
            <div class="p-1 sm-show">
                <button class="hamburger hamburger--squeeze" id="menu-button" type="button" onclick="toggleNav();">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
                </button>
            </div>
        </div>
    </nav>
    
    <div class="main-wrapper">
        <aside class="sidebar sm-show">
            <div class="sidebar-nav">
                <?php
                    if(isset($_SESSION["myanimelist_username"])){
                        echo '<div class="sidebar-item" style="background-color:#E1E7F5;">'.$user_row['name'].'</div>';
                    }
                ?>
                <div class="sidebar-item">
                    <a href="../home" class="sidebar-link"><i class="fas fa-home"></i> Home</a>
                </div>
                <div class="sidebar-item">
                    <a href="../anime" class="sidebar-link"><i class="fas fa-list-ul"></i> Anime</a>
                </div>
                <div class="sidebar-item">
                    <a href="../chara" class="sidebar-link"><i class="fas fa-list-ul"></i> Character</a>
                </div>
                <?php
                    if(isset($_SESSION["myanimelist_username"])){
                        if($user_row['status'] == 'administrator'){
                            echo '
                            <div class="sidebar-item">
                                <a href="../entry" class="sidebar-link"><i class="fas fa-plus"></i> Add Entry</a>
                            </div>
                            <div class="sidebar-item">
                                <a href="../manager" class="sidebar-link"><i class="fas fa-users"></i> Manager</a>
                            </div>';
                        }
                        echo '<div class="sidebar-item"><a href="../account" class="sidebar-link"><i class="fas fa-user-alt"></i> Account</a></div>';
                        echo '<div class="sidebar-item"><a href="../include/logout.php" class="sidebar-link"><i class="fas fa-sign-out-alt"></i> Log Out</a></div>';
                    }else{
                        echo '<div class="sidebar-item"><a href="../login" class="sidebar-link"><i class="fas fa-sign-in-alt"></i> Login</a></div>';
                    }
                ?>
            </div>
        </aside>