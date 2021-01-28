<?php
    require_once("conf/marker.class.php");
    require_once("conf/apprentices.class.php");
    require_once("conf/database.class.php");
    require_once("conf/entity.class.php");

    $database = new Database("mysql", "root", "secret", "happyplace_oop");

    if(!$database){
        error_log ("Could not fetch Database ---------------------- Selflog");
    }

    $table_apprentices = new Entity($database->connection, 'apprentices');
    $table_markers = new Entity($database->connection, 'markers');

    $result_query = $database->query("SELECT COUNT(marker_id) FROM apprentices;");
    $count = mysqli_fetch_assoc($result_query)['COUNT(marker_id)'];

    $result_query = $database->query("SELECT apprentices.id, apprentices.firstname, apprentices.lastname, apprentices.marker_id, markers.lat, markers.lon, markers.color FROM apprentices INNER JOIN markers ON apprentices.marker_id=markers.id;");
    $apprenticesobjs = array();
    $count = 0;

    while($row = mysqli_fetch_assoc($result_query)){
        $markerobj = new Marker($row['marker_id'], $row['lat'], $row['lon'], $row['color']);
        $apprenticesobjs[$count] = new Apprentices($row['id'], $row['firstname'], $row['lastname'], $markerobj);
        $count++;
    }

    $rootpath = "http://localhost/happyplace_oop";
?>