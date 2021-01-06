<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <script src="https://use.fontawesome.com/releases/v5.9.0/js/all.js" data-auto-replace-svg></script>
    <title>OpenLayers example</title>
</head>
<body>
    <form action="index.php" method="post" id="searchbar">
        <input class="searchbar" placeholder="Firstname" name="personsearch" autocomplete="off" type="text" id="fname">
        <input class="searchbar" placeholder="Lastname" name="personsearch-last" autocomplete="off" type="text" id="lname">
        <button type="submit" name="submit-search" id="search_submit"><i class="fas fa-search-location"></i> Search</button>
    </form>
   	<a href="report.php" id="bug-report"><i class="fas fa-bug"></i> Report Bug</a>
    <a id="register" href="register.php"><i class="fas fa-file-signature"></i> Register new Member</a>
    <div id="map" class="map"></div>
    <script type="text/javascript">
        // window.onload = function () {
        //     document.getElementById('result-id').innerHTML = "";
        // }
        var map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([8, 47]),
                zoom: 8
            })
        });
        function set_center(lng, lat){
        	map.setView(new ol.View({
                center: ol.proj.transform([lng, lat], 'EPSG:4326', 'EPSG:3857'),
            	zoom: 17
            }));
        }
        function add_map_point(lng, lat) {
            var vectorLayer = new ol.layer.Vector({
                source: new ol.source.Vector({
                    features: [new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.transform([parseFloat(lng), parseFloat(lat)], 'EPSG:4326', 'EPSG:3857')),
                    })]
                }),
                style: new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 0.5],
                        anchorXUnits: "fraction",
                        anchorYUnits: "fraction",
                        src: "https://upload.wikimedia.org/wikipedia/commons/e/ec/RedDot.svg"
                    })
                })
            });
            map.addLayer(vectorLayer);
        }
    </script>
    <div id="list"> 
    <h2><i class="fas fa-users"></i> Members</h2>
<?php

	function get_starred($str) { 
    	$len = strlen($str);
        return substr($str, 0, 1).str_repeat('*', $len-1); 
	} 
    // $servername = "mysql27j09.db.hostpoint.internal";
    // $username = "dekinotu_user1";
    // $password = "CBXG2pfrpKkDWsG";
    // $dbname = "dekinotu_happyplace";

    $servername = "mysql";
    $username = "root";
    $password = "secret";
    $dbname = "happyplace";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql_all = "SELECT * FROM apprentices;";
    $result_all = $conn->query($sql_all);
    $k=1;
    while ($row = mysqli_fetch_assoc($result_all)) {
        $sql_place_list = "SELECT latitude, longitude FROM places WHERE id=" . $row['place_id'] . ";";
        $result_place_list = $conn->query($sql_place_list);
        $row_place_list = $result_place_list->fetch_array(MYSQLI_BOTH);
        echo "
        <script type='text/javascript'>
            add_map_point(".$row_place_list[1].", ".$row_place_list[0].", false);
        </script>";
        echo "<a class='list-content' onclick='set_center(".$row_place_list[1].", ".$row_place_list[0].");'>".$k." |  ".get_starred($row['prename'])." ".get_starred($row['lastname'], true)."</a><br>";
        $k++;
    }
    echo "</div>";

    if (isset($_POST['submit-search'])) {
        $searchedperson = $_POST['personsearch'];
        $searchedperson_last = $_POST['personsearch-last'];
        $sql_full = "SELECT * FROM apprentices WHERE prename='" . $searchedperson . "' AND lastname='$searchedperson_last';";
        $result_full = $conn->query($sql_full);
        
        $sql_appr = "SELECT prename, lastname FROM apprentices WHERE prename='" . $searchedperson . "' AND lastname='$searchedperson_last';";
        $result_appr = $conn->query($sql_appr);

        if ($result_full->num_rows > 0) {
            $row_full = $result_full->fetch_array(MYSQLI_BOTH);

            $row_appr = $result_appr->fetch_array(MYSQLI_BOTH);
            
            $place_id = $row_full[3];
            $marker_id = $row_full[4];

            $sql_place = "SELECT latitude, longitude FROM places WHERE id=" . $place_id . ";";
            $sql_marker = "SELECT color FROM markers WHERE id=" . $marker_id . ";";
            $result_places = $conn->query($sql_place);
            $result_marker = $conn->query($sql_marker);
            $row_places = $result_places->fetch_array(MYSQLI_BOTH);
            $row_marker = $result_marker->fetch_array(MYSQLI_BOTH);
        } 
        else {
            echo "<p id='result-id' class='result'><i class='fas fa-exclamation-triangle'></i> No results</p>";
        }
        echo "
        <script type='text/javascript'>
            add_map_point(".$row_places[1].", ".$row_places[0].", true);
        </script>";
;
    }
    $conn->close();
?>
</body>
</html>