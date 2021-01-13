<?php include "config.php"; setcookie("login", "false", 0, "/");?>
<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="css/login.css" type="text/css">
    <script src="https://use.fontawesome.com/releases/v5.9.0/js/all.js" data-auto-replace-svg></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Root Login</title>
</head>
<body>
    <a href="<?php echo $rootpath; ?>/home/light"><i class="fas fa-arrow-left"></i> Go Back</a>
    <form action="<?php echo $rootpath; ?>/login.php" method="post" id="report-form">
        <h3 style="margin-bottom:15px;font-size:20px; font-weight: bold;">Username</h3>
        <input placeholder="Username" name="username" autocomplete="off" type="text" id="getusername"><br>
        <h3 style="margin-bottom:15px;font-size:20px;font-weight: bold;">Password</h3>
        <input placeholder="Password" name="password" autocomplete="off" type="password" id="getpassword"><br>
        <button type="submit" name="submit" class="submitbtn">Login <i class="fas fa-arrow-right"></i></button>
    </form>
<?php
    $errors = array();

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (empty($username)) { array_push($errors, "Username missing"); }
        if (empty($password)) { array_push($errors, "Password missing"); }

        $passwordhash = hash("sha256", $username.$password);
        
        if (count($errors) == 0) {
            $sql_get_login = "SELECT passwordhash FROM users WHERE username='" . $username . "';";
            $result_get = $conn->query($sql_get_login); 
            $row_get_login = $result_get->fetch_array(MYSQLI_BOTH);
            if($row_get_login[0] === $passwordhash){
                echo "<p style='background-color: green;' class='login-response'>Login succesfull</p>";
            }
            else{
                echo "<p style='background-color: red;' class='login-response'>Login failed</p>";
            }   
        }
        else{
            echo "<div id='error-container'>";
            for($a=0;$a<count($errors);$a++){
                echo "<p class='error'><i class='fas fa-exclamation-triangle'></i> ".$errors[$a]."</p><br>";
            }
            echo "</div>";
            echo "<script>document.getElementById('getusername').value = '".$username."';</script>";
            echo "<script>document.getElementById('getpassword').value = '".$password."';</script>";
        }
    }
?>
</body>
</html>