<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .map {
            margin-top: -10px;
            height: 890px;
            width: 100%;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <title>OpenLayers example</title>
</head>
<body>
    <form action="/action_page.php">
        <label for="fname">Name of person to find Location:</label>
        <input autocomplete="off" type="text" id="fname" name="fname"><br><br>
    </form>
    
    <div id="map" class="map"></div>
    <script type="text/javascript">
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
    </script>
</body>
</html>