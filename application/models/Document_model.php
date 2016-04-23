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
        $sql = 'SELECT * FROM papers';
        $stmt = $db->query($sql);
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

    public function get_document($id)
    {
        // 17284678, 11748933
        include ('DBconfig.php');
        $stmt = $db->prepare('SELECT * FROM papers WHERE idNCBI = :id');
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
        $stmt = $db->prepare('SELECT * FROM annotated_paper WHERE idNCBI = :id');
        $stmt->execute(['id' => $id]);
        if ($stmt) {
            $response = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $response = null;
        }
        return $response;
    }
    public function post_document($idNCBI,$title,$abstract){
//         INSERT INTO papers (idNCBI, title, abstract)
//         VALUES(123,'titulo','muito abstracto');

        include ('DBconfig.php');
        $stmt = $db->prepare('INSERT INTO papers (idNCBI, title, abstract) VALUES(:idNCBI, :title, :abstract)');
        $stmt -> bindParam(':idNCBI', $idNCBI);
        $stmt -> bindParam(':title', $title);
        $stmt -> bindParam(':abstract', $abstract);
        $response = $stmt->execute(); // true if inserted
       
        return $response;
    }

    
    
    
}

?>