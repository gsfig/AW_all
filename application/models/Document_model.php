<?php

class Document_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_documents()
    {

        $query = $this->db->get('Paper');
        $result = $query->result();
        return $result;
    }
    public function get_document($id)
    {
        // 17284678, 11748933

        $query = $this->db->get_where('Paper', array('idNCBI' => $id));
        $result = $query->result();
        return $result;

    }

    public function get_annotation($id)
    {
        include ('DBconfig.php');
        $stmt = $db->prepare('SELECT PA.offset, PA.size, PA.P.idPaper,  FROM Paper P, PaperAnnotation PA WHERE P.idNCBI = :id');
        $stmt->execute(['id' => $id]);
        if ($stmt) {
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $response = null;
        }
        return $response;
    }
    public function post_document($idNCBI,$title,$abstract){

        $data = array(
                'idNCBI' => $idNCBI,
                'title' => $title,
                'abstract' => $abstract
        );
        $this->db->insert('Paper', $data);
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

    public function post_paper_annotation($idNCBI, $annotation, $user){

        if(is_null($user)){
            $user = 1;
        }
        $this->db->select('idPaper');
        $query = $this->db->get_where('Paper', array('idNCBI' => $idNCBI));
        $paperid  = $query->result();

        if(count($paperid) > 0){
            $paperid = (int)$paperid[0]->idPaper;
        }
        else{
            // paper not found in DB, get it
        }

        foreach ($annotation->abstract->sentences as $sentence){
            foreach ($sentence->entities as $entity){
                // get fkChemicalCompound if not exist => 1 (DB has this)

//                $chebi_id = $entity['chebi_id']; // isto funcionava? talvez seja de estar a usar json temporário para nao ter de anotar??
                $chebi_id = $entity->chebi_id;
                $this->db->select('idChemicalCompound');
                $query = $this->db->get_where('ChemicalCompound', array('chebiid' => $chebi_id));
                $result = $query->result();

                if(count($result) > 0){
                    $fkChem = (int)$result[0]->idChemicalCompound;
                }
                else{ // there is another chebiID not in the DB

                    // TODO: add new chemical compound to DB and then get it $fkChem = ....

                }
//                echo "\n";

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

                // if data already exists in DB doesnt insert
                $rowExists = $this->value_exists('Annotation', $data);
//                if(!is_null($rowExists[0])){
                if(count($rowExists) > 0){
                    $idAnnotationInserted = (int)$rowExists[0]->idAnnotation;
                }
                else{
                    $idAnnotationInserted = $this->insert($data, 'Annotation');
                }
                $PaperAnnotation = array(
                    'fkpaper' => $paperid,
                    'fkuser' => $user,
                    'fkAnnotation' => $idAnnotationInserted
                );
                $rowExists = $this->value_exists('PaperAnnotation', $PaperAnnotation);
                if(count($rowExists) > 0){
                    $idPaperAnnotationInserted = (int)$rowExists[0]->idPaperAnnotation;
                }
                else{
                    $idPaperAnnotationInserted = $this->insert($PaperAnnotation, 'PaperAnnotation');
                }
            }
        }


        // POSTMAN
        // get fkChemicalCompound if not exist => 1 (database does this)
//        $chebi_id = $this->getinput($annotation, 'chebi_id');
//        $this->db->select('idChemicalCompound');
//        $query = $this->db->get_where('ChemicalCompound', array('chebiid' => $chebi_id));
//        $fkChibi = $query->result();
//        }

//        $data = array(
//                    'text' => $this->getinput($annotation, 'text'),
//                    'ssm_score' => $this->getinput($annotation, 'ssm_score'),
//                    'fkChemicalCompound' => (int)$fkChibi[0]->idChemicalCompound,
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





}

?>