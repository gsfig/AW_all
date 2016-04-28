<?php

class Login_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function post_signUp($username,$email,$password ){
//        include ('DBconfig.php');
        $token = 'undefined';
        $data = array(
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'token' => $token
        );
        $this->db->insert('Users', $data);
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
        else{
            return false;
        }
        /*
        $stmt = $db->prepare('INSERT INTO Users (Email, Username, Password, Token) VALUES(:email, :username, :password, :token)');
        $stmt -> bindParam(':email', $email);
        $stmt -> bindParam(':username', $username);
        $stmt -> bindParam(':password', $password);
        $stmt -> bindParam(':token', $token);
        $response = $stmt->execute(); // true if inserted */


    }

    public function getUser($username,$password ){

        // TODO: fazer com query builder
        include ('DBconfig.php');
        $userInfo = $db->query("SELECT username FROM Users WHERE Username='$username' AND password='$password'");
        return $userInfo->fetchAll();
    }

    public function UpdateToken($username,$password, $token ){
        // TODO: fazer com query builder

        include ('DBconfig.php');
        $stmt = $db->prepare('UPDATE Users SET token=:token WHERE username=:username AND password=:password');
        $stmt -> bindParam(':username', $username);
        $stmt -> bindParam(':password', $password);
        $stmt -> bindParam(':token', $token);
        return  $stmt->execute(); // true if inserted

    }




}

?>