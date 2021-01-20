<?php 
    include "navbar.php";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $subjects = array("bwl", "che", "fr", "frvo", "ge", "mat");
    $result = array();
    $endnote = array();
    $endcount = 0;
    $endnote['bms'] = "-";

    $username_login = "root";
    $sql_getusersids = "SELECT id FROM users WHERE username='".$username_login."';";
    $result_getusersids = $conn->query($sql_getusersids);
    $getusersids = $result_getusersids->fetch_array(MYSQLI_BOTH);
    $searchid = $getusersids[0];

    for($i=0; $i < count($subjects); $i++){
        $sql_bms = "SELECT * FROM bms_".$subjects[$i]." WHERE id=".$searchid.";";
        $result_bms = $conn->query($sql_bms);
        $bms = $result_bms->fetch_array(MYSQLI_BOTH);

        $result[$subjects[$i]] = array();

        if($subjects[$i] != "frvo"){
            $endnote[$subjects[$i]] = "-";
        }

        $check = 0;
        for($a=1; $a < count($bms)/2; $a++){ 
            if($bms[$a] != ""){
                $check++;
                $endnote[$subjects[$i]] += $bms[$a];
            } 
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
            $value = "<td style='background-color: ".$color.";'>".$bms[$a]."</td>";
            array_push($result[$subjects[$i]], $value);
        }
        if($endnote[$subjects[$i]] != "-"){
            $endnote[$subjects[$i]] = $endnote[$subjects[$i]] / $check;
            $endnote['bms'] += $endnote[$subjects[$i]];
            $endcount++;
        }
    }

    if($endnote['frvo'] != "-" && $endnote['fr'] != "-"){
        $endnote['fr'] = ($endnote['fr'] + $endnote['frvo']) / 2;
    }
    else if($endnote['frvo'] != "-" && $endnote['fr'] == "-"){
        $endnote['fr'] = $endnote['frvo'];
    }

    if($endnote['bms'] != "-" && $endcount != 0){
        $endnote['bms'] = $endnote['bms'] / $endcount;
    }
?>
<script>document.title = "BMS - Noten"; document.getElementById('site-title').innerHTML = "BMS - Noten"</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<link rel="stylesheet" href="<?php echo $rootpath; ?>/css/bms.css">
<h1>BMS Notentabelle von <?php echo $username_login; ?></h1>
<form action="bms_edit.php" method="post"><button id="edit-table"><i class="fas fa-pencil-alt"></i></button></form>
<div id="tables" >
    <table style="width:100%;">
        <tr>
            <th>Französisch</th>
            <?php 
                for($b=0; $b < count($result['fr']); $b++){
                    echo $result['fr'][$b];
                }
                $b=0;
            ?>
        </tr>
        <tr>
            <th>Französisch Vokabeln</th>
            <?php 
                for($b=0; $b < count($result['frvo']); $b++){
                    echo $result['frvo'][$b];
                }
                $b=0;
            ?>
        </tr>
        <tr>
            <th>Geschichte</th>
            <?php 
                for($b=0; $b < count($result['ge']); $b++){
                    echo $result['ge'][$b];
                }
                $b=0;
            ?>
        </tr>
        <tr>
            <th>BWL</th>
            <?php 
                for($b=0; $b < count($result['bwl']); $b++){
                    echo $result['bwl'][$b];
                }
                $b=0;
            ?>
        </tr>
        <tr>
            <th>Chemie</th>
            <?php 
                for($b=0; $b < count($result['che']); $b++){
                    echo $result['che'][$b];
                }
                $b=0;
            ?>
        </tr>
        <tr>
            <th>Mathematik</th>
            <?php 
                for($b=0; $b < count($result['mat']); $b++){
                    echo $result['mat'][$b];
                }
                $b=0;
            ?>
        </tr>
    </table>
</div>
<div id="calculates">
    <h2>Aktueller Stand</h2>
    <table>
        <tr>
            <th>Gesamtnote Französisch: </th>
            <td><?php echo $endnote['fr']; ?></td>
        </tr>
        <tr>
            <th>Gesamtnote Geschichte: </th>
            <td><?php echo $endnote['ge']; ?></td>
        </tr>
        <tr>
            <th>Gesamtnote BWL: </th>
            <td><?php echo $endnote['bwl']; ?></td>
        </tr>
        <tr>
            <th>Gesamtnote Chemie: </th>
            <td><?php echo $endnote['che']; ?></td>
        </tr>
        <tr>
            <th>Gesamtnote Mathematik: </th>
            <td><?php echo $endnote['mat']; ?></td>
        </tr>
        <tr>
            <td style="padding-left: 0;"><div id="splitline"></div></td>
        </tr>
        <tr>
            <th>Endnote BMS: </th>
            <td><?php echo $endnote['bms']; ?></td>
        </tr>
    </table>
</div>