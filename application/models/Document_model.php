<?php

class Document_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_documents()
    {

        $query = $this->db->get('paper');
        $result = $query->result();
        return $result;
    }
    public function get_document($id)
    {
        // 17284678, 11748933

        $query = $this->db->get_where('paper', array('idNCBI' => $id));
        if ($query->num_rows() < 1){
            return null;
        }
        return $query->result();

    }

    public function getannotation($idannotation){
        $query = $this->db->get_where('annotation', array('idannotation' => $idannotation));
        if ($query->num_rows() < 1){
            return null;
        }
        return $query->result();

    }
    public function listPaperAnnotationsByuser($userid){
//        echo $userid;
        $this->db->select('fkpaper, fkannotation');
        $query = $this->db->get_where('paperannotation', array('fkuser' => $userid));

        // TODO: esta sera forma de verificar resultado
        if ($query->num_rows() < 1){
            return null;
        }
        return $query->result();
    }

    /*
     * in: paper id
     * from paperannotations table
     * returns: array of fkannotations for that paper
     */
    public function list_paper_annotation($idpaper)
    {
        $this->db->select('fkuser,fkannotation');
        $query = $this->db->get_where('paperannotation', array('fkpaper' => $idpaper));

        // TODO: esta sera forma de verificar resultado
        if ($query->num_rows() < 1){
            return null;
        }
        return $query->result();
    }
    public function getUserAnnotation($idPaperAnnotation){
        $this->db->select('fkuser');
        $query = $this->db->get_where('paperannotation', array('idpaperannotation' => $idPaperAnnotation));

        if ($query->num_rows() < 1){
            return null;
        }
        return $query->result();
    }



    public function post_document($idNCBI,$title,$abstract){

        $data = array(
                'idNCBI' => $idNCBI,
                'title' => $title,
                'abstract' => $abstract
        );
        $this->db->insert('paper', $data);
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
        else{
            return false;
        }

        // TODO: add mesh to DB
    }
    private function value_exists($table, $array)
    {
        $query = $this->db->get_where($table, $array);
        return $query->result();

    }

    public function post_annotation($text, $offset, $size, $ssm_score, $fkChemCompound, $subtype, $chebi_score, $ssm_entity, $type){

        $data = array(
            // dava com ['text'] em vez de ->text
            'text' => $text,
            'ssm_score' => $ssm_score,
            'fkchemicalcompound' => $fkChemCompound,
            'subtype' => $subtype,
            'chebi_score' => $chebi_score,
            'ssm_entity' => $ssm_entity,
            'type' => $type,
            'offset' => $offset,
            'size' =>$size
        );
//        echo $data;
        // if data already exists in DB doesnt insert
        $rowExists = $this->value_exists('annotation', $data);
        
//                if(!is_null($rowExists[0])){
        if(count($rowExists) > 0){
            $return = (int)$rowExists[0]->idannotation;
        }
        else{
            $return = $this->insert($data,'annotation');
        }

        return $return;

    }
    public function post_User_paper_annotation($fkpaper, $fkuser, $fkannotation){
        $paperannotation = array(
            'fkpaper' => $fkpaper,
            'fkuser' => $fkuser,
            'fkannotation' => $fkannotation
        );
        $rowExists = $this->value_exists('paperannotation', $paperannotation);
        if(count($rowExists) > 0){
            $idpaperannotationInserted = (int)$rowExists[0]->idpaperannotation;
        }
        else{
            $idpaperannotationInserted = $this->insert($paperannotation, 'paperannotation');
        }
        return $idpaperannotationInserted;

    }



    public function post_paper_annotation($idNCBI, $annotation, $user){

        if(is_null($user)){
            $user = 1;
        }
        
        $this->db->select('idpaper');
        $query = $this->db->get_where('paper', array('idNCBI' => $idNCBI));
        $paperid  = $query->result();

        if(count($paperid) > 0){
            $paperid = (int)$paperid[0]->idpaper;
        }
        else{
            // paper not found in DB, get it
        }

        foreach ($annotation->abstract->sentences as $sentence){
            foreach ($sentence->entities as $entity){
                // get fkchemicalcompound if not exist => 1 (DB has this)

//                $chebi_id = $entity['chebi_id']; // isto funcionava? talvez seja de estar a usar json temporário para nao ter de anotar??
                $chebi_id = $entity->chebi_id;
                $query = $this->getChemFK($chebi_id);



                if ($query->num_rows() > 0){
                    $result = $query->result();
//                    echo "post_paper_annotation, query select idChemCompound, numRows > 0: "; print_r($result); echo "\n";
                    $fkChem = (int)$result[0]->idchemicalcompound;
                }
                else{ // there is another chebiID not in the DB

                    // chebi_id == 1, should be in DB


                    $chemCompound = $this->getCompound($chebi_id);

//                    if(!isset($chemCompound)){
//                        echo "not set \n";
//                        echo "chebi ID "; print_r($chebi_id); echo "\n";
//                        echo "chemCompound from curl: "; print_r($chemCompound); echo "\n";
//                        echo "fkChem from curl: "; print_r($fkChem); echo "\n";
//                    }
//                    echo "post_paper_annotation, entity: "; print_r($entity); echo "\n";

//                    echo "post_paper_annotation, getCompound: "; print_r($chemCompound); echo "\n";


                    $fkChem = $chemCompound->idchemicalcompound;



//                    echo "fkChem from curl: "; print_r($fkChem); echo "\n";
//                    die();
                    // TODO: add new chemical compound to DB and then get it $fkChem = ....
                    

                }
//                echo "\n";

                $data = array(
                    // dava com ['text'] em vez de ->text
                    'text' => $entity->text,
                    'ssm_score' => $entity->ssm_score,
                    'fkchemicalcompound' => $fkChem,
                    'subtype' => $entity->subtype,
                    'chebi_score' => $entity->chebi_score,
                    'ssm_entity' => (int)$entity->ssm_entity,
                    'type' => $entity->type,
                    'offset' => $entity->offset,
                    'size' =>$entity->size
                );

                // if data already exists in DB doesnt insert
                $rowExists = $this->value_exists('annotation', $data);
//                if(!is_null($rowExists[0])){
                if(count($rowExists) > 0){
                    $idannotationInserted = (int)$rowExists[0]->idannotation;
                }
                else{
                    $idannotationInserted = $this->insert($data, 'annotation');
                }
                $paperannotation = array(
                    'fkpaper' => $paperid,
                    'fkuser' => $user,
                    'fkannotation' => $idannotationInserted
                );
                $rowExists = $this->value_exists('paperannotation', $paperannotation);
                if(count($rowExists) > 0){
                    $idpaperannotationInserted = (int)$rowExists[0]->idpaperannotation;
                }
                else{
                    $idpaperannotationInserted = $this->insert($paperannotation, 'paperannotation');
                }
            }
        }


        // POSTMAN
        // get fkchemicalcompound if not exist => 1 (database does this)
//        $chebi_id = $this->getinput($annotation, 'chebi_id');
//        $this->db->select('idchemicalcompound');
//        $query = $this->db->get_where('chemicalcompound', array('chebiid' => $chebi_id));
//        $fkChibi = $query->result();
//        }

//        $data = array(
//                    'text' => $this->getinput($annotation, 'text'),
//                    'ssm_score' => $this->getinput($annotation, 'ssm_score'),
//                    'fkchemicalcompound' => (int)$fkChibi[0]->idchemicalcompound,
//                    'subtype' => $this->getinput($annotation, 'subtype'),
//                    'chebi_score' => $this->getinput($annotation, 'chebi_score'),
//                    'ssm_entity' => $this->getinput($annotation, 'ssm_entity'),
//                    'type' => $this->getinput($annotation, 'type'),
//                    'offset' => $this->getinput($annotation, 'offset'),
//                    'size' => $this->getinput($annotation, 'size')
//        );


        // insert paperannotation with paper id, user and annotationid


        return true;



    }
    private function getinput($annotation, $variable){
        // não vai receber o $annotation total quando usar os foreach
        return $annotation->abstract->sentences[0]->entities[0]->$variable;

    }

    public function getChemFK($chebi_id){
        $this->db->select('idchemicalcompound');
        return $this->db->get_where('chemicalcompound', array('chebiid' => $chebi_id));

    }

    private function insert($data,$tableName){

        $this->db->insert($tableName, $data);
        if($this->db->affected_rows() > 0)
        {
            return $this->db->insert_id();;
        }
        else{
            return false;
        }

    }

    public function getCompound($chebi_id){
        $this->load->model('Chemical_model');
        $this->load->model('API_model');
        $this->load->library('utilities');

        $ChemProperties = $this->Chemical_model->getChemical($chebi_id);
        if(count($ChemProperties) < 1){

            $compound = $this->API_model->chebi_getcompleteentity_chebi($chebi_id);
            $ChemFromChebi = $this->utilities->chebiToDB($compound);
            $inserted = $this->Chemical_model->post_ChemicalDB($ChemFromChebi);

            //get from DB
            $ChemProperties = $this->Chemical_model->getChemical($chebi_id);
            $chemName = $ChemProperties->chebiname;

            $chemName = ucwords($chemName);

            $url = $this->utilities->getUrlDbpedia($chemName);
            $data = $this->cheminfo($url);

            if(isset($data)){
                $chemInfo = $this->utilities->chemInfo($data);
                $this-> Chemical_model -> updateDbpedia($chebi_id, $chemInfo);
                $ChemProperties = $this->Chemical_model->getChemical($chebi_id); // get chemical with Dbpedia info
            }
        }
        return $ChemProperties;
    }
    private function cheminfo($url){
        $this->load->model('Dbpedia_model');
        $result =  json_decode($this->Dbpedia_model->request($url));
        return $result;
    }





}

?>