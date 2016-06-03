<?php

class Login_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function post_signUp($username,$email,$password ){
        $token = 'undefined';
        $data = array(
            'email' => $email,
            'username' => $username,
            'password' => $password,
            'token' => $token
        );
        $this->db->insert('users', $data);
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function getUser($username,$password ){
        $query = $this->db->get_where('users', array('username' => $username, 'password' => $password));
        $result = $query->result();
        return $result;
    }

    // should receive oldtoken as well so that WHERE is with username and oldtoken
    public function UpdateToken($username, $token ){

        $data = array(
            'token' => $token
        );
        $array = array('username' => $username);

        $this->db->where($array);
        $this->db->update('users', $data);
        if($this->db->affected_rows() > 0)
        {
            return true;
        }
        else{
            return false;
        }
    }
    public function getuserid($username){

        $query = $this->db->get_where('users', array('username' => $username));
        $result = $query->result();
        $userid = null;

        if(count($result) > 0){
            $userid = $result[0]->idUser;
        }
        else{
        }
        return $userid;
    }
    public function getusername($id){
        $this->db->select('username');
        $query = $this->db->get_where('users', array('idUser' => $id));
        $result = $query->result();
        $username = null;

        if(count($result) > 0){
            $username = $result[0]->username;
        }
        else{
        }
        return $username;
    }




}

?>