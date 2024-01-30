<?php
require("login.class.php");
use PHPMailer\PHPMailer\PHPMailer;

require '/home/tp-home008/brepair/public_html/ProgWeb/ProgWeb/ProjetBACKUP/PHPMailer-master/src/PHPMailer.php';
require '/home/tp-home008/brepair/public_html/ProgWeb/ProgWeb/ProjetBACKUP/PHPMailer-master/src/Exception.php';
require '/home/tp-home008/brepair/public_html/ProgWeb/ProgWeb/ProjetBACKUP/PHPMailer-master/src/SMTP.php';


function generateRandomPassword($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $password = '';
    $characterCount = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, $characterCount - 1)];
    }

    return $password;
}
function isEmailInDatabase($email) {
    $stored_users = json_decode(file_get_contents("dataAccount.json"), true);
    

    foreach ($stored_users as $user) {

        if ($email === $user['email']) {
            return true;
        }
    }

    return false;
}
function updatePassword($email, $newPassword) {
    $stored_users = json_decode(file_get_contents("dataAccount.json"), true);

    foreach ($stored_users as &$user) {
        if ($email === $user['email']) {
            var_dump($email === $user['email']);
            $user['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            break;
        }
    }


    if (file_put_contents("dataAccount.json", json_encode($stored_users, JSON_PRETTY_PRINT))) {
        return true;
    } else {
        return false;
    }
}




if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];


    if (isEmailInDatabase($email)) {
        $newPassword = generateRandomPassword();

        if (updatePassword($email, $newPassword)) {
            $mail = new PHPMailer;
            $mail->addAddress($email);
            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->Port = '465';
            $mail->isHTML();
            $mail->Username= 'yannickdalmeida0@gmail.com';
            $mail->Password = 'nkklxucymxtvindp';
            $mail->setFrom('no-reply@howcode.org');
            $mail->Subject = "Reinitialisation du mot de passe";
            $mail->Body = "Votre nouveau mot de passe est : $newPassword";

            if ($mail->send()) {
                $successMessage = "Un nouveau mot de passe a été envoyé à votre adresse e-mail.";
            } else {
                $errorMessage = "Une erreur s'est produite lors de l'envoi de l'e-mail. Veuillez réessayer plus tard.";
            }
        } else {
            $errorMessage = "Une erreur s'est produite lors de la mise à jour du mot de passe. Veuillez réessayer plus tard.";
        }
    } else {
        $errorMessage = "Adresse e-mail non trouvée dans la base de données.";
    }
}

if (isset($_POST['submit'])) {
    $user = new LoginUser($_POST['email'], $_POST['password']);
}

if (isset($_GET['icinacc'])) {
    header("location: registerPage.php");
    exit();
}

if (isset($_GET['educampus'])) {
    header("location: homePage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Page de connexion</title>
    <link href="style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
</head>
<body>
<a href="?educampus"><img class="logo" src="bankImage/educampus.png" style="float:left" width="200" height="200"></a>
<style>
    body {
        background-image: url('bankImage/fond.png');
        background-repeat: repeat;
        background-size: 25%;
    }
</style>
<div class="log">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off" id="login-form" class="formLognReg">
        <h2>Formulaire de connexion</h2>
        <h4>Les deux champs sont <span>requis.</span></h4>

        <label>Adresse e-mail</label>
        <input type="email" name="email" id="username" placeholder="Adresse e-mail..." required>

        <label>Mot de Passe</label>
        <input type="password" name="password" id="password" placeholder="Mot de passe...">
        <i class="fa-solid fa-eye" id="show-password"></i>
        <script>
            const showPassword = document.querySelector("#show-password");
            const passwordField = document.querySelector("#password");
            showPassword.addEventListener("click", function () {
                this.classList.toggle("fa-eye-slash");
                const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                passwordField.setAttribute("type", type);
            });
        </script>

        <h6>Vous n'avez pas de compte ? Cliquez <a href="?icinacc">ici</a> pour vous en créer un !</h6>

        <button type="submit" name="submit">Connexion</button>
        <p class="error"><?php echo @$user->error ?></p>
        <p class="success"><?php echo @$user->success ?></p>
        <button type="submit" name="forgot_password">Mot de passe oublié</button>
    <p class="error"><?php echo @$errorMessage ?></p>
    <p class="success"><?php echo @$successMessage ?></p>
    <script>
        function validateForm() {
            var email = document.getElementById('forgot-email').value;
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email)) {
                alert('Veuillez entrer une adresse e-mail valide.');
                return false;
            }
        }
    </script>
    </form>
</div>
</body>
</html>


