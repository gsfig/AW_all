<?php

class Document_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_documents()
    {
        include ('DBconfig.php');
        
        // SELECT * FROM papers
        $sql = 'SELECT * FROM Papers';
        $stmt = $db->query($sql);
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    public function get_document($id)
    {
        // 17284678, 11748933
        include ('DBconfig.php');
        $stmt = $db->prepare('SELECT * FROM Papers WHERE idNCBI = :id');
        $stmt->execute(['id' => $id]);
        if ($stmt) {
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $response = null;
        }
        return $response;
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
    public function post_document($idNCBI,$title,$abstract, $link){
//         INSERT INTO papers (idNCBI, title, abstract)
//         VALUES(123,'titulo','muito abstracto');

        include ('DBconfig.php');
        $stmt = $db->prepare('INSERT INTO Paper (idNCBI, Title, Abstract, linkNCBI) VALUES(:idNCBI, :title, :abstract, :link)');
        $stmt -> bindParam(':idNCBI', $idNCBI);
        $stmt -> bindParam(':title', $title);
        $stmt -> bindParam(':abstract', $abstract);
        $stmt -> bindParam(':abstract', $link);
        $response = $stmt->execute(); // true if inserted
        
        /*if($response){
            // TODO: add mesh to DB
        }*/
       
        return $response;
    }

    
    
    
}

?>