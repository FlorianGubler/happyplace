<?php 
    include "config.php";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $subjects = array("bwl", "che", "fr", "frvo", "ge", "mat");
    $result = array();
	
    $username_login = $_COOKIE['session-name'];
    $sql_getusersids = "SELECT id FROM users WHERE username='".$username_login."';";
    $result_getusersids = $conn->query($sql_getusersids);
    $getusersids = $result_getusersids->fetch_array(MYSQLI_BOTH);
    $searchid = $getusersids[0];

    $most = 0;

    for($i=0; $i < count($subjects); $i++){
        $sql_bms = "SELECT * FROM bms_".$subjects[$i]." WHERE id=".$searchid.";";
        $result_bms = $conn->query($sql_bms);
        $bms = $result_bms->fetch_array(MYSQLI_BOTH);

        $result[$subjects[$i]] = array();

        $check = 0;
        for($a=1; $a < count($bms)/2; $a++){
            $check++; 
            if($bms[$a] == ""){
                $color = "#d1d1d1";
            }
            else if($bms[$a] < 4){
                $color = "#e33c30";
            }
            else if($bms[$a] > 4){
                $color = "#59d467";
            }
            else{
                $color = "#d9912b";
            }
            
            if($bms[$a] == ""){
            	$value = "";
            }
            else{
            	$value = "<td style='background-color: ".$color.";'><input name='".$subjects[$i].$a."' class='table-edit-input' value='".$bms[$a]."'></td>";
            }
            
            array_push($result[$subjects[$i]], $value);
        }
        if($check > $most){$most = $check;}
    }

    for($i=0; $i <= count($subjects); $i++){
        if(isset($_POST['add-field-btn-'.$subjects[$i]])){
        	$newfield = count($result[$subjects[$i]]) + 1;
            
        	$sql_checkexist = "SELECT * FROM bms_".$subjects[$i]." WHERE id=".$searchid.";";
        	$result_checkexist = $conn->query($checkexist);
        	$checkexist = $result_checkexist->fetch_array(MYSQLI_BOTH);
            
            if(isset($checkexist[$newfield])){
            	$sql_setdef = "INSERT INTO bms_".$subjects[$i]." (note".$newfield.") VALUES(0);";
                $result_add = $conn->query($sql_add);
            }
            else{
            	$sql_add = "ALTER TABLE bms_".$subjects[$i]." ADD note".$newfield." float;";
                $result_add = $conn->query($sql_add);
                $sql_add = "INSERT INTO bms_".$subjects[$i]." (note".$newfield.") VALUES(0);";
                $result_add = $conn->query($sql_add);
            }
            header("Location: ".$rootpath."/bms");
        }
    }

    if(isset($_POST['safe-btn'])){
        for($a=0; $a < count($subjects); $a++){
            for($b=1; $b <= count($result[$subjects[$a]]); $b++){
                $value = $_POST[$subjects[$a].$b];
                $sql_set = "UPDATE bms_".$subjects[$a]." SET note".$b." = ".$value." WHERE id=".$bms[0].";";
                $result_set = $conn->query($sql_set);
            }
        }
        header("Location: ".$rootpath."/bms");
    }

    include "navbar.php";
?>

<script>document.title = "BMS - Noten"; document.getElementById('site-title').innerHTML = "BMS - Noten"</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<link rel="stylesheet" href="<?php echo $rootpath; ?>/css/bms.css">
<h1>BMS Notentabelle editieren</h1>
<form action="<?php echo $rootpath; ?>/bms"><button id="exit-edit-table"><i class="fas fa-times"></i></button></form>
<form action="<?php echo $rootpath; ?>/bms/edit" method="post">
<div style="width: 95%;" id="tables" >
    <table style="width:100%;">
        <tr>
            <th>Französisch</th>
            <?php 
                for($b=0; $b < count($result['fr']); $b++){
                    echo $result['fr'][$b];
                }
            ?>
            <th style="padding: 0; margin: 0; background-color: rgba(0, 0, 0, 0);"><form action="<?php echo $rootpath; ?>/bms/edit" method="post"><button type="submit" name="add-field-btn-fr" class="add-column-btn"><i class="fas fa-plus"></i></button></form></th>
        </tr>
        <tr>
            <th>Französisch Vokabeln</th>
            <?php 
                for($b=0; $b < count($result['frvo']); $b++){
                    echo $result['frvo'][$b];
                }
            ?>
            <th style="padding: 0; margin: 0; background-color: rgba(0, 0, 0, 0);"><form action="<?php echo $rootpath; ?>/bms/edit" method="post"><button type="submit" name="add-field-btn-frvo" class="add-column-btn"><i class="fas fa-plus"></i></button></form></th>
        </tr>
        <tr>
            <th>Geschichte</th>
            <?php 
                for($b=0; $b < count($result['ge']); $b++){
                    echo $result['ge'][$b];
                }
            ?>
            <th style="padding: 0; margin: 0; background-color: rgba(0, 0, 0, 0);"><form action="<?php echo $rootpath; ?>/bms/edit" method="post"><button type="submit" name="add-field-btn-ge" class="add-column-btn"><i class="fas fa-plus"></i></button></form></th>
        </tr>
        <tr>
            <th>BWL</th>
            <?php 
                for($b=0; $b < count($result['bwl']); $b++){
                    echo $result['bwl'][$b];
                }
            ?>
            <th style="padding: 0; margin: 0; background-color: rgba(0, 0, 0, 0);"><form action="<?php echo $rootpath; ?>/bms/edit" method="post"><button type="submit" name="add-field-btn-bwl" class="add-column-btn"><i class="fas fa-plus"></i></button></form></th>
        </tr>
        <tr>
            <th>Chemie</th>
            <?php 
                for($b=0; $b < count($result['che']); $b++){
                    echo $result['che'][$b];
                }
            ?>
            <th style="padding: 0; margin: 0; background-color: rgba(0, 0, 0, 0);"><form action="<?php echo $rootpath; ?>/bms/edit" method="post"><button type="submit" name="add-field-btn-che" class="add-column-btn"><i class="fas fa-plus"></i></button></form></th>
        </tr>
        <tr>
            <th>Mathematik</th>
            <?php 
                for($b=0; $b < count($result['mat']); $b++){
                    echo $result['mat'][$b];
                }
            ?>
            <th style="padding: 0; margin: 0; background-color: rgba(0, 0, 0, 0);"><form action="<?php echo $rootpath; ?>/bms/edit" method="post"><button type="submit" name="add-field-btn-mat" class="add-column-btn"><i class="fas fa-plus"></i></button></form></th>
        </tr>
    </table>
</div>
<button type="submit" id="btn-safe" name="safe-btn">Speichern</button>
</form>