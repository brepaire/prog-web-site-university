<?php require("register.class.php") ?>
<?php
   if(isset($_POST['submit'])){
    $isProf = isset($_POST['isProf']) ? true : false; 
    $user = new RegisterUser($_POST['email'], $_POST['password'], $isProf);
   }

    session_start();
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"></head>
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
        <div class = "reg">
            <form action ="" method="post" enctype="multipart/form-data" autocomplete="off" id="lognreg-form" class = "formLognReg">
                <h2> Formulaire d'enregistrement </h2>
                <h4> Les deux champs sont <span>requis.</span></h4>

                <label>Adresse e-mail</label>
                <input type="email" name="email" id="email" placeholder="Adresse e-mail...">

 
                <label>Mot de Passe</label>
                <input type="password" name="password" id="password" placeholder="Mot de passe...">
                <i class="fa-solid fa-eye" id="show-password"></i>
                <script>
                    const showPassword = document.querySelector("#show-password");
                    const passwordField = document.querySelector("#password");
                    showPassword.addEventListener("click",function(){
                        this.classList.toggle("fa-eye-slash");
                        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                        passwordField.setAttribute("type", type);
                    });
                </script>
                <label>Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmer le mot de passe...">
                <i class="fa-solid fa-eye" id="show-confirm-password"></i>
                <script>
                const showConfirmPassword = document.querySelector("#show-confirm-password");
                const confirmPasswordField = document.querySelector("#confirm_password");
                showConfirmPassword.addEventListener("click", function() {
                    this.classList.toggle("fa-eye-slash");
                    const type = confirmPasswordField.getAttribute("type") === "password" ? "text" : "password";
                    confirmPasswordField.setAttribute("type", type);
                });
                </script>

                <div>
                    <label for="isProf">Vous êtes un professeur :</label>
                    <input type="checkbox" class = "checkboxRegister" name="isProf" id="isProf">
                </div>
                <div id="validation-key" style="display: none;">
                    <label for="validationKey">Entrez la clé de validation :</label>
                    <input type="text" name="validationKey" id="validationKey" placeholder="Clé de validation...">
                </div>
                <script>
                    const isProfCheckbox = document.querySelector("#isProf");
                    const validationKeyDiv = document.querySelector("#validation-key");

                    isProfCheckbox.addEventListener("change", function() {
                        if (this.checked) {
                        validationKeyDiv.style.display = "block";
                        } else {
                        validationKeyDiv.style.display = "none";
                        }
                    });
                </script>
                <h6>Vous avez déjà un compte ? Cliquez <a href="?iciyacc">ici</a> pour vous connecter !<h6>
                <button type="submit" name="submit">Enregistrer</button>
                <p class="error"><?php echo @$user->error ?></p>
                <p class="success"><?php echo @$user->success ?></p>
        
            </form>
        </div>
    </body>
</html>
