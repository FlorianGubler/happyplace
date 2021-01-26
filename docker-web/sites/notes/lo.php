<?php
    include "config.php";

    $logs = array();
    if(isset($_POST['submit_code'])){
        if(isset($_GET['token'])){
            $token = $_GET['token'];
            $code = $_POST['input-code'];
    
            $sql_session = "SELECT * FROM session_links where token='".$token."' AND code=".$code.";";
            $query_session = $conn->query($sql_session);
            $result_session = $query_session->fetch_array(MYSQLI_BOTH);
    
            if($result_session[0] == ""){
                header("Location: ".$rootpath."/login");
            }
            else{
                $sql_getuser = "SELECT username FROM users where id='".$result_session[1]."';";
                $query_getuser = $conn->query($sql_getuser);
                $result_getuser = $query_getuser->fetch_array(MYSQLI_BOTH);

                $checklogin = true;

                //Get Notes
                
                $notes = array("uek", "lap", "bs");
                $resultsar = array();

                $searchid = $result_session[1];

                for($i=0; $i < count($notes); $i++){
                    $sql_sel = "SELECT * FROM ".$notes[$i]." WHERE id=".$searchid.";";
                    $result_sel = $conn->query($sql_sel);
                    $sel = $result_sel->fetch_array(MYSQLI_BOTH);

                    $resultsar[$notes[$i]] = array();

                    for($a=1; $a < (count($sel)/2); $a++){
                        array_push($resultsar[$notes[$i]], "<td>".$sel[$a]."</td>");
                    }
                }
                
                $subjects = array("bwl", "che", "fr", "frvo", "ge", "mat");
                $result = array();

                for($i=0; $i < count($subjects); $i++){
                    $sql_bms = "SELECT * FROM bms_".$subjects[$i]." WHERE id=".$searchid.";";
                    $result_bms = $conn->query($sql_bms);
                    $bms = $result_bms->fetch_array(MYSQLI_BOTH);

                    $result[$subjects[$i]] = array();
                    
                    $check = 0;
                    for($a=1; $a < count($bms)/2; $a++){ 
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
                }
            }
        }
        else{
            header("Location: ".$rootpath."/login");
        }
    }
    else{
        $checklogin = false;
    }
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sharing</title>
    <link rel="stylesheet" href="<?php echo "$rootpath"; ?>/css/share.css">
</head>
<body>
    <?php if(!$checklogin){ ?>
    <div id="code-container">
        <form action="" method="post">
            <label for="input-code">Enter Your Code</label>
            <input autocomplete="off" placholder="Code" type="text" name="input-code">
            <button type="submit" name="submit_code">Enter</button>
        </form>
    </div>
    <?php } else{ ?>
        <script>document.title = "Sharing - <?php echo $result_getuser[0]; ?>";</script>
        <h1>BMS Notentabelle von <?php echo $result_getuser[0]; ?></h1>
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
                <h1>LAP Notentabelle von <?php echo $result_getuser[0]; ?></h1>
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
                <div id="bs-table">
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
        </div>
    <?php } ?>
</body>
</html>