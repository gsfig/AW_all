<?php

class Db_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function deleteDB(){

//        $this->db->delete('users');
        $this->db->empty_table('authorpaper');
        $this->db->empty_table('author');
        $this->db->empty_table('freetext');
        $this->db->empty_table('freetextannotation');
        $this->db->empty_table('iupac');

        $this->db->empty_table('links');
        $this->db->empty_table('mesh');
        $this->db->empty_table('meshpaper');
        $this->db->empty_table('registry');
        $this->db->empty_table('secondchebi');
        $this->db->empty_table('synonym');
        $this->db->empty_table('paperannotation');
        $this->db->empty_table('paper');
        $this->db->empty_table('annotation');
        $this->db->empty_table('chemicalcompound');
        $this->db->empty_table('users');

        $data = array(
            'idUser' => 1,
            'email' => null,
            'username' => null,
            'password' => null,
            'token' => null
        );
        $this->db->insert('users', $data);

        $data = array(
            'email' => 'admin',
            'username' => 'admin',
            'password' => sha1('admin'),
            'token' => null
        );
        $this->db->insert('users', $data);

        $data = array(
            'idchemicalcompound' => 1
        );
        $this->db->insert('chemicalcompound', $data);



        $data = array(
            'idpaper' => 1,
            'idNCBI' => 0,
            'title' => null,
            'abstract' => null
        );
        $this->db->insert('paper', $data);

    }






















}