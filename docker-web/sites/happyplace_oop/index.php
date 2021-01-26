<?php
    include "config.php";

    if(isset($_POST['func'])){

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <script src="https://use.fontawesome.com/releases/v5.9.0/js/all.js" data-auto-replace-svg></script>
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happyplace OOP</title>
</head>
<body>
    <div id="map" class="map"></div>
    <div id="list">
        <?php 
            foreach($apprenticesobjs as $person){
                echo "<div class='list-content'><p>";
                echo $person->lastname.", ".$person->firstname;
                echo "</p><button id='list-edit'><i class='fas fa-pencil-alt'></i></button><button id='list-delete'><i class='fas fa-times'></i></button></div>";
            }
        ?>
    </div>
    <form id="register-form" action="<?php echo $rootpath ?>/index.php" method="post">
        <button type="submit" name="register-btn" id="register-button">Register new</button>
    </form>
    <script type="text/javascript">
        var map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([8, 50]),
                zoom: 5
            })
        });
        function set_center(lng, lat, zoomget=17){
        	map.setView(new ol.View({
                center: ol.proj.transform([lng, lat], 'EPSG:4326', 'EPSG:3857'),
            	zoom: zoomget
            }));
        }
        function add_map_point(lng, lat, color) {
            console.log('<?php echo $rootpath ?>/returnmarker.php?color='+color);
            var vectorLayer = new ol.layer.Vector({
                source: new ol.source.Vector({
                    features: [new ol.Feature({
                        geometry: new ol.geom.Point(ol.proj.transform([parseFloat(lng), parseFloat(lat)], 'EPSG:4326', 'EPSG:3857')),
                    })]
                }),
                style: new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [1, 1],
                        anchorXUnits: "fraction",
                        anchorYUnits: "fraction",
                        src: '<?php echo $rootpath ?>/returnmarker.php?color='+color,
                    })
                })
            });
            map.addLayer(vectorLayer);
        }
        <?php
            foreach($apprenticesobjs as $apprentices){
                echo $apprentices->marker->ShowMarkerOnMap();
            }
        ?>
    </script>
</body>
</html>