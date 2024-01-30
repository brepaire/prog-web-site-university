<?php
$resultStatusDate = array();
$resultStatusDate['reponse'] = 'Pas OK';
$ok = true;
$pok2 = false;
$ok3 = false;
$cpt=0;
$matiere = $_POST['matiere'];
$typeM = $_POST['typeM'];
$groupe = $_POST['grp'];
$prof = $_POST['prof'];
$salle = $_POST['salle'];
$jour = $_POST['jour'];
$hDebut = $_POST['hDebut'];
$hFin = $_POST['hFin'];
$date = $_POST['dateAddCours'];
$datePrRec= $date;
$datePrRecF=$datePrRec;
$dateRecM =$_POST['recur'];
$chrono = 8;
$h = "h";
$timer = "00";
$currentH = $chrono . $h . $timer;
$tb;
$w3;
$a;

if ($dateRecM == "0") {
    $dateRecM = $date;
    $tbf = explode("-", strval($dateRecM));
    $W = intval($tbf[1]);
    $W++;
    $W = sprintf('%02d', $W); 
    $dateRecM = $tbf[0] . '-' . $W;
} else {
    $tbf = explode("-", strval($dateRecM));
    $W = intval($tbf[1]);
    $W++;
    $W = sprintf('%02d', $W); 
    $dateRecM = $tbf[0] . '-' . $W;
}

$datePrRecF = strval($datePrRecF);
$tbf = explode("-", strval($datePrRecF));
$W = intval($tbf[1]);
$W = sprintf('%02d', $W);
$datePrRecF = $tbf[0] . '-' . $W;







    while($datePrRecF!=$dateRecM){
        
        $ok = true;
        $dataSemaineActu = json_decode(file_get_contents("dataDate/".$datePrRecF), true);

        while ($currentH != $hDebut) {
            $timer = $timer + 15;
            if ($timer == 60) {
                $chrono = $chrono + 1;
                $timer = "00";
            }
            $currentH = $chrono . $h . $timer;
        }

        while ($currentH != $hFin) {
            if($groupe != "Les Deux"){
                if ($dataSemaineActu[$currentH][$jour][$groupe]["val"] != 0) {
                    $ok = false;
                    $pok2 = true;
                    break;
                }
            }else{
                if ($dataSemaineActu[$currentH][$jour]["Groupe 1"]["val"] != 0) {
                    $ok = false;
                    $pok2 = true;
                    break;
                }
                if ($dataSemaineActu[$currentH][$jour]["Groupe 2"]["val"] != 0) {
                    $ok = false;
                    $pok2 = true;
                    $chrono = 8;
                    break;
                }
            }
            if($groupe == "Les Deux"){
                $dataSemaineActu[$currentH][$jour]["Groupe 1"]["val"] = -1;
                $dataSemaineActu[$currentH][$jour]["Groupe 2"]["val"] = -1;
                $coursId = $jour . "_" . $hDebut . "_". $hFin . "_" .$groupe;
                $dataSemaineActu[$currentH][$jour]["Groupe 1"]["id"] = $coursId;
                $dataSemaineActu[$currentH][$jour]["Groupe 2"]["id"] = $coursId;
                switch($matiere){
                    case "ProgWeb":
                        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["Class"] = "ProgWebBoth";
                        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["Class"] = "ProgWebBoth";
                        break;
                    case "IAS":
                        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["Class"] = "IASBoth";
                        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["Class"] = "IASBoth";
                        break;
                    case "Reseaux":
                        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["Class"] = "ReseauxBoth";
                        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["Class"] = "ReseauxBoth";
                        break;
                    case "Anglais":
                        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["Class"] = "AnglaisBoth";
                        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["Class"] = "AnglaisBoth";
                        break; 
                    case "BDD":
                        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["Class"] = "BDDBoth";
                        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["Class"] = "BDDBoth";
                        break;  
                    case "Logique":
                        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["Class"] = "LogiqueBoth";
                        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["Class"] = "LogiqueBoth";
                        break;  
                    default:
                        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["Class"] = "unknowCoursBoth";
                        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["Class"] = "unknowCoursBoth";
                        break;    
                }
            }else{
                $dataSemaineActu[$currentH][$jour][$groupe]["val"] = -1;
                $coursId = $jour . "_" . $hDebut . "_". $hFin . "_" .$groupe;
                $dataSemaineActu[$currentH][$jour][$groupe]["id"] = $coursId;
                switch($matiere){
                    case "ProgWeb":
                        $dataSemaineActu[$currentH][$jour][$groupe]["Class"] = "ProgWeb";
                        break;
                    case "IAS":
                        $dataSemaineActu[$currentH][$jour][$groupe]["Class"] = "IAS";
                        break;
                    case "Reseaux":
                        $dataSemaineActu[$currentH][$jour][$groupe]["Class"] = "Reseaux";
                        break;
                    case "Anglais":
                        $dataSemaineActu[$currentH][$jour][$groupe]["Class"] = "Anglais";
                        break; 
                    case "BDD":
                        $dataSemaineActu[$currentH][$jour][$groupe]["Class"] = "BDD";
                        break;  
                    case "Logique":
                        $dataSemaineActu[$currentH][$jour][$groupe]["Class"] = "Logique";
                        break;  
                    default:
                        $dataSemaineActu[$currentH][$jour][$groupe]["Class"] = "unknowCours";
                        break;    
                }
            }
            $timer = $timer + 15;
            if ($timer == 60) {
                $chrono = $chrono + 1;
                $timer = "00";
            }
            $currentH = $chrono . $h . $timer;
        }

        if ($ok) {
            $ok3=true;

            if($groupe == "Les Deux"){
                switch($matiere){
                    case "ProgWeb":
                        $dataSemaineActu[$hDebut][$jour]["Groupe 1"]["Class"] = "ProgWebBoth";
                        $dataSemaineActu[$hDebut][$jour]["Groupe 2"]["Class"] = "ProgWebBoth";
                        break;
                    case "IAS":
                        $dataSemaineActu[$hDebut][$jour]["Groupe 1"]["Class"] = "IASBoth";
                        $dataSemaineActu[$hDebut][$jour]["Groupe 2"]["Class"] = "IASBoth";
                        break;
                    case "Reseaux":
                        $dataSemaineActu[$hDebut][$jour]["Groupe 1"]["Class"] = "ReseauxBoth";
                        $dataSemaineActu[$hDebut][$jour]["Groupe 2"]["Class"] = "ReseauxBoth";
                        break;
                    case "Anglais":
                        $dataSemaineActu[$hDebut][$jour]["Groupe 1"]["Class"] = "AnglaisBoth";
                        $dataSemaineActu[$hDebut][$jour]["Groupe 2"]["Class"] = "AnglaisBoth";
                        break; 
                    case "BDD":
                        $dataSemaineActu[$hDebut][$jour]["Groupe 1"]["Class"] = "BDDBoth";
                        $dataSemaineActu[$hDebut][$jour]["Groupe 2"]["Class"] = "BDDBoth";
                        break;  
                    case "Logique":
                        $dataSemaineActu[$hDebut][$jour]["Groupe 1"]["Class"] = "LogiqueBoth";
                        $dataSemaineActu[$hDebut][$jour]["Groupe 2"]["Class"] = "LogiqueBoth";
                        break;  
                    default:
                        $dataSemaineActu[$hDebut][$jour]["Groupe 1"]["Class"] = "unknowCoursBoth";
                        $dataSemaineActu[$hDebut][$jour]["Groupe 2"]["Class"] = "unknowCoursBoth";
                        break;    
                }
                $dataSemaineActu[$hDebut][$jour]["Groupe 1"]["val"] = 1;
                $dataSemaineActu[$hDebut][$jour]["Groupe 2"]["val"] = 1;
                $coursId = $jour . "_" . $hDebut . "_". $hFin . "_" .$groupe;
                $dataSemaineActu[$currentH][$jour]["Groupe 1"]["id"] = $coursId;
                $dataSemaineActu[$currentH][$jour]["Groupe 2"]["id"] = $coursId;
                $dataSemaineActu[$hDebut][$jour]["Groupe 1"]["Cours"] = $matiere . "<br>" . $typeM . "<br>" .  $prof . "<br>" . $salle;

            }else{
                $dataSemaineActu[$hDebut][$jour][$groupe]["val"] = 1;
                $coursId = $jour . "_" . $hDebut . "_". $hFin . "_" .$groupe;
                $dataSemaineActu[$currentH][$jour][$groupe]["id"] = $coursId;
                $dataSemaineActu[$hDebut][$jour][$groupe]["Cours"] = $matiere . "<br>" . $typeM . "<br>" .  $prof . "<br>" . $salle;
                switch($matiere){
                    case "ProgWeb":
                        $dataSemaineActu[$hDebut][$jour][$groupe]["Class"] = "ProgWeb";
                        break;
                    case "IAS":
                        $dataSemaineActu[$hDebut][$jour][$groupe]["Class"] = "IAS";
                        break;
                    case "Reseaux":
                        $dataSemaineActu[$hDebut][$jour][$groupe]["Class"] = "Reseaux";
                        break;
                    case "Anglais":
                        $dataSemaineActu[$hDebut][$jour][$groupe]["Class"] = "Anglais";
                        break; 
                    case "BDD":
                        $dataSemaineActu[$hDebut][$jour][$groupe]["Class"] = "BDD";
                        break;  
                    case "Logique":
                        $dataSemaineActu[$hDebut][$jour][$groupe]["Class"] = "Logique";
                        break;  
                    default:
                        $dataSemaineActu[$hDebut][$jour][$groupe]["Class"] = "unknowCours";
                        break;    
                }
            }
            file_put_contents("dataDate/".$datePrRecF, json_encode($dataSemaineActu, JSON_PRETTY_PRINT));
            
        } 
        $tb=explode("-",strval($datePrRecF));
            $W = $tb[1];
            $A = $tb[0];
            $WW = intval($W); 
            $WW++; 
            if ($WW>52){
                $A=intval($A);
                $A++;
                $A = strval($A);
                $W=1;
            }
            $W = sprintf('%02d', $WW);
            $datePrRecF =$tb[0].'-'.$W;
            $chrono = 8;
            $h = "h";
            $timer = "00";
            $currentH = $chrono . $h . $timer;
            chmod("dataDate/".$datePrRecF, 0777);

        $cpt++;
            
            

            
        

        }
        $resultStatusDate['reponse'] = 'OK';
        $resultStatusDate['cpt'] = $cpt;
        $resultStatusDate['OK'] = $ok;
        $resultStatusDate['POK2'] = $pok2;
        $resultStatusDate['OK3'] = $ok3;
        $resultStatusDate['result'] = json_encode($resultStatusDate);
        echo json_encode($resultStatusDate);





?>