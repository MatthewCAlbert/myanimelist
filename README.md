# About
A PHP web application to create your own custom anime list. Btw, this is an online application, so make sure you're online.
Created on June 2018.
Build using MySQL, PHP, Javascript(ES6), jQuery, Bootstrap 4, Font Awesome 5, Slick.JS (http://kenwheeler.github.io/slick) .
Tested smoothly on Chrome Version 67.0.3396.87 (Official Build) (64-bit).

# Features
- Add, search, edit your list ( anime, episodes, and character ).
- A movie player, create your own anime library, so make sure episode link you've inserted is a valid playable .mp4 videos.
- Responsive mobile website and lightweight.
- Choose to make your list private or not.
- Member and Admin account.

# Design
You know it, it's from (http://www.myanimelist.net) MyAnimeList.net website lol.

# Documentation
No, I don't provide one, I'm too lazy :v. Find out yourself. I already give some examples below

# Licenses
-

# Getting Started
*Follow the folder repository zip structure.
Host this folder to your host side.
Import myhlist.sql to your MySQL server.
And setup exactly according to below.

## Changing MySQL Server and DB Name also changing the private status of the list.
Go to include/server.php.
Default settings
```php
    session_start();
    $private_col = false; // Change this to change private status
    $servername = "localhost:3306";
    $username = "root";
    $password = "";
    $dbname = "myanimelist";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 
```
## Changing Website Name
Go to include/head.php.
Default settings
```php
    // Change this to change every website pages end <title> tags
    $title_dash = ' - MyAnimeList';
```

Go to include/sidebar.php.
Default settings
```html
    <div class="p-1 mr-auto"><a href=".." class="navbar-brand text-bold">MyAnimeList</a></div>
```

## Default Admin User
```txt
Username : root
Password : admin
```

## Page Structure
Adding new page in this root folder, create a new folder name it according to the page name.
And then add index.php inside the newly created folder according to php file format below.

Plain page structure, must be in .php format.
```html
<?php
    require '../include/server.php';
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        require '../include/server.php';
        require '../include/head.php';
    ?>
    <title>Page Name<?php echo $title_dash; ?></title>
</head>
<body>
    <?php
        include '../include/sidebar.php';
    ?>
        <section class="content">
            <div class="container">
                <!-- Content here -->
            </div>
        </section>
            <?php
                include '../include/footer.php';
            ?>
    </div>
</body>
</html>
```
