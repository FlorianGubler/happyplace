<?php 
    include "navbar.php";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
     
    $notes = array("uek", "lap", "bs");
    $endnote = array();
    $setar = array();
    $setar["bs"] = array("M100", "M114", "M117", "M120", "M122", "M123", "M133", "M150", "M151", "M152", "M153", "M183", "M214", "M226", "M242", "M254", "M306", "M326", "M403", "M404", "M411", "M426", "M431");
    $setar["lap"] = array("praktisch", "dokumentation", "praesentation");
    $setar["uek"] = array("M304", "M305", "M101", "M318", "M105", "M307", "M335");

    $sql_getusersids = "SELECT id FROM users WHERE username='".$username_login."';";
    $result_getusersids = $conn->query($sql_getusersids);
    $getusersids = $result_getusersids->fetch_array(MYSQLI_BOTH);
    $searchid = $getusersids[0];

    $resultsar = array();

    for($i=0; $i < count($notes); $i++){
    	$emptycheck = false;
        $sql_sel = "SELECT * FROM ".$notes[$i]." WHERE id=".$searchid.";";
        $result_sel = $conn->query($sql_sel);
        $sel = $result_sel->fetch_array(MYSQLI_BOTH);
		
        $resultsar[$notes[$i]] = array();
        $endnote[$notes[$i]] = "-";
	
    	$check = 0;
        for($a=1; $a < (count($sel)/2); $a++){
        	array_push($resultsar[$notes[$i]], "<td><input name='".$notes[$i].$a."' class='table-edit-input' value='".$sel[$a]."'></input></td>");
            if($notes[$i] == "lap"){
            	switch($a){
                	case 1: $endnote[$notes[$i]] += $sel[$a]*0.5;break;
                    case 2: $endnote[$notes[$i]] += $sel[$a]*0.25;break;
                    case 3: $endnote[$notes[$i]] += $sel[$a]*0.25;break;
                }
            }
            else{
            	if($sel[$a] != ""){
                    $endnote[$notes[$i]] += $sel[$a];
                    $check++;
                }
                else{
                    $emptycheck = true;
                }
            }
        }  
        if($emptycheck){
        	$endnote[$notes[$i]] = $endnote[$notes[$i]] / $check;
        	$endnote[$notes[$i]] = round($endnote[$notes[$i]], 2);
    	}
    }
    
    if(isset($_POST['safe-lap-btn'])){
    	for($c=0;$c < count($notes); $c++){
    		if($notes[$c] == "uek"){
            	for($d=1; $d < count($resultsar['uek']); $d++){
                	$val = $_POST["uek".$d];
                    $sql_setnew = "UPDATE uek SET ".$setar["uek"][$d]." = ".$val." WHERE id=".$searchid.";";
                    $result_setnew = $conn->query($sql_setnew);
                }
            }
            else if($notes[$c] == "lap"){
            	for($d=1; $d < count($resultsar['lap']); $d++){
                	$val = $_POST["lap".$d];
                    $sql_setnew = "UPDATE uek SET ".$setar["lap"][$d]." = ".$val." WHERE id=".$searchid.";";
                    $result_setnew = $conn->query($sql_setnew);
                }
            }
            else if($notes[$c] == "bs"){
            	for($d=1; $d < count($resultsar['bs']); $d++){
                	$val = $_POST["bs".$d];
                    $sql_setnew = "UPDATE uek SET ".$setar["bs"][$d]." = ".$val." WHERE id=".$searchid.";";
                    $result_setnew = $conn->query($sql_setnew);
                }
            }
    	}
    }
?>
<script>document.title = "LAP - Noten"; document.getElementById('site-title').innerHTML = "LAP - Noten"</script>
<link rel="stylesheet" href="<?php echo $rootpath; ?>/css/lap.css">
<h1>LAP Notentabelle von <?php echo $username_login; ?></h1>
<form action="<?php echo $rootpath; ?>/lap" method="post"><button style="right: 20px;" id="edit-table"><i class="fas fa-times"></i></button></form>
<form action="<?php echo $rootpath; ?>/lap/edit" method="post">
<div id="tables" style="width:98%;">
	<h3>ÜK Module</h3>
    <table style="width:100%;">
        <tr>
            <th>M304</th>
            <th>M305</th>
            <th>M101</th>
            <th>M318</th>
            <th>M105</th>
            <th>M107</th>
            <th>M335</th>
        </tr>
        <tr>
            <?php 
                for($a=0; $a < count($resultsar['uek']); $a++){
                    echo $resultsar['uek'][$a];
                }
            ?>
        </tr>
    </table>
    <h3>IPA Abschlussprüfung</h3>
    <table style="width:100%;">
        <tr>
            <th>Resultat der Arbeit</th>
            <th>Dokumentation</th>
            <th>Fachgespräch und Präsentation</th>
        </tr>
        <tr>
            <?php 
                for($a=0; $a < count($resultsar['lap']); $a++){
                    echo $resultsar['lap'][$a];
                }
            ?>
        </tr>
    </table>
    <h3>Berufsfachschule Module</h3>
    <table style="width:100%;">
        <tr>
            <th>M100</th>
            <th>M104</th>
            <th>M114</th>
            <th>M117</th>
            <th>M120</th>
            <th>M122</th>
            <th>M123</th>
            <th>M133</th>
            <th>M150</th>
            <th>M151</th>
            <th>M152</th>
            <th>M153</th>
            <th>M183</th>
            <th>M214</th>
            <th>M226</th>
            <th>M242</th>
            <th>M254</th>
            <th>M306</th>
            <th>M326</th>
            <th>M403</th>
            <th>M404</th>
            <th>M411</th>
            <th>M426</th>
            <th>M431</th>
        </tr>
        <tr>
            <?php 
                for($a=0; $a < count($resultsar['bs']); $a++){
                    echo $resultsar['bs'][$a];
                }
            ?>
        </tr>
    </table>
</div>
<button type="submit" id="btn-safe" name="safe-lap-btn">Speichern</button>
</form>