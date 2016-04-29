<?php
defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Dbpedia_controller extends REST_Controller
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

    public function cheminfo_get(){
        $term = $this->get('name');
        $url = $this->getUrlDbpedia($term);
        $this->load->model('Dbpedia_model');
        $result =  json_decode($this->Dbpedia_model->request($url));
        $this->send_reply($result, "", "request failed");
    }


    function getUrlDbpedia($term)
    {
        $format = 'json';

        $query = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>        
PREFIX type: <http://dbpedia.org/class/yago/>
PREFIX prop: <http://dbpedia.org/property/>
PREFIX res: <http://dbpedia.org/resource/>
SELECT ?smiles ?molecularWeight ?pubchem 
WHERE {
    res:" . $term . " a type:Chemical114806838 ;
    prop:smiles ?smiles ;
    prop:molecularWeight ?molecularWeight ;
    prop:pubchem ?pubchem.
}";

        $searchUrl = 'http://dbpedia.org/sparql?'
            . 'query=' . urlencode($query)
            . '&format=' . $format;

        return $searchUrl;
    }


}

?>