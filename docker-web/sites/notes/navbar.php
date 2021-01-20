<?php 
    include "config.php"; 

    if(isset($_COOKIE['session-name']) && isset($_COOKIE['session-id'])){
        $sql_user = "SELECT passwordhash FROM users where username='".$_COOKIE['session-name']."';";
        $query_user = $conn->query($sql_user);
        $result_user = $query_user->fetch_array(MYSQLI_BOTH);

        $hash = hash("sha256", $result_user[0].$_SERVER['REMOTE_ADDR']);

        if($_COOKIE['session-id'] == $hash){
            $username_login = $_COOKIE['session-name'];
            $session = $_COOKIE['session-id'];
            $login = true;
        }
    }

    $sql_profilepicture = "SELECT profilepicture FROM users where username".$username_login.";";
    $query_profilepicture = $conn->query($sql_profilepicture);
    $result_profilepicture = $query_profilepicture->fetch_array(MYSQLI_BOTH);
    $profilepicture = $result_profilepicture[0];
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="<?php echo "$rootpath"; ?>/css/navbar.css">
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" data-auto-replace-svg></script>
</head>
<body>
    <div id="site-title-container">
        <a id="menu-expand" onclick="document.getElementById('menu-expand').style.display = 'none';" href="#menu"><i class="fas fa-bars"></i> Menu</a>
        <p id="site-title"></p>
    </div>
    <nav id="menu">
        <a id="menu-close" href=""><i class="fas fa-times"></i></a>
        <a href="<?php if($login){echo $rootpath.'/config/user/'.urlencode($username);}else{echo $rootpath.'/login';}?>" id="menu-user"><?php if($login){echo $username_login;}else{echo "Login";}?></a>
        <ul>
            <li><a id="home-anchor" href="<?php echo $rootpath ?>/home">- Home</a></li>
            <li><a id="bms-anchor" href="<?php echo $rootpath ?>/bms">- BMS Notenstand</a></li>
            <li><a id="lap-anchor" href="<?php echo $rootpath ?>/lap">- LAP Notenstand</a></li>
            <li><a id="logout" href="<?php echo $rootpath ?>/login/logout">Logout from <?php echo $username_login ?></a></li>
        </ul>
    </nav>
</body>
</html>