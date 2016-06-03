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
    private function send_reply($result, $messageOK, $messageFAIL)
    {
        // Check if data store contains documents(in case the database result returns NULL)
        if ($result) {
            // Set the response and exit
            $this->response([
                'payload' => $result,
                'message' => "$messageOK"
            ], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

        } else {
            // Set the response and exit
            $this->response([
                'status' => FALSE,
                'message' => "$messageFAIL"
            ], REST_Controller::HTTP_UNAUTHORIZED);
        }
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

        if(!is_null($result)){
            $result = $this->updateToken($username,$password);
        }
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

            $token = $this->updateToken($username);
            $this->send_reply($token, 'logged in ', 'fail login');

        } else {
            $this->send_reply(null, 'logged in ', 'fail login');
        }
    }
    private function updateToken($username){
        $this->load->model('Login_model');
        $token = $username . " | " . uniqid() . uniqid() . uniqid();
        $update = $this->Login_model->UpdateToken($username, $token);
        return json_encode($token);
    }
    function logout_post(){

        // grab user and password
        $data = json_decode(file_get_contents("php://input"));
        $username = $data->username;
        $token = $data->token;

        $this->load->model('Login_model');
        $result = $this->Login_model->updateToken($username, null);

        if (!is_null($result)){
            echo "logged out";
        } else {
            echo "db error log out";
        }
    }

    public function annotations_get(){
        $this->load->model('Login_model');
        $this->load->model('Document_model');

        $username = $this->get('user');

        if(is_numeric($username)){
            $username = null;
        }

        $userid = $this->Login_model->getuserid($username);
//        echo $userid;
        $paperAnnotations = $this->Document_model->listPaperAnnotationsByuser($userid);
//        print_r($paperAnnotations);
        $allAnnotations = array();
        $paperlist = array();

//        var_dump($paperAnnotations);
        if(!isset($paperAnnotations)){
            $this->send_reply(null, "ok", "no annotations found");
        }
        else{
            foreach ($paperAnnotations as $annot){ // lista total de papers

                array_push($paperlist, $annot->fkpaper);

//            print_r($annot);
//            $a = $this->Document_model->getAnnotation($annot->fkannotation);
//            print_r($a);

//            $allAnnotations[$annot->fkpaper ] = $a[0];
//            print_r($allAnnotations);
//
            }
//        print_r($paperlist);

            $uniquePapers = array_unique($paperlist);
//        print_r($uniquePapers);
            $annotations = array();
            foreach($uniquePapers as $paperID){
//            print_r($paperID);
                $annotations['list'] =  $uniquePapers;
                $annotations[$paperID] = array();
                foreach($paperAnnotations as $annot){
                    if($annot-> fkpaper == $paperID ){
                        $a = $this->Document_model->getAnnotation($annot->fkannotation);
                        array_push($annotations[$paperID], get_object_vars($a[0]));
                    }
                }

            }
//        print_r($annotations);

//        foreach ($annotations as $paper){
//
//        }

//die();

            if(empty($annotations)){
                $annotations = null;
            }
            $this->send_reply(json_encode($annotations, true), "ok", "no annotations found");

        }





    }
    
    public function delete_post(){
        $this->load->model('Db_model');

        $data = json_decode(file_get_contents("php://input"));
        $username = $data->username;

        echo $username;

        $this->Db_model->deleteDB();





//        $this->send_reply(null, "", "error deleting database");

        
    }
    
    





}






?>