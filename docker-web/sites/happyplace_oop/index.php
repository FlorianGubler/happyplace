<?php
    include "config.php";

    $errors = array();
    $login = false;

    //Dev Settings
    $login = false;
    $username_login = "root";

    if(isset($_POST['submit-register-btn'])){ //Register
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $adress = $_POST['adress'];
        $color = $_POST['color'];

        //Check values

        //Get Lat & Lon from Adress
        $lat = 47;
        $lon = 8;

        //Submit to DB
        $setmarker = new stdClass();
        $setapprentices = new stdClass();
        $setapprentices->firstname = $firstname;
        $setapprentices->lastname = $lastname;
        $setapprentices->marker_id = $count+1;
        $setmarker->color = $color;
        $setmarker->lat = $lat;
        $setmarker->lon = $lon;
        $table_markers->save($setmarker);
        $table_apprentices->save($setapprentices);

        //Go back & Reload site
        header("Location: $rootpath/#");
    }

    if(isset($_POST['submit-login-btn'])){ //Login
        $username = $_POST['get-username'];
        $password = $_POST['get-password'];
    }

    if(isset($_POST['del'])){ //Del from list
        $usr_id = $_POST['del'];
        $table_apprentices->delete($usr_id);
        $table_markers->delete($usr_id);

        echo "success";
        exit();
    }

    if(isset($_POST['edit'])){ //Edit List
        echo "Feature not implemented yet";
        exit();
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
                echo "<div id='list_".$person->id."' class='list-content'><p>";
                echo "<img class='icon-marker-list' width='20px' height='20px' src='$rootpath/returnmarker.php?color=".urlencode($person->marker->color)."' alt='Marker Icon'>";
                echo $person->lastname.", ".$person->firstname."</p>";
                if($login == true && $username_login == "root"){
                    echo "<button onclick='listdelete(\"edit\", ".$person->id.");' id='list-edit'><i class='fas fa-pencil-alt'></i></button><button onclick='listdelete(\"del\", ".$person->id.");' id='list-delete'><i class='fas fa-times'></i></button>";
                }
                echo "</div>";
            }
        ?>
    </div>
    <?php if($login == true && $username_login != "root"){}else{ ?>
        <a href="#register-div" id="register-button"> <?php if($login == true && $username_login == "root"){echo "Register New";}else if($login == false){echo "Login";} ?></a>
        <div id="register-div">
            <a href="#" id="register-close"><i class='fas fa-times'></i></a>
            <?php if($login == true && $username_login == "root"){ ?>
                <!-- Register Form -->
                <h2>Register</h2>
                <form id="register-form" action="" method="post">
                    <label>Personalities</label>
                    <input type="text" id="get-firstname" name="firstname" placeholder="Firstname">
                    <input type="text" id="get-lastname" name="lastname" placeholder="Lastname">
                    <label>Location & Custom Marker</label>
                    <input type="text" id="get-adress" name="adress" placeholder="Adress">
                    <input type="color" id="get-color" name="color" value="#ff00ff">
                    <button type="submit" name="submit-register-btn">Finish</button>
                </form>
            <?php }else if($login == false) {?>
                <!-- Login Form -->
                <h2>Login</h2>
                <form id="login-form"  action="" method="post">
                    <input type="text" id="get-username" name="username" placeholder="Username">
                    <input type="password" id="get-password" name="password" placeholder="Password">
                    <button type="submit" name="submit-login-btn">Login</button>
                </form>
            <?php } ?>
        </div>
    <?php } ?>
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
        function listdelete(func, personid){
            xmlreq = new XMLHttpRequest;
            xmlreq.open("POST", "", true);
            xmlreq.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xmlreq.onreadystatechange = function(){
                if(this.readyState === XMLHttpRequest.DONE && this.status === 200){
                    if(func == "del" && this.response == "success"){
                        element = document.getElementById("list_"+personid);
                        element.parentNode.removeChild(element);
                    }
                    else if(func == "edit" && this.response == "success"){
                    }
                    else if(this.response != "success"){
                        alert("Error occured: '"+this.response+"'");
                    }
                }
            }
            if(func == "del"){
                xmlreq.send("del="+personid);
            }
            else if(func == "edit"){
                xmlreq.send("edit="+personid);
            }
        }
        <?php
            foreach($apprenticesobjs as $apprentices){
                echo $apprentices->marker->ShowMarkerOnMap();
            }
        ?>
    </script>
</body>
</html>