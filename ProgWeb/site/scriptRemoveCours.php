<?php
$resultStatusDate = array();
$resultStatusDate['reponse'] = 'Pas OK';
$date = $_POST['dateAddCours'];
$id = $_POST['id'];
$hDebut = $_POST['hDebut'];
$hFin = $_POST['hFin'];
$jour = $_POST['jour'];
$groupe = $_POST['grp'];
$dataSemaineActu = json_decode(file_get_contents("dataDate/".$date), true);
$chrono = 8;
$h = "h";
$timer = "00";
$currentH = $chrono . $h . $timer;

while ($currentH != $hDebut) {
    $timer = $timer + 15;
    if ($timer == 60) {
        $chrono = $chrono + 1;
        $timer = "00";
    }
    $currentH = $chrono . $h . $timer;
}

while ($currentH != $hFin) {
    if ($groupe == "Les Deux") {
        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["val"] = 0;
        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["Cours"] = "";
        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["Class"] = "caseNormal";
        $dataSemaineActu[$currentH][$jour]["Groupe 1"]["id"] = 0;

        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["val"] = 0;
        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["Cours"] = "";
        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["Class"] = "caseNormal";
        $dataSemaineActu[$currentH][$jour]["Groupe 2"]["id"] = 0;
    } else {
        $dataSemaineActu[$currentH][$jour][$groupe]["val"] = 0;
        $dataSemaineActu[$currentH][$jour][$groupe]["Cours"] = "";
        $dataSemaineActu[$currentH][$jour][$groupe]["Class"] = "caseNormal";
        $dataSemaineActu[$currentH][$jour][$groupe]["id"] = 0;
    }
    $timer = $timer + 15;
    if ($timer == 60) {
        $chrono = $chrono + 1;
        $timer = "00";
    }
    $currentH = $chrono . $h . $timer;
}
        
    
file_put_contents("dataDate/".$date, json_encode($dataSemaineActu, JSON_PRETTY_PRINT));
chmod("dataDate/".$date, 0777);
$resultStatusDate['reponse'] = 'OK';
$resultStatusDate['result'] = json_encode($resultStatusDate);
echo json_encode($resultStatusDate);

?>
