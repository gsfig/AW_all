<?php
defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Login_controller extends REST_Controller{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }
    function signup_post(){

        // TODO: verify if username is taken

        // grab user and password
        $data = json_decode(file_get_contents("php://input"));
        $password = sha1($data->password);
//        $password = $data->password;
        $email = $data->email;
        $username = $data->username;
        $this->load->model('Login_model');

        $result = $this->Login_model->post_signUp($username,$email, $password );

        // if result, else error
        echo $result;
    }
    function login_post(){

        // grab user and password
        $data = json_decode(file_get_contents("php://input"));
        $password = sha1($data->password);
        $username = $data->username;

        $this->load->model('Login_model');

        $result = $this->Login_model->getUser($username,$password );

        $token = 'undefined';
        if (count($result) == 1){
            //This means that the user is logged in. This gives a token
            $token = $username . " | " . uniqid() . uniqid() . uniqid();

            $update = $this->Login_model->UpdateToken($username,$password, $token );

            echo json_encode($token);
        } else {
            echo "ERROR";
        }
    }





}






?>