<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="css/report.css" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <a href="index.php">Go Back</a>
    <form action="reporter.php" method="post" id="report-form">
        <h3 style="margin-bottom:15px;font-size:20px; font-weight: bold;">Your Name</h3>
        <input placeholder="Name" name="name" autocomplete="off" type="text" id="getname"><br>
        <h3 style="margin-bottom:15px;font-size:20px;font-weight: bold;">Bug Description</h3>
        <input placeholder="Description" name="description" autocomplete="off" type="text" id="getdescription"><br>
        <button type="submit" name="submit" class="submitbtn">Register</button>
    </form>
<?php
    $servername = "mysql27j09.db.hostpoint.internal";
    $username = "dekinotu_user1";
    $password = "CBXG2pfrpKkDWsG";
    $dbname = "dekinotu_happyplace";

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
    }
?>
</body>
</html>