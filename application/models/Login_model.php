<?php

class Login_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function post_signUp($username,$email,$password ){
        include ('DBconfig.php');
        $token = 'undefined';
 /*       $username = 'a';
        $email = 'email';
        $password = 'password';*/

        $stmt = $db->prepare('INSERT INTO users (Email, Username, Password, Token) VALUES(:email, :username, :password, :token)');
        $stmt -> bindParam(':email', $email);
        $stmt -> bindParam(':username', $username);
        $stmt -> bindParam(':password', $password);
        $stmt -> bindParam(':token', $token);
        $response = $stmt->execute(); // true if inserted



        /*if($response){
            // TODO: add mesh to DB
        }*/

        return $response;
    }

    public function getUser($username,$password ){
        include ('DBconfig.php');
        $userInfo = $db->query("SELECT Username FROM users WHERE Username='$username' AND Password='$password'");
        return $userInfo->fetchAll();
    }

    public function UpdateToken($username,$password, $token ){
        include ('DBconfig.php');
        $stmt = $db->prepare('UPDATE users SET Token=:token WHERE Username=:username AND Password=:password');
        $stmt -> bindParam(':username', $username);
        $stmt -> bindParam(':password', $password);
        $stmt -> bindParam(':token', $token);
        return  $stmt->execute(); // true if inserted

    }




}

?>