<?php
    session_start();
    if(isset($_GET['icinacc'])){
        header("location: registerPage.php");
        exit();
    }

    if(isset($_GET['iciyacc'])){
        header("location: loginPage.php");
        exit();
    }

    if(isset($_GET['educampus'])){
        header("location: homePage.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Page d'enregistrement</title>
    <link href="style.css" rel="stylesheet" />
</head>
    <body>
        <a href="?educampus"><img class = "logo" src='bankImage/educampus.png' style="float:left" width ="200" height ="200"> </a>
        <style>
            body {  
                background-image: url('bankImage/fond.png');
                background-repeat : repeat;
                background-size: 25%;
            }
        </style>
        <div class = "home">
            <form action ="" method="post" enctype="multipart/form-data" autocomplete="off">
                <h2>Bienvenue sur le site Edu'campus</h2>

                <h5>Créer un compte <a href="?icinacc">ici</a> !</h5>
                <h5>Se connecter <a href="?iciyacc">ici</a> !</h5>
            </form>
        </div>
    </body>
</html>
