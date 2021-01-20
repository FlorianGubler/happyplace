<?php include "config.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="css/report.css" type="text/css">
    <script src="https://use.fontawesome.com/releases/v5.9.0/js/all.js" data-auto-replace-svg></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
</head>
<body>
    <a href="<?php echo $rootpath; ?>/home/light"><i class="fas fa-arrow-left"></i> Go Back</a>
    <form action="<?php echo $rootpath; ?>/report" method="post" id="report-form">
        <h3 style="margin-bottom:15px;font-size:20px; font-weight: bold;">Your Name</h3>
        <input placeholder="Name" name="name" autocomplete="off" type="text" id="getname"><br>
        <h3 style="margin-bottom:15px;font-size:20px;font-weight: bold;">Bug Description</h3>
        <textarea rows="10" cols="72" placeholder="Description" name="description" autocomplete="off" type="text" id="getdescription"></textarea><br>
        <button type="submit" name="submit" class="submitbtn">Report <i class="fas fa-arrow-right"></i></button>
    </form>
<?php

    $errors = array();

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
            $sql_set_bugs = "INSERT INTO bugs (id, user, bugdescription) VALUES($newid, '$name', '$description');";
            $result_set = $conn->query($sql_set_bugs); 
            header('Location: '.$rootpath.'/home/light');
        }
        else{
            echo "<div id='error-container'>";
            for($a=0;$a<count($errors);$a++){
                echo "<p class='error'><i class='fas fa-exclamation-triangle'></i> ".$errors[$a]."</p><br>";
            }
            echo "</div>";
        }
    }
?>
</body>
</html>