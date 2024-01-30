<?php require("datec.class.php") ?>

<?php
    /**
     * Pour la redirection d'URL
     */
    session_start();
    
    if(isset($_SESSION['user'])) {
        include_once 'login.class.php';
        include_once 'register.class.php';
        $stored_users = json_decode(file_get_contents("dataAccount.json"), true);
        $isProf = false;
        foreach ($stored_users as $user) {
            if($user['email'] == $_SESSION['user']){
                $isProf = $user['isProf'];
                break;
            }
        }
    } else {
        header("Location: loginPage.php");
        exit();
    }
    
    if(isset($_GET['logout'])){
        unset($_SESSION['user']);
        header("location: loginPage.php");
        exit();
    }
    if(isset($_GET['educampus'])){
        header("location: calendrier.php");
        exit();
    }

    

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Calendrier</title> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <div class = "logout">
            <a href="?educampus"><img class="logo" src='bankImage/educampus.png' /></a>
            <p>
            <h2 class = "txtlogout">Bienvenue :<br> <?php echo $_SESSION['user']; ?><h2>
            <a href="?logout" class = "deco">Déconnexion</a>
            </p>
        </div>
        <h1 class = "Titre"> Emploi du temps de l'année <?php  $today = date_create();
                                                                if(date_format($today,"m")<=8){
                                                                    echo date_format(date_sub($today,date_interval_create_from_date_string("1 year")),"Y")."-".date("Y");
                                                                }else{
                                                                    echo date("Y")."-".date_format(date_add($today,date_interval_create_from_date_string("1 year")),"Y");
                                                                }
                                                            ?>
        </h1>
    </header>
    <main>
        <div class="butndate">
            <form method="post" action="">
                <?php
                $currentLundiDate = date_create();
                $i = 0;
                $z = date_format($currentLundiDate, "N");
                while ($z > 1) {
                    $z--;
                    $i++;
                }  
                $currentLundiDate = date_sub($currentLundiDate, date_interval_create_from_date_string($i . " days"));
                if (isset($_POST['previousWeek']) || isset($_POST['nextWeek'])) {
                    $currentLundiDate = date_create($_POST['currentDate']);
                    if (isset($_POST['previousWeek'])) {
                        $currentLundiDate = date_sub($currentLundiDate, date_interval_create_from_date_string("1 week"));
                        $datec = new DateC(date_format($currentLundiDate, "Y-W"));

                    } elseif (isset($_POST['nextWeek'])) {
                        $currentLundiDate = date_add($currentLundiDate, date_interval_create_from_date_string("1 week"));
                        $datec = new DateC(date_format($currentLundiDate, "Y-W"));
                    }
                }
                ?>
                <input type="hidden" name="currentDate" value="<?php echo(date_format($currentLundiDate, "d-m-Y")); ?>" />
                <div class="fleche">
                    <p class="txtD">
                        <button type="submit" name="previousWeek" class="buttonD">&#x2B05;</button> Semaine du <span id="currentLundiDate"><?php echo(date_format($currentLundiDate, "d-m-Y ")); ?></span> <button type="submit" name="nextWeek" class="buttonD">&#x2B95;</button>
                    </p>
                </div>
            </form>
        </div>
        <table class="tableau">
            <?php
                /**
                * Initialisation de variables
                */
                $chrono = 8;
                $h="h";
                $timer="00";
                $json_file_name = date_format($currentLundiDate, "Y-W");
                $dataSemaine = json_decode(file_get_contents("dataDate/".$json_file_name), true);
            ?>
                <thead>
                    <tr>
                        <th class = "upcornerleft"></th>
                        <th class="tableauSepare"></th>
                        <th colspan ="2" class = Lundi> Lundi </th>
                        <th class="tableauSepare"></th>
                        <th colspan ="2" class = Mardi> Mardi </th>
                        <th class="tableauSepare"></th>
                        <th colspan ="2" class = Mercredi> Mercredi </th>
                        <th class="tableauSepare"></th>
                        <th colspan ="2" class = Jeudi> Jeudi </th>
                        <th class="tableauSepare"></th>
                        <th colspan ="2" class = Vendredi> Vendredi </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    echo"<tr>";
                    echo'<td style="text-align: center" class="backslash"><b></b></td>';
                    echo"<td class='tableauSepare'></td>";
                    for($j=0;$j<5;$j++){
                        echo "<td class='caseNormale'> Groupe 1 </td>";
                        echo "<td class='caseNormale'> Groupe 2 </td>";
                        if($j!=4){
                            echo"<td class='tableauSepare'></td>";
                        }
                    }
                    echo'</tr>';
                /**
                 * Création des horaires du tableau
                 */
            
                foreach ($dataSemaine as $heure => $jour) {
                    echo "<tr>";
                    echo "<td class='caseNormale'>$heure</td>";
                    echo "<td class='tableauSepare'></td>";
                    foreach ($jour as $j => $groupe) {
                        foreach ($groupe as $g) {
                            if ($g["val"] == 0) {
                                echo "<td class='caseNormale'></td>";
                            } else {
                                if ($g["val"] == 1) {
                                    if (strpos($g["Class"], "Both") !== false) {
                                        if ($g["Cours"] == "") {
                                            echo '<td class="td-val-1 ' . $g["Class"] . '" style="border-top: 1px solid black; text-align: center; position: relative" data-id="' . $g["id"] . '">';
                                            echo $g["Cours"];
                                            echo "</td>";
                                        } else {
                                            echo '<td class="td-val-0 ' . $g["Class"] . '" style="border-top: 1px solid black; text-align: center; position: relative" data-id="' . $g["id"] . '">';
                                            echo $g["Cours"];
                                            echo "</td>";
                                        }
                                    } else {
                                        echo '<td class="td-val-1 ' . $g["Class"] . '" style="border-top: 1px solid black; text-align: center; position: relative" data-id="' . $g["id"] . '">';
                                        echo $g["Cours"];
                                        echo "</td>";
                                    }
                                } else {
                                    echo '<td class="td-val-2 ' . $g["Class"] . '" style="text-align: center" data-id="' . $g["id"] . '">';
                                    echo $g["Cours"];
                                    echo "</td>";
                                }
                            }
                        }
                        if ($j != "Vendredi") {
                            echo '<td class="tableauSepare"></td>';
                        }
                    }
                    echo "</tr>";
                }
                ?>
                <div class="modal" id="confirmationModal">
                    
                    <div class="modal-content">
                        <h4>Supprimer le cours</h4>
                        <p>Voulez-vous vraiment supprimer ce cours ?</p>
                        <div class="modal-actions">
                        <button class="btn-confirm" onclick="">Supprimer</button>
                            <button class="btn-cancel">Annuler</button>
                        </div>
                    </div>
                </div> 

                <script>

                    var id;
                    $(document).ready(function() {
                        $(".td-val-0").on("mouseenter", function() {
                            $(this).next().append('<span class="delete-cross">&times;</span>');
                        });
                        $(".td-val-0").on("mouseleave", function() {
                            $('.delete-cross').remove();
                        });
                        $(".td-val-1").on("mouseenter", function() {
                            $(this).append('<span class="delete-cross">&times;</span>');
                        });
                        $(".td-val-1").on("mouseleave", function() {
                            $('.delete-cross').remove();
                        });
                        $(".td-val-2").on("mouseenter", function() {
                            let columnIndex = $(this).index();
                            let currentRow = $(this).closest('tr');
                            let found = false;
                            while (currentRow.prev().length > 0 && !found) {
                                currentRow = currentRow.prev();
                                currentRow.find('td').filter(function() {
                                    if ($(this).index() === columnIndex && $(this).hasClass('td-val-1')) {
                                        $(this).append('<span class="delete-cross">&times;</span>');
                                        found = true;
                                    }
                                });
                            }
                        });
                        $(".td-val-2").on("mouseenter", function() {
                            let columnIndex = $(this).index();
                            let currentRow = $(this).closest('tr');
                            let found = false;
                            while (currentRow.prev().length > 0 && !found) {
                                currentRow = currentRow.prev();
                                currentRow.find('td').filter(function() {
                                    if ($(this).index() === columnIndex && $(this).hasClass('td-val-0')) {
                                        if ($(this).index() === columnIndex && $(this).hasClass('td-val-1')) {
                                            $(this).append('<span class="delete-cross">&times;</span>');
                                            found = true;
                                        }
                                        $(this).next().append('<span class="delete-cross">&times;</span>');
                                        found = true;
                                    }
                                    if ($(this).index() === columnIndex && $(this).hasClass('td-val-1')) {
                                        if ($(this).index() === columnIndex && $(this).hasClass('td-val-0')) {
                                            $(this).next().append('<span class="delete-cross">&times;</span>');
                                            found = true;
                                        }
                                        found = true;
                                    }
                                });
                            }
                        });
                        $(".td-val-2").on("mouseleave", function() {
                            $('.delete-cross').remove();
                        });
                        $(document).on("click", ".delete-cross", function() {
                            id = $(this).closest('td').data('id');
                            $("#confirmationModal").show();
                        });
                        $(".btn-confirm").click(function() {
                            removeCoursFunction(id);
                            $("#confirmationModal").hide();
                        });
                        $(".btn-cancel").click(function() {
                            $("#confirmationModal").hide();
                        });
                    });
                </script>

                <?php
                
                
                /**
                 * Fonctionnalité de déroulement du tableau */
                /*
                echo '<tbody class="hide">';
                    for($i=22;$i<45;$i++){
                        echo'<tr>';
                        echo"<td>$chrono$h$timer</td>";
                        for($j=0;$j<10;$j++){
                            echo '<td> </td>';
                        }
                        echo("</tr>");
                        $timer = $timer+15;
                        if($timer == 60){
                            $chrono = $chrono+1;
                            $timer = "00";
                        }
                    }
                */
                ?>
            </tbody>
        </table>
           
        <?php if($isProf) { ?>
		    <button id="open-form" class="open-button" onclick="openForm()">Ajouter un cours</button>
	    <?php } ?>  
        <div class = "addCours" id="addC">
            <form method ="POST" class = "popupcours">
                <div class ="popuptxt">
                    <h2>Ajoutez un cours au calendrier : </h2>
                    <br>
                    <label>Nom de la matière :</label>
                    <input type="text" name="matiere" id="matiere" placeholder="Nom de la Matière..." required>
                    <label>Type de la matière :</label>
                    <select name="typeM" id="typeM" required>
                        <option value="" disabled selected hidden>Type du cours...</option>
                        <option value="TD">TD</option>
                        <option value="TP">TP</option>
                        <option value="Amphi">Amphi</option>
                        <option value="Examen">Examen</option>
                        <option value="Tutorat">Tutorat</option>
                    </select>
                    <label>Groupe :</label>
                    <select name="grp" id="grp" required>
                        <option value="" disabled selected hidden>Numéro de groupe...</option>
                        <option value="Groupe 1">Groupe 1</option>
                        <option value="Groupe 2">Groupe 2</option>
                        <option id="Both">Les Deux</option>

                    </select>
                    <label>Nom du professeur :</label>
                    <input type="text" name="prof" id="prof" placeholder="Nom du Professeur..." required>
                    <label>Numéro de la Salle :</label>
                    <input type="text" name="salle" id="salle" placeholder="Numéro de la Salle..." required>
                    <label>Jour :</label>
                    <select name="jour" id="jour" required>
                        <option value="" disabled selected hidden>Jour de la semaine...</option>
                        <option value="Lundi">Lundi</option>
                        <option value="Mardi">Mardi</option>
                        <option value="Mercredi">Mercredi</option>
                        <option value="Jeudi">Jeudi</option>
                        <option value="Vendredi">Vendredi</option>
                    </select>
                    <label>Début du cours :</label>
                    <select name="hDebut" id="hDebut" required>
                        <option value="" disabled selected hidden>Horaire de début...</option>
                        <?php
                        $chrono = 8;
                        $h="h";
                        $timer="00";
                        for($i=0;$i<45;$i++){
                            $horaire = $chrono.$h.$timer;
                            $timer = $timer+15;
                            if($timer == 60){
                                $chrono = $chrono+1;
                                $timer = "00";
                        }
                        echo"<option value='$horaire'>$horaire</option>";

                        }
                        ?>
                    </select>
                    <label>Fin du cours :</label>
                    <select name="hFin" id="hFin" required disabled>
                        <option value="" disabled selected hidden>Horaire de fin...</option>
                        <?php
                        $chrono = 8;
                        $h="h";
                        $timer="00";
                        for($i=0;$i<45;$i++){
                            $horaire = $chrono.$h.$timer;
                            $timer = $timer+15;
                            if($timer == 60){
                                $chrono = $chrono+1;
                                $timer = "00";
                            }
                        echo"<option value='$horaire'>$horaire</option>";

                        }
                        ?>
                    </select>
                    <label>Sur plusieurs semaines?</label>
                    <select name="recur" id="recur" required>
                    <option value=0>Non</option>";
                    <?php
                    $currentLundi = clone $currentLundiDate;
                    $currentLundiMax = clone $currentLundi;
                    $compt = 1;
                    date_add($currentLundiMax, date_interval_create_from_date_string("24 weeks"));
                    while ($currentLundi != $currentLundiMax) {
                        $currentLundi= date_add($currentLundi, date_interval_create_from_date_string("1 week"));
                        $datec = new DateC(date_format($currentLundi, "Y-W"));
                        $compt=$compt+1;
                        echo "<option value='" . date_format($currentLundi, "Y-W") . "'>" . "Sur $compt semaines". "</option>";
                    }
                    ?>
                </select>





                    <input type="hidden" id="dateAddCours" name="dateAddCours" value="<?php echo date_format($currentLundiDate, "Y-W") ?>">
                    <input type="button" id="submitCours" name="submitCours" onclick="submitCoursFunction()" class="btn" value="Ajouter le cours">
                    <div id="errorMessage"></div>
                    <button type="button" onclick="closeForm()" class="btn cancel" >Fermer</button>
                </div>
            </form>
        </div>
    </main>
    <script>
        
        /**
         * Fonction et script lié au formulaire
         */        

        const hDebut = document.getElementById('hDebut');
        const hFin = document.getElementById('hFin');
        function updateHFin() {
            const selectedIndex = hDebut.selectedIndex;
            hFin.innerHTML = "";
            for (let i = selectedIndex + 1; i < hDebut.length; i++) {
                const option = document.createElement('option');
                option.value = hDebut.options[i].value;
                option.text = hDebut.options[i].text;
                hFin.add(option);
            }
        }
        hDebut.addEventListener('change', () => {
            hFin.disabled = false;
            updateHFin();
        });

        function openForm() {
            document.getElementById("addC").style.display = "block";
        }
        
        function closeForm() {
            document.getElementById("addC").style.display = "none";
        }
        
        function submitCoursFunction() {
            if ($("#matiere").val() == "" || $("#typeM").val() == "" || $("#grp").val() == "" || $("#prof").val() == "" || $("#salle").val() == "" || $("#jour").val() == "" || $("#hDebut").val() == "" || $("#hFin").val() == "") {
                $("#errorMessage").html("Veuillez remplir tous les champs du formulaire.");
            } else {
                var descr = $("#hFin").val().split("h");
                var jour = descr[0];
                var hDebut = descr[1];

                var data = {
                'matiere': $("#matiere").val(),
                'typeM': $("#typeM").val(),
                'grp': $("#grp").val(),
                'prof': $("#prof").val(),
                'salle': $("#salle").val(),
                'jour': $("#jour").val(),
                'hDebut': $("#hDebut").val(),
                'hFin': $("#hFin").val(),
                'dateAddCours': $("#dateAddCours").val(),
                'recur': $("#recur").val()
                };
                data = $.param(data);

                jQuery.ajax({
                    type: "POST",
                    method: "POST",
                    dataType: "json",
                    data: data,
                    url: "scriptAddCours.php",
                    success: function(data) {
                        if (data['OK3'] && data['POK2']){
                            alert("Les cours ont été ajoutés dans la limite du possible.");
                            $("#resultDate").html(data['reponse']);
                    window.location.reload();
                        }
                        else if (data['OK'] ){
                            if(data['cpt']>1){
                                alert("Les cours ont été ajoutés avec succès.");
                                $("#resultDate").html(data['reponse']);
                                window.location.reload();
                        }else{
                            alert("Le cours a été ajouté avec succès.");
                                $("#resultDate").html(data['reponse']);
                                window.location.reload();
                        }
                    } else {
                        alert("Le cours n'a pas pu être ajouté");
                    }
                },
                    error: function(xhr) {
                        
                    console.log(xhr.status);
                    console.log(xhr.statusText);
                    console.log("TATA");
                    }
                });
                } 
            }


        function removeCoursFunction(id) {
            console.log(id);
            var descr = id.split("_");
            var jour = descr[0];
            var hDebut = descr[1];
            var hFin = descr[2];
            var grp = descr[3];
            console.log(jour);
            console.log(hDebut);
            console.log(hFin);
            console.log(grp);

            var data = {
                'dateAddCours': $("#dateAddCours").val(),
                'id': id,
                'hDebut':descr[1],
                'hFin':descr[2],
                'jour':descr[0],
                'grp':descr[3]

            };

            $.ajax({
                url: "scriptRemoveCours.php",
                type: "POST",
                method: "POST",
                data: data,
                dataType: "json",
                success: function(response) {
                    console.log("marche");
                    console.log(response);
                    window.location.reload();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    console.log("TATA");
                }
            });
}


    </script>



       <!-- <button type="button" class="collapsible">Développer</button>

        <script>
            var coll = document.getElementsByClassName("collapsible")[0];
            coll.addEventListener("click", function() {
                var content = document.getElementsByClassName("hide")[0];
                if (content.style.display == "none") {
                    content.style.display = "table-row-group";
                    document.getElementsByClassName("collapsible")[0].innerHTML = "Réduire"
                } else {
                    content.style.display = "none";
                    document.getElementsByClassName("collapsible")[0].innerHTML = "Développer"
                }
            });
        </script>
        -->

    </body>
</html>

