<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="css/register.css" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <a href="index.php">Go Back</a>
    <form action="register.php" method="post" id="register-form">
        <h3 style="margin-bottom:15px;font-size:20px; font-weight: bold;">Personalities</h3>
        <label for="getfirstname">Firstname</label>
        <input placeholder="Firstname" name="firstname" autocomplete="off" type="text" id="getfirstname"><br>
        <label for="getlastname">Lastname</label>
        <input placeholder="Lastname" name="lastname" autocomplete="off" type="text" id="getlastname"><br>
        <h3 style="margin-bottom:15px;font-size:20px;font-weight: bold;">Location</h3>
        <label for="getadress">Adress</label>
        <input placeholder="Adress" name="adress" autocomplete="off" type="text" id="getadress"><br>
        <label for="getpostcode">Post Code & Location</label>
        <input placeholder="Post Code & Location" name="postcode" autocomplete="off" type="text" id="getpostcode"><br>
        <input type="color" name="color" id="colorpicker" value="#0000ff">
        <button type="submit" name="submit" class="registerbtn">Register</button>
    </form>
<?php
    session_start();

    // $servername = "mysql27j09.db.hostpoint.internal";
    // $username = "dekinotu_user1";
    // $password = "CBXG2pfrpKkDWsG";
    // $dbname = "dekinotu_happyplace";

    $servername = "mysql";
    $username = "root";
    $password = "secret";
    $dbname = "happyplace";

    $errors = array(); 

    function geocode($address){

        // url encode the address
        $address = urlencode($address);
    
        $url = "http://nominatim.openstreetmap.org/?format=json&addressdetails=1&q={$address}&format=json&limit=1";
    
        // get the json response
        $resp_json = file_get_contents($url);
    
        // decode the json
        $resp = json_decode($resp_json, true);
    
        return array($resp[0]['lat'], $resp[0]['lon']);
    
    }

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['submit'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $adress = $_POST['adress'];
        $postcode = $_POST['postcode'];
        $color = $_POST['color'];

        if (empty($firstname)) { array_push($errors, "Firstname missing"); }
        if (empty($lastname)) { array_push($errors, "Lastname missing"); }
        if (empty($adress)) { array_push($errors, "Adress missing"); }
        if (empty($postcode)) { array_push($errors, "Postcode / Location missing"); }
        if (empty($color)) { array_push($errors, "Color missing"); }
        
        $sql_count = "SELECT COUNT(id) FROM apprentices";
        $result_count = $conn->query($sql_count);
        $count = $result_count->fetch_array(MYSQLI_BOTH);
        $newid = $count[0]+1;

        $sql_check = "SELECT prename, lastname FROM apprentices WHERE prename='$firstname';";
        $result_check = $conn->query($sql_check);
        $check = $result_check->fetch_array(MYSQLI_BOTH);

        if ($check[0] === $firstname && $check[1] === $lastname) {
            array_push($errors, "Person '$firstname $lastname' does already exists");
        }

        // Finally, register user if there are no errors in the form
        if (count($errors) == 0) {
            $latlng = geocode($adress);
            $sql_set_place = "INSERT INTO places (id, latitude, longitude) VALUES($newid, '$latitude', '$longitude');";
            $result_set = $conn->query($sql_set_place); 
            $sql_set_mark = "INSERT INTO markers (id, color) VALUES($newid, '$color');";
            $result_set = $conn->query($sql_set_mark);
            $sql_set_appr = "INSERT INTO apprentices (prename, lastname, place_id, markers_id) VALUES('$firstname', '$lastname', $newid, $newid);";
            $result_set = $conn->query($sql_set_appr);
            header('Location:index.php');
        }
        else{
            echo "<div id='error-container'>";
            for($a=0;$a<count($errors);$a++){
                echo "<p class='error'>".$errors[$a]."</p><br>";
            }
            echo "</div>";
        }
    }
?>
</body>
</html>