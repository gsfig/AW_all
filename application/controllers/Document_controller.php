<?php
defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Document_controller extends REST_Controller
{

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
            ], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
        }
    }

    public function document_get()
    {
        // gets papers in DB
        // 17284678, 11748933
        $id = $this->get('id');
        $this->load->model('Document_model');
        
        // if no id, get all papers, else get one paper
        if ($id === NULL) {
            $result = $this->Document_model->get_all_documents();
            
        } else {
            $result = $this->Document_model->get_document($id);
            
        }
        if($result){
            $this->send_reply($result, "", "no document found");
        }
        else{ // if document is not on database
            // TODO: maybe do this in NCBIController?
        }
        

    }

    public function document_annotation_get()
    {
        $id = $this->get('id');
        $this->load->model('Document_model');
        $result = $this->Document_model->get_annotation($id);
        
        $this->send_reply($result, "", "no document found");
    }
    public function document_post(){
//         idNCBI, title, abstract
        $idNCBI = $this->post('idNCBI');
        $title = $this->post('title');
        $abstract = $this->post('abstract');
        $this->load->model('Document_model');
        $result = $this->Document_model->post_document($idNCBI,$title,$abstract);
        
        $this->send_reply($result, "document inserted sucessfully", "document not inserted");
        
//         TO TEST - POSTMAN
//         put parameters in "body"

// POST /AW_server/document/ HTTP/1.1
// Host: localhost
// Accept: application/json
// Cache-Control: no-cache
// Postman-Token: a43809f2-0ce2-6590-6115-a734ee3fc9a1
// Content-Type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW

// ----WebKitFormBoundary7MA4YWxkTrZu0gW
// Content-Disposition: form-data; name="idNCBI"

// 1234
// ----WebKitFormBoundary7MA4YWxkTrZu0gW
// Content-Disposition: form-data; name="title"

// titulo novo
// ----WebKitFormBoundary7MA4YWxkTrZu0gW
// Content-Disposition: form-data; name="abstract"

// abstrato novo
// ----WebKitFormBoundary7MA4YWxkTrZu0gW    
    }
}
