<?php
    include "config.php";

    $firstname = $_GET['fn'];
    $lastname = $_GET['ln'];
    $newid = $_GET['newid'];
    $latitude = $_GET['lat'];
    $longitude = $_GET['lon'];
    $color = $_GET['color'];
	
    $sql_set_place = "INSERT INTO places (id, latitude, longitude) VALUES($newid, $latitude, $longitude);";
    $result_set = $conn->query($sql_set_place); 
    $sql_set_mark = "INSERT INTO markers (id, color) VALUES($newid, '$color');";
    $result_set = $conn->query($sql_set_mark);
    $sql_set_appr = "INSERT INTO apprentices (id, prename, lastname, place_id, markers_id) VALUES($newid, '$firstname', '$lastname', $newid, $newid);";
    $result_set = $conn->query($sql_set_appr);
    
    $sql_all = "SELECT * FROM apprentices;";
    $result_all = $conn->query($sql_all);
    $k=1;

    $sql_marker_color = "SELECT color FROM markers WHERE id=" . $newid . ";";
    $result_marker_color= $conn->query($sql_marker_color);
    $row_marker_color = $result_marker_color->fetch_array(MYSQLI_BOTH);
    $urlcolor = str_replace("#", "", $row_marker_color[0]); 
    $newsvg = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="20" height="20"><circle cx="10" cy="10" r="5.7" style="fill: '.$row_marker_color[0].';stroke:black;stroke-width:1;"/></svg>';
    $svgfile = fopen("assets/icon_".$urlcolor.".svg", "w");
    fwrite($svgfile, $newsvg);
    fclose($svgfile);
    $k++;
	
    header('Location: '.$rootpath.'/index.php?light=light');
?>