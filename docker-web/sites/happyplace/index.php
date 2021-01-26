<?php include "config.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/build/ol.js"></script>
    <link rel="stylesheet" href="<?php echo $rootpath ?>/css/style.css" type="text/css">
    <script src="https://use.fontawesome.com/releases/v5.9.0/js/all.js" data-auto-replace-svg></script>
    <title>Happy Place</title>
</head>
<body id="bodyobj" style="background-color: <?php if(isset($_GET['light'])){if($_GET['light'] == "light"){echo 'white';}else{echo 'grey';}} else {echo 'white';} ?>;">
    <form action="<?php echo $rootpath; ?>/index.php?light=light" method="post" id="searchbar">
        <input style="background-color: <?php if(isset($_GET['light'])){if($_GET['light'] == "light"){echo '#f1f1f1';}else{echo '#565353';}} else {echo '#f1f1f1';} ?>;" class="searchbar" placeholder="Firstname" name="personsearch" autocomplete="off" type="text" id="fname">
        <input style="background-color: <?php if(isset($_GET['light'])){if($_GET['light'] == "light"){echo '#f1f1f1';}else{echo '#565353';}} else {echo '#f1f1f1';} ?>;" class="searchbar" placeholder="Lastname" name="personsearch-last" autocomplete="off" type="text" id="lname">
        <button style="background-color: <?php if(isset($_GET['light'])){if($_GET['light'] == "light"){echo '#f1f1f1';}else{echo '#565353';}} else {echo '#f1f1f1';} ?>;" type="submit" name="submit-search" id="search_submit"><i class="fas fa-search-location"></i> Search</button>
    </form>
   	<a style="background-color: <?php if(isset($_GET['light'])){if($_GET['light'] == "light"){echo '#f1f1f1';}else{echo '#565353';}} else {echo '#f1f1f1';} ?>;" href="<?php echo $rootpath ?>/report.php" id="bug-report"><i class="fas fa-bug"></i> Report Bug</a>
    <a style="background-color: <?php if(isset($_GET['light'])){if($_GET['light'] == "light"){echo '#f1f1f1';}else{echo '#565353';}} else {echo '#f1f1f1';} ?>;" id="register" href="<?php echo $rootpath ?>/register.php"><i class="fas fa-file-signature"></i> Register new Member</a>
    <div style="opacity: <?php if(isset($_GET['light'])){if($_GET['light'] == "light"){echo '100%';}else{echo '60%';}} else {echo '100%';} ?>;" id="map" class="map"></div>
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
        function dark_light(){
        	light = "<?php if(isset($_GET['light'])) {echo $_GET['light'];} else{echo "light";} ?>";
        	if(light === 'light'){
                window.location.href = '<?php echo $rootpath ?>/index.php?light=dark';
            }
            else{
                window.location.href = '<?php echo $rootpath ?>/index.php?light=light';
            }
            
        }
        function set_center(lng, lat, zoomget=17){
        	map.setView(new ol.View({
                center: ol.proj.transform([lng, lat], 'EPSG:4326', 'EPSG:3857'),
            	zoom: zoomget
            }));
        }
        function add_map_point(lng, lat, color) {
            console.log("la: "+lat+", lon: "+lng);
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
    </script>
    <div id="list"> 
    <button style="background-color: <?php if(isset($_GET['light'])){if($_GET['light'] == "light"){echo '#f1f1f1';}else{echo '#565353';}} else {echo '#f1f1f1';} ?>;" id="dark-light" onclick="dark_light()"><?php if(isset($_GET['light'])){if($_GET['light'] == "true"){echo '<i class="fas fa-lightbulb"></i>';}else{echo '<i class="far fa-lightbulb"></i>';}} else {echo '<i class="fas fa-lightbulb"></i>';} ?></button>
    <button style="background-color: <?php if(isset($_GET['light'])){if($_GET['light'] == "light"){echo '#f1f1f1';}else{echo '#565353';}} else {echo '#f1f1f1';} ?>;" onclick="set_center(0, 0, 1);" id="map_reset"><i class="fas fa-redo"></i> Reset Map</button>
    <h2><i class="fas fa-users"></i> Members</h2>
<?php
    
    // Check connection
    /*if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }*/
    
	function get_starred($str) { //For anonym datas on website
    	$len = strlen($str);
        return substr($str, 0, 1).str_repeat('*', $len-1); 
	} 
    
    $sql_all = "SELECT * FROM apprentices;";
    $result_all = $conn->query($sql_all);
    $k=1;
    
    while ($row = mysqli_fetch_assoc($result_all)) {
        $sql_place_list = "SELECT latitude, longitude FROM places WHERE id=" . $row['place_id'] . ";";
        $sql_marker_color = "SELECT color FROM markers WHERE id=" . $row['place_id'] . ";";
        $result_marker_color= $conn->query($sql_marker_color);
        $result_place_list = $conn->query($sql_place_list);
        $row_place_list = $result_place_list->fetch_array(MYSQLI_BOTH);
        $row_marker_color = $result_marker_color->fetch_array(MYSQLI_BOTH);
        $urlcolor = str_replace("#", "", $row_marker_color[0]);
        echo "
        <script type='text/javascript'>
            add_map_point(".$row_place_list[1].", ".$row_place_list[0].", '".$urlcolor."');
        </script>";
        if(isset($_GET['light'])){
        	if($_GET['light'] == "light"){
            	$light = '#f1f1f1';
            }else{
            	$light = '#565353';
            }
        } else {$light = '#f1f1f1';}
        echo "<a style='background-color: ".$light.";' class='list-content' onclick='set_center(".$row_place_list[1].", ".$row_place_list[0].");'>".$k." |  ".get_starred($row['prename'])." ".get_starred($row['lastname'], true)."</a><br>";
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
            $result_places = $conn->query($sql_place);
            $row_places = $result_places->fetch_array(MYSQLI_BOTH);
            
            echo "<script>set_center(".$row_places[1].", ".$row_places[0].")</script>";
        } 
        else {
            echo "<p id='result-id' class='result'><i class='fas fa-exclamation-triangle'></i> No results</p>";
        }
    }
    $conn->close();
?>
	<footer>
  		<p>&copy; By Florian Gubler - Im Rahmen von Projekt "HappyPlace" im ZLI Basislehrjahr 2020 / 2021</p>
  		<a href="mailto:gubler.florian@gmx.net">E-Mail: gubler.florian@gmx.net</a>
        <a href="tel:+41796731741">Telefon : +41796731741</a>
        <a id="login" href="<?php echo $rootpath ?>/login.php">Login</a>
	</footer>
</body>
</html>