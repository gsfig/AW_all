<?php

class Dbpedia_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function request($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
?>