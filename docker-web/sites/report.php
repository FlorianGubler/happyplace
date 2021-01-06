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

    $errors = array();

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];

        if (empty($name)) { array_push($errors, "Name missing"); }
        if (empty($description)) { array_push($errors, "Description missing"); }
        
        $sql_count = "SELECT COUNT(id) FROM bugs";
        $result_count = $conn->query($sql_count);
        $count = $result_count->fetch_array(MYSQLI_BOTH);
        $newid = $count[0]+1;

        if (count($errors) == 0) {
            $sql_set_place = "INSERT INTO bugs (id, name, description) VALUES($newid, '$name', '$description');";
            $result_set = $conn->query($sql_set_place); 
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