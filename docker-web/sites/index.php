<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <title>OpenLayers example</title>
</head>
<body>
    <form action="index.php" method="post" id="searchbar">
        <label for="fname">Name of person to find Location:</label>
        <input name="personsearch" autocomplete="off" type="text" id="fname"><br>
        <button type="submit" name="submit-search" id="search_submit">Search</button>
    </form>
    <a href="register.php">Register</a>
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
                center: ol.proj.fromLonLat([8.52074800985474, 47.36022860676095]),
                zoom: 18
            })
        });
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
            map.setView(new ol.View({
                center: ol.proj.transform([lng, lat], 'EPSG:4326', 'EPSG:3857'),
                zoom: 17
            }));
            map.addLayer(vectorLayer);
        }
    </script>
<?php
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

    if (isset($_POST['submit-search'])) {
        $searchedperson = $_POST['personsearch'];
        $sql_full = "SELECT * FROM apprentices WHERE prename='" . $searchedperson . "';";
        $result_full = $conn->query($sql_full);
        
        $sql_appr = "SELECT prename, lastname FROM apprentices WHERE prename='" . $searchedperson . "';";
        $result_appr = $conn->query($sql_appr);

        if ($result_full->num_rows > 0) {
            $row_full = $result_full->fetch_array(MYSQLI_BOTH);

            $row_appr = $result_appr->fetch_array(MYSQLI_BOTH);
            // echo "<br> <b style='font-weight:bold'>Apprentices</b> <br>";
            // for($i=0;$i<=count($row_appr);$i++){
            //     echo $row_appr[$i]." ";
            // }
            

            $place_id = $row_full[3];
            $marker_id = $row_full[4];

            $sql_place = "SELECT latitude, longitude FROM places WHERE id=" . $place_id . ";";
            $sql_marker = "SELECT color FROM markers WHERE id=" . $marker_id . ";";
            $result_places = $conn->query($sql_place);
            $result_marker = $conn->query($sql_marker);
            $row_places = $result_places->fetch_array(MYSQLI_BOTH);
            $row_marker = $result_marker->fetch_array(MYSQLI_BOTH);

            // echo "<br> <b style='font-weight:bold'>Places</b> <br>";
            // for($i=0;$i<count($row_places);$i++){
            //     echo $row_places[$i]." ";
            // }
            // echo "<br> <b style='font-weight:bold'>Marker</b> <br>";
            // for($i=0;$i<=count($row_marker);$i++){
            //     echo $row_marker[$i]." ";
            // }
        } 
        else {
            echo "<p id='result-id' class='result'>0 results</p>";
        }
        echo "
        <script type='text/javascript'>
            add_map_point(".$row_places[1].", ".$row_places[0].");
        </script>";
;
    }
    $conn->close();
?>
</body>
</html>