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
        <label for="getlatitude">Latitude</label>
        <input placeholder="Latitude" name="latitude" autocomplete="off" type="text" id="getlatitude"><br>
        <label for="getlongitude">Longitude</label>
        <input placeholder="Longitude" name="longitude" autocomplete="off" type="text" id="getlongitude"><br>
        <input type="color" name="color" id="colorpicker" value="#0000ff">
        <button type="submit" name="submit" class="registerbtn">Register</button>
    </form>
<?php
    session_start();

    $servername = "mysql";
    $username = "root";
    $password = "secret";
    $dbname = "happyplace";
    $errors = array(); 

    // connect to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['submit'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $color = $_POST['color'];

        if (empty($firstname)) { array_push($errors, "Firstname missing"); }
        if (empty($lastname)) { array_push($errors, "Lastname missing"); }
        if (empty($latitude)) { array_push($errors, "Latitude missing"); }
        if (empty($longitude)) { array_push($errors, "Longitude missing"); }
        if (empty($color)) { array_push($errors, "Color missing"); }

        $sql_check = "SELECT * FROM apprentices WHERE prename='$firstname';";
        $result_check = $conn->query($sql_check);
        $check = $result_check->fetch_array(MYSQLI_BOTH);

        if ($check[1] === $firstname) {
            array_push($errors, "Person '$firstname' does already exists");
        }

        // Finally, register user if there are no errors in the form
        if (count($errors) == 0) {
            $sql_set_place = "INSERT INTO places (id, latitude, longitude) VALUES(2, '$latitude', '$longitude')";
            $result_set = $conn->query($sql_set_place); 
            $sql_set_mark = "INSERT INTO markers (id, color) VALUES(2, '$color')";
            $result_set = $conn->query($sql_set_mark);
            $sql_set_appr = "INSERT INTO apprentices (prename, lastname, place_id, markers_id) VALUES('$firstname', '$lastname', 2, 2)";
            $result_set = $conn->query($sql_set_appr);
           
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