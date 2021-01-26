<?php include "config.php"; ?>
<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.5.0/css/ol.css" type="text/css">
    <link rel="stylesheet" href="<?php echo $rootpath; ?>/css/register.css" type="text/css">
    <script src="https://use.fontawesome.com/releases/v5.9.0/js/all.js" data-auto-replace-svg></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <a href="<?php echo $rootpath; ?>/index.php?light=light"><i class="fas fa-arrow-left"></i> Go Back</a>
    <form action="<?php echo $rootpath; ?>/register.php" method="post" id="register-form">
        <h3 style="margin-bottom:15px;font-size:20px; font-weight: bold;">Personalities</h3>
        <label for="getfirstname">Firstname</label>
        <input onkeydown="removeinvalidclass(this);" value="<?php echo $_GET['fn'];?>" placeholder="Enter Firstname" name="firstname" autocomplete="off" type="text" id="getfirstname"><br>
        <label for="getlastname">Lastname</label>
        <input onkeydown="removeinvalidclass(this);" value="<?php echo $_GET['ln'];?>" placeholder="Enter Lastname" name="lastname" autocomplete="off" type="text" id="getlastname"><br>
        <h3 style="margin-bottom:15px;font-size:20px;font-weight: bold;">Location</h3>
        <label for="getadress">Adress & Postcode</label>
        <input onkeydown="removeinvalidclass(this);" value="<?php echo $_GET['adress'];?>" placeholder="Enter Adress & Postcode" name="adress" autocomplete="off" type="text" id="getadress"><br>
        <label for="colorpicker">Personal Marker Color</label>
        <input onchange="removeinvalidclass(this);" value="<?php if(isset($_GET['color'])){echo urldecode($_GET['color']);} else{echo "#32a852";}?>" type="color" name="color" id="colorpicker">
        <button type="submit" name="submit" class="registerbtn"><i class="fas fa-sign-in-alt"></i> Register</button>
    </form>
    <script>
        function removeinvalidclass(obj){
            obj.classList.remove('invalid-input');
        }
    	function geocodejs(adress, newid, color, firstname, lastname, callback){
        	var obj;
        	let url = 'https://nominatim.openstreetmap.org/?format=json&addressdetails=1&q={'+adress+'}&format=json&limit=1';
            fetch(url).then(res => res.json())
                .then((out) => {obj = out[0];})
                .then(() => callback(obj, newid, color, firstname, lastname))
                .catch(err => { throw err });
        }
        function getData(data, newidget, colorget, firstnameget, lastnameget){
        	if(data == null){
            	window.location.href = 'register/error/adress/firstname/'+firstnameget+'/lastname/'+lastnameget+'/id/'+newidget+'/color/'+encodeURIComponent(colorget);
            	//window.location.href = 'register.php?errorget=adress'+'&fn='+firstnameget+'&ln='+lastnameget+'&newid='+newidget+'&color='+encodeURIComponent(colorget);
            }
            else{
            	window.location.href = '<?php echo $rootpath; ?>/submittodb.php?'+'fn='+firstnameget+'&ln='+lastnameget+'&newid='+newidget+'&color='+encodeURIComponent(colorget)+'&lat='+data['lat']+'&lon='+data['lon'];
            }
        }
    </script>
<?php
	function submit($adressget, $newidget, $colorget, $firstnameget, $lastnameget){
    	$adressget = urlencode($adressget);
        echo "<script>geocodejs('".$adressget."', '".$newidget."', '".$colorget."', '".$firstnameget."', '".$lastnameget."', getData);</script>";
	}

    $errors = array(); 
    $errorscheck = array();
    $inputs = array(
        1 => "getfirstname",
        2 => "getlastname",
        3 => "getadress",
        4 => "colorpicker",
    );

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    if(isset($_GET['errorget'])){
        echo "<div class='error-container'><p class='error'><i class='fas fa-exclamation-triangle'></i> Adress does not exist</p><br></div>";
    }

    if (isset($_POST['submit'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $adress = $_POST['adress'];
        $adress = str_replace(',', '', $adress);
        $color = $_POST['color'];

        if (empty($firstname)) { array_push($errors, "Firstname missing"); array_push($errorscheck, "getfirstname"); }
        if (empty($lastname)) { array_push($errors, "Lastname missing"); array_push($errorscheck, "getlastname");}
        if (empty($adress)) { array_push($errors, "Adress missing"); array_push($errorscheck, "getadress");}
        if (empty($color)) { array_push($errors, "Color missing"); array_push($errorscheck, "colorpicker");}
        
        if (is_numeric($firstname)) { array_push($errors, "Please enter a valid Name in field 'Firstname'"); array_push($errorscheck, "getfirstname");}
        if (is_numeric($lastname)) { array_push($errors, "Please enter a valid Name in field 'Lastname'"); array_push($errorscheck, "getlastname");} 
        if (is_numeric($adress)) { array_push($errors, "Please enter a valid Adress"); array_push($errorscheck, "getadress");}
        
        $sql_count = "SELECT COUNT(id) FROM apprentices";
        $result_count = $conn->query($sql_count);
        $count = $result_count->fetch_array(MYSQLI_BOTH);
        $newid = $count[0]+1;

        $sql_check = "SELECT prename, lastname FROM apprentices WHERE prename='$firstname';";
        $result_check = $conn->query($sql_check);
        $check = $result_check->fetch_array(MYSQLI_BOTH);
        
       	$sql_check_color = "SELECT * FROM markers WHERE color='$color';";
        $result_check_color = $conn->query($sql_check_color);
        $check_color = $result_check_color->fetch_array(MYSQLI_BOTH);

        if ($check[0] === $firstname && $check[1] === $lastname) {
            array_push($errors, "Person '$firstname $lastname' does already exists");
            array_push($errorscheck, "getfirstname");
            array_push($errorscheck, "getlastname");
        }
        
        if ($check_color[1] === $color) {
            array_push($errors, "Color '".$color."' is already taken");
            array_push($errorscheck, "colorpicker");
        }
        
        if (count($errors) == 0) {
            submit($adress, $newid, $color, $firstname, $lastname);
        }
        else{
            echo "<div class='error-container'>";
            for($a=0;$a<count($errors);$a++){
                echo "<p class='error'><i class='fas fa-exclamation-triangle'></i> ".$errors[$a]."</p><br>";
            }
            echo "</div>";
            for($ac=0;$ac<count($errorscheck);$ac++){
                echo "<script>document.getElementById('".$errorscheck[$ac]."').classList.add('invalid-input');</script>";
            }
            echo "<script>";
            echo "document.getElementById('".$inputs[1]."').value = '".$firstname."';";
            echo "document.getElementById('".$inputs[2]."').value = '".$lastname."';";
            echo "document.getElementById('".$inputs[3]."').value = '".$adress."';";
            echo "document.getElementById('".$inputs[4]."').value = '".$color."';";
            echo "</script>";
        }
    }
?>
</body>
</html>