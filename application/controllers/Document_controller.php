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
        $this->load->model('API_model');
        
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

            $papers = $this->API_model->ncbi_efetch_papers($id);

            if(!$papers){
                $this->send_reply(null, "", "NCBI connectivity problem");
            }
            else{
                // convert in simple xml object
                $xml = simplexml_load_string($papers);
                $pmid = (string) $xml->PubmedArticle->MedlineCitation->PMID;
                $abstract = (string) $xml->PubmedArticle->MedlineCitation->Article->Abstract->AbstractText;
                $title = (string) $xml->PubmedArticle->MedlineCitation->Article->ArticleTitle;

                // TODO: get mesh

                // TODO: send mesh
                $addedToDB = $this->Document_model->post_document($pmid,$title,$abstract);
                if($addedToDB){
                    $result = $this->Document_model->get_document($id);
                }
                $this->send_reply($result, "", "no document found");
            }
        }
    }


    public function document_annotation_get()
    {
        $id = $this->get('id');
        $this->load->model('Document_model');
        $result = $this->Document_model->get_annotation($id);

        if(is_null($result)){ // annotation doesn't exist
            // get abstract: new get in document_model only for abstract
            // annotate

            // OR
            // post annotation($id)


        }
        
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
        

    }
    
    ///////////////   IBENT //////////////

    private function annotate($text_anotate){
        $this->load->model('Ibent_model');
        return $this->Ibent_model->annotate($text_anotate);
    }
    public function free_text_post()
    {
        $data = json_decode(file_get_contents("php://input"));
        $text_to_anotate = $data->text;

//		$text_to_anotate = $this->pull_data();
//		$text_to_anotate = "Primary Leydig cells obtained from bank vole testes and the established tumor Leydig cell line (MA-10) have been used to explore the effects of 4-tert-octylphenol (OP). Leydig cells were treated with two concentrations of OP (10(-4)M, 10(-8)M) alone or concomitantly with anti-estrogen ICI 182,780 (1M). In OP-treated bank vole Leydig cells, inhomogeneous staining of estrogen receptor (ER) within cell nuclei was found, whereas it was of various intensity among MA-10 Leydig cells. The expression of ER mRNA and protein decreased in both primary and immortalized Leydig cells independently of OP dose. ICI partially reversed these effects at mRNA level while at protein level abrogation was found only in vole cells. Dissimilar action of OP on cAMP and androgen production was also observed. This study provides further evidence that OP shows estrogenic properties acting on Leydig cells. However, its effect is diverse depending on the cellular origin. ";
        $result = $this->annotate($text_to_anotate);
//		$anottation_decoded = json_decode($result,TRUE);
//		echo '<pre>'; print_r($result);'<pre>';
        $this->send_reply($result, "ok", "ibent error");
    }
    public function paper_annotation_post()
    {
        $this->load->model('Document_model');

        // POSTMAN
//        $this->load->helper('url');
//        $idNCBI = $_POST["idNCBI"];
//        $this->send_reply($idNCBI, "ok", "ibent error");


        // ANGULAR
        $data = json_decode(file_get_contents("php://input"));
        $idNCBI = $data->idNCBI;



        if(false){ // if annotation exists for this paper

            // compose reply

        }
        else{ // else get abstract and annotate

            // get abstract
            $paper = $this->Document_model->get_document($idNCBI);

            $text_anotate = $paper[0]->abstract;

            // Annotate
            $annotation = json_decode($this->annotate($text_anotate));
            

            // POSTMAN
//            $annotation = json_decode(file_get_contents(base_url('annotation.json')));
//            $this->send_reply($annotation, "ok", "ibent error");
//            return $annotation;

            $user = null;
            $addedToDB = $this->Document_model->post_paper_annotation($idNCBI, $annotation, $user);
            $this->send_reply($addedToDB, "ok", "ibent error");



            // save annotation DB
            // compose reply
        }






//        $this->send_reply($result, "ok", "ibent error");
    }




    
    
    
}
