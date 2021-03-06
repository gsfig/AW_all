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

            $return = $this->ncbiGet($id);
            $this->send_reply($return, "ok", "no document found");
          
        }
    }
    private function ncbiGet($id){
        $this->load->model('API_model');

        $result = null;

        $papers = $this->API_model->ncbi_efetch_papers($id);
        if($papers){
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

        }
        return $result;
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

        $return = $this->Ibent_model->annotate($text_anotate);

        if(empty($return)){
            $this->send_reply(null, "", "IBEnt error");
            die();
        }
        return $return;
    }
    public function free_text_post()
    {


        $data = json_decode(file_get_contents("php://input"));

        $text_to_anotate = $data->text;



//		$text_to_anotate = $this->pull_data();
//        echo $text_to_anotate;/
//		$text_to_anotate = "Primary Leydig cells obtained from bank vole testes and the established tumor Leydig cell line (MA-10) have been used to explore the effects of 4-tert-octylphenol (OP). Leydig cells were treated with two concentrations of OP (10(-4)M, 10(-8)M) alone or concomitantly with anti-estrogen ICI 182,780 (1M). In OP-treated bank vole Leydig cells, inhomogeneous staining of estrogen receptor (ER) within cell nuclei was found, whereas it was of various intensity among MA-10 Leydig cells. The expression of ER mRNA and protein decreased in both primary and immortalized Leydig cells independently of OP dose. ICI partially reversed these effects at mRNA level while at protein level abrogation was found only in vole cells. Dissimilar action of OP on cAMP and androgen production was also observed. This study provides further evidence that OP shows estrogenic properties acting on Leydig cells. However, its effect is diverse depending on the cellular origin. ";


        // FOR TESTING
//        $annotation = '{"abstract": {"sentences": [{"text": "Primary Leydig cells obtained from bank vole testes and the established tumor Leydig cell line (MA-10) have been used to explore the effects of 4-tert-octylphenol (OP).", "pairs": [], "id": "d0.s1", "entities": [{"sentence_offset": 96, "ssm_score": 0, "text": "MA-10", "chebi_name": "null", "subtype": null, "chebi_id": "0", "eid": "d0.s1.e0", "offset": 96, "chebi_score": 0.0, "ssm_entity": "0", "type": "chemical", "size": 5}, {"sentence_offset": 144, "ssm_score": 0, "text": "4-tert-octylphenol", "chebi_name": "null", "subtype": null, "chebi_id": "0", "eid": "d0.s1.e1", "offset": 144, "chebi_score": 0.0, "ssm_entity": "0", "type": "chemical", "size": 18}], "offset": "0"}, {"text": "Leydig cells were treated with two concentrations of OP (10(-4)M, 10(-8)M) alone or concomitantly with anti-estrogen ICI 182,780 (1M).", "pairs": [], "id": "d0.s2", "entities": [{"sentence_offset": 53, "ssm_score": 0, "text": "OP (10(-4)M", "chebi_name": "streptothricin D", "subtype": null, "chebi_id": "60828", "eid": "d0.s2.e0", "offset": 222, "chebi_score": 0.24330916833039523, "ssm_entity": "0", "type": "chemical", "size": 11}, {"sentence_offset": 53, "ssm_score": 0, "text": "OP (10(-4)M, 10(-8)M", "chebi_name": "streptothricin D", "subtype": null, "chebi_id": "60828", "eid": "d0.s2.e1", "offset": 222, "chebi_score": 0.24330916833039523, "ssm_entity": "0", "type": "chemical", "size": 20}, {"sentence_offset": 56, "ssm_score": 0, "text": "(10(-4)M, 10(-8)M) alone", "chebi_name": "delavirdine mesylate", "subtype": null, "chebi_id": "4379", "eid": "d0.s2.e2", "offset": 225, "chebi_score": -0.0601678754339086, "ssm_entity": "0", "type": "chemical", "size": 24}, {"sentence_offset": 66, "ssm_score": 0, "text": "10(-8)M) alone", "chebi_name": "delavirdine mesylate", "subtype": null, "chebi_id": "4379", "eid": "d0.s2.e3", "offset": 235, "chebi_score": -0.0601678754339086, "ssm_entity": "0", "type": "chemical", "size": 14}, {"sentence_offset": 108, "ssm_score": 0, "text": "estrogen", "chebi_name": "estrogen", "subtype": null, "chebi_id": "50114", "eid": "d0.s2.e4", "offset": 277, "chebi_score": 1.0, "ssm_entity": "0", "type": "chemical", "size": 8}, {"sentence_offset": 108, "ssm_score": 0, "text": "estrogen ICI 182", "chebi_name": "estrogen", "subtype": null, "chebi_id": "50114", "eid": "d0.s2.e5", "offset": 277, "chebi_score": 0.9, "ssm_entity": "0", "type": "chemical", "size": 16}, {"sentence_offset": 117, "ssm_score": 0, "text": "ICI 182", "chebi_name": "null", "subtype": null, "chebi_id": "0", "eid": "d0.s2.e6", "offset": 286, "chebi_score": 0.0, "ssm_entity": "0", "type": "chemical", "size": 7}], "offset": "169"}, {"text": "In OP-treated bank vole Leydig cells, inhomogeneous staining of estrogen receptor (ER) within cell nuclei was found, whereas it was of various intensity among MA-10 Leydig cells.", "pairs": [], "id": "d0.s3", "entities": [{"sentence_offset": 64, "ssm_score": 0, "text": "estrogen", "chebi_name": "estrogen", "subtype": null, "chebi_id": "50114", "eid": "d0.s3.e0", "offset": 368, "chebi_score": 1.0, "ssm_entity": "0", "type": "chemical", "size": 8}], "offset": "304"}, {"text": "The expression of ER mRNA and protein decreased in both primary and immortalized Leydig cells independently of OP dose.", "pairs": [], "id": "d0.s4", "entities": [], "offset": "483"}, {"text": "ICI partially reversed these effects at mRNA level while at protein level abrogation was found only in vole cells.", "pairs": [], "id": "d0.s5", "entities": [], "offset": "603"}, {"text": "Dissimilar action of OP on cAMP and androgen production was also observed.", "pairs": [], "id": "d0.s6", "entities": [{"sentence_offset": 27, "ssm_score": 0, "text": "cAMP", "chebi_name": "3\',5\'-cyclic AMP", "subtype": null, "chebi_id": "17489", "eid": "d0.s6.e0", "offset": 745, "chebi_score": 0.8, "ssm_entity": "0", "type": "chemical", "size": 4}, {"sentence_offset": 36, "ssm_score": 0, "text": "androgen", "chebi_name": "androgen", "subtype": null, "chebi_id": "50113", "eid": "d0.s6.e1", "offset": 754, "chebi_score": 1.0, "ssm_entity": "0", "type": "chemical", "size": 8}], "offset": "718"}, {"text": "This study provides further evidence that OP shows estrogenic properties acting on Leydig cells.", "pairs": [], "id": "d0.s7", "entities": [], "offset": "793"}, {"text": "However, its effect is diverse depending on the cellular origin.", "pairs": [], "id": "d0.s8", "entities": [], "offset": "890"}], "offset": "14"}, "corpusfile": "HELB7E"}';
        $annotation = $this->annotate($text_to_anotate);


        $result = $this->transformToFrontEnd($annotation);


        $this->send_reply($result, "ok", "ibent error");
    }
    public function paperAnnotation_get()
    {
        $this->load->model('Document_model');
        $this->load->model('Login_model');

        // POSTMAN
//        $this->load->helper('url');
//        $idNCBI = $_POST["idNCBI"];
//        $this->send_reply($idNCBI, "ok", "ibent error");
        $idNCBI = $this->get('idNCBI');

        // ANGULAR
//        $data = json_decode(file_get_contents("php://input"));
//        $idNCBI = $data->idNCBI;


        $paper = $this->Document_model->get_document($idNCBI);
        
        if(is_null($paper) ){
            $this->ncbiGet($idNCBI);
            $paper = $this->Document_model->get_document($idNCBI);
        }
        if(is_null($paper) ){
            $this->send_reply(null, "ok", "no document found");
            die();
        }
        $annotations = $this->Document_model->list_paper_annotation($paper[0]->idpaper);

        if(is_null($annotations)){
            $text_anotate = $paper[0]->abstract;

            // Annotate
            $annotation = json_decode($this->annotate($text_anotate));


            // fake annotation for testing
//            $annotation = json_decode('{"abstract": {"sentences": [{"text": "Primary Leydig cells obtained from bank vole testes and the established tumor Leydig cell line (MA-10) have been used to explore the effects of 4-tert-octylphenol (OP).", "pairs": [], "id": "d0.s1", "entities": [{"sentence_offset": 96, "ssm_score": 0, "text": "MA-10", "chebi_name": "null", "subtype": null, "chebi_id": "0", "eid": "d0.s1.e0", "offset": 96, "chebi_score": 0.0, "ssm_entity": "0", "type": "chemical", "size": 5}, {"sentence_offset": 144, "ssm_score": 0, "text": "4-tert-octylphenol", "chebi_name": "null", "subtype": null, "chebi_id": "0", "eid": "d0.s1.e1", "offset": 144, "chebi_score": 0.0, "ssm_entity": "0", "type": "chemical", "size": 18}], "offset": "0"}, {"text": "Leydig cells were treated with two concentrations of OP (10(-4)M, 10(-8)M) alone or concomitantly with anti-estrogen ICI 182,780 (1M).", "pairs": [], "id": "d0.s2", "entities": [{"sentence_offset": 53, "ssm_score": 0, "text": "OP (10(-4)M", "chebi_name": "streptothricin D", "subtype": null, "chebi_id": "60828", "eid": "d0.s2.e0", "offset": 222, "chebi_score": 0.24330916833039523, "ssm_entity": "0", "type": "chemical", "size": 11}, {"sentence_offset": 53, "ssm_score": 0, "text": "OP (10(-4)M, 10(-8)M", "chebi_name": "streptothricin D", "subtype": null, "chebi_id": "60828", "eid": "d0.s2.e1", "offset": 222, "chebi_score": 0.24330916833039523, "ssm_entity": "0", "type": "chemical", "size": 20}, {"sentence_offset": 56, "ssm_score": 0, "text": "(10(-4)M, 10(-8)M) alone", "chebi_name": "delavirdine mesylate", "subtype": null, "chebi_id": "4379", "eid": "d0.s2.e2", "offset": 225, "chebi_score": -0.0601678754339086, "ssm_entity": "0", "type": "chemical", "size": 24}, {"sentence_offset": 66, "ssm_score": 0, "text": "10(-8)M) alone", "chebi_name": "delavirdine mesylate", "subtype": null, "chebi_id": "4379", "eid": "d0.s2.e3", "offset": 235, "chebi_score": -0.0601678754339086, "ssm_entity": "0", "type": "chemical", "size": 14}, {"sentence_offset": 108, "ssm_score": 0, "text": "estrogen", "chebi_name": "estrogen", "subtype": null, "chebi_id": "50114", "eid": "d0.s2.e4", "offset": 277, "chebi_score": 1.0, "ssm_entity": "0", "type": "chemical", "size": 8}, {"sentence_offset": 108, "ssm_score": 0, "text": "estrogen ICI 182", "chebi_name": "estrogen", "subtype": null, "chebi_id": "50114", "eid": "d0.s2.e5", "offset": 277, "chebi_score": 0.9, "ssm_entity": "0", "type": "chemical", "size": 16}, {"sentence_offset": 117, "ssm_score": 0, "text": "ICI 182", "chebi_name": "null", "subtype": null, "chebi_id": "0", "eid": "d0.s2.e6", "offset": 286, "chebi_score": 0.0, "ssm_entity": "0", "type": "chemical", "size": 7}], "offset": "169"}, {"text": "In OP-treated bank vole Leydig cells, inhomogeneous staining of estrogen receptor (ER) within cell nuclei was found, whereas it was of various intensity among MA-10 Leydig cells.", "pairs": [], "id": "d0.s3", "entities": [{"sentence_offset": 64, "ssm_score": 0, "text": "estrogen", "chebi_name": "estrogen", "subtype": null, "chebi_id": "50114", "eid": "d0.s3.e0", "offset": 368, "chebi_score": 1.0, "ssm_entity": "0", "type": "chemical", "size": 8}], "offset": "304"}, {"text": "The expression of ER mRNA and protein decreased in both primary and immortalized Leydig cells independently of OP dose.", "pairs": [], "id": "d0.s4", "entities": [], "offset": "483"}, {"text": "ICI partially reversed these effects at mRNA level while at protein level abrogation was found only in vole cells.", "pairs": [], "id": "d0.s5", "entities": [], "offset": "603"}, {"text": "Dissimilar action of OP on cAMP and androgen production was also observed.", "pairs": [], "id": "d0.s6", "entities": [{"sentence_offset": 27, "ssm_score": 0, "text": "cAMP", "chebi_name": "3\',5\'-cyclic AMP", "subtype": null, "chebi_id": "17489", "eid": "d0.s6.e0", "offset": 745, "chebi_score": 0.8, "ssm_entity": "0", "type": "chemical", "size": 4}, {"sentence_offset": 36, "ssm_score": 0, "text": "androgen", "chebi_name": "androgen", "subtype": null, "chebi_id": "50113", "eid": "d0.s6.e1", "offset": 754, "chebi_score": 1.0, "ssm_entity": "0", "type": "chemical", "size": 8}], "offset": "718"}, {"text": "This study provides further evidence that OP shows estrogenic properties acting on Leydig cells.", "pairs": [], "id": "d0.s7", "entities": [], "offset": "793"}, {"text": "However, its effect is diverse depending on the cellular origin.", "pairs": [], "id": "d0.s8", "entities": [], "offset": "890"}], "offset": "14"}, "corpusfile": "Y7TESU"}');

            // TODO: check user from front end
            $user = null;

            $addedToDB = $this->Document_model->post_paper_annotation($idNCBI, $annotation, $user);

            $annotations = $this->Document_model->list_paper_annotation($paper[0]->idpaper);
        }

        $allAnnotations = array();
        foreach ($annotations as $annot){


            // prepare reply

            // get each annotation
            $a = $this->Document_model->getAnnotation($annot->fkannotation);
            
            // get username
            $username = $this->Login_model->getusername($annot->fkuser);
            
            
            $a[0]->user = $username;


            // compose array
            array_push($allAnnotations,$a[0] );
        }
        if(empty($allAnnotations)){
            $allAnnotations = null;
        }
        $this->send_reply($allAnnotations, "ok", "no annotations found");

    }
    public function paper_annotation_post(){
        $this->load->model('Document_model');
        $this->load->model('Chemical_model');
        $this->load->model('Login_model');

        $data = json_decode(file_get_contents("php://input"));

        $idNCBI = $data->document;
        $text = $data->text;
        $begin = $data->begin;
        $end = $data->end;
        $type = $data->type;
        $user = $data->username;



        $userid = $this->Login_model->getuserid($user);


        $idPaper = $this->Document_model->get_document($idNCBI);

        $size = $end - $begin;
        $chem = $this->Chemical_model->getChemical(0);
//        $fkChemCompound = json_encode($this->Chemical_model->getChemical(0));
//        $fkChemCompound = json_decode($fkChemCompound);
//        echo $idNCBI . ' ' . $text .' ' .$begin. ' ' .strval($size). ' ' .$type. ' ' .$user. ' '  ;
        $fkchem = $chem->idchemicalcompound;
//        print_r( $chem->idchemicalcompound );
//        echo $fkchem;
//        echo $text. ' ', $begin. ' ', strval($size). ' ', '0'. ' ', $fkchem. ' ', null. ' ', '0'. ' ', '0'. ' ', $type. ' ';
//        die();
//        var_dump($fkchem);
//        die();


        $idAnnotation = $this->Document_model->post_annotation($text, $begin, strval($size), '0', '1', null, '0', '0', $type);

//        echo $user;
//        print_r($idPaper);
//        print_r($idAnnotation);
//        die();


        $reply = $this->Document_model->post_User_paper_annotation($idPaper[0]->idpaper, $userid, $idAnnotation);

        $this->send_reply($reply, 'annotation added', 'failed to post annotation');

    }

    private function transformToFrontEnd($annotation){

        $annotation = json_decode($annotation);
        $this->load->model('Document_model');
        $allAnnotations = array();

        foreach ($annotation->abstract->sentences as $sentence) {
            foreach ($sentence->entities as $entity) {


                $chebi_id = $entity->chebi_id;
                $query = $this->Document_model->getChemFK($chebi_id);
                if ($query->num_rows() > 0){
                    $result = $query->result();
//                    echo "post_paper_annotation, query select idChemCompound, numRows > 0: "; print_r($result); echo "\n";
                    $fkChem = (int)$result[0]->idchemicalcompound;
                }
                else { // there is another chebiID not in the DB

                    $chemCompound = $this->Document_model->getCompound($chebi_id);
                    $fkChem = $chemCompound->idchemicalcompound;
                }
                $data = array(
                    // dava com ['text'] em vez de ->text
                    'text' => $entity->text,
                    'ssm_score' => $entity->ssm_score,
                    'fkChemicalCompound' => $fkChem,
                    'subtype' => $entity->subtype,
                    'chebi_score' => $entity->chebi_score,
                    'ssm_entity' => (int)$entity->ssm_entity,
                    'type' => $entity->type,
                    'offset' => $entity->offset,
                    'size' =>$entity->size
                );
                // compose array
                array_push($allAnnotations,$data );
            }
        }
        if(empty($allAnnotations)){
            $allAnnotations = null;
        }

        $this->send_reply($allAnnotations, "ok", "no annotations found");
        


    }
}
