<?php 

class RegisterUser{
    private $email;
    private $raw_password;
    private $encrypted_password;
    private $isProf;
    private $validation_key = "PROF2000";
    public $error;
    public $success;
    private $storage = "dataAccount.json";
    private $stored_users;
    private $new_user;

    public function __construct($email, $password, $isProf){ 
        $this->email = filter_var(trim($email), FILTER_SANITIZE_STRING);
        $this->raw_password = filter_var(trim($password), FILTER_SANITIZE_STRING);
        $this->encrypted_password = password_hash($this->raw_password, PASSWORD_DEFAULT);
        $this->isProf = filter_var($isProf, FILTER_VALIDATE_BOOLEAN);
        $this->stored_users = json_decode(file_get_contents($this->storage), true);
        $this->new_user = [
            "email" => $this->email,
            "password" => $this->encrypted_password,
            "isProf" => $this->isProf 
        ];

        if($this->checkFieldValues()){
            $this->insertUser();
        }

    }
    private function checkFieldValues(){
        if(empty($this->email) || empty($this->raw_password)){
            $this->error = "Les deux champs sont requis.";
            return false;
        }elseif(!$this->checkPasswordConfirmation()){
            return false;
        }
        else{
            return true;
        }
    }

    private function emailExists(){
        foreach ($this->stored_users as $user) {
           if($this->email == $user['email']){
              $this->error = "Adresse email déjà existante, veuillez en choisir une autre.";
              return true;
           }
        }
        return false;
    }

    private function insertUser(){
        if($this->emailExists() == FALSE){
            if ($this->isProf && (!isset($_POST['validationKey']) || $_POST['validationKey'] !== $this->validation_key)) {
                $this->error = "Clé de validation invalide.";
                return;
            }
    
            array_push($this->stored_users, $this->new_user);
            if(file_put_contents($this->storage, json_encode($this->stored_users, JSON_PRETTY_PRINT))){
                $this->success = "Enregistrement réussi avec succès.";
            }else{
                $this->error = "Une erreur s'est produite, veuillez réessayer.";
            }
        }
    }

    private function checkPasswordConfirmation() {
        $confirmPassword = filter_var(trim($_POST['confirm_password']), FILTER_SANITIZE_STRING);
        if ($confirmPassword !== $this->raw_password) {
            $this->error = "La confirmation du mot de passe ne correspond pas.";
            return false;
        }
        return true;
    }
    
}

?>
