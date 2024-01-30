<?php
class LoginUser{
    private $email;
    private $password;
    public $error;
    public $success;
    private $storage = "dataAccount.json";
    private $stored_users;
 
    public function __construct($email, $password){
        $this->email = $email;
        $this->password = $password;
        $this->stored_users = json_decode(file_get_contents($this->storage), true);
        $this->login();
    }
 
    private function login(){
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return $this->error = "Adresse e-mail invalide.";
        }
    
        foreach ($this->stored_users as $user) {
            if($user['email'] == $this->email){
                if(password_verify($this->password, $user['password'])){
                    session_start();
                    $_SESSION['user'] = $this->email;
                    header("location: calendrier.php");
                    exit();
                }
            }
        }
        return $this->error = "Adresse e-mail ou mot de passe invalide.";
}
}

?>