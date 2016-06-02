<?php

class Dbpedia_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function request($url)
    {
        return null;
//        $ch = curl_init();
//        ini_set('MAX_EXECUTION_TIME', 90);
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $response = curl_exec($ch);
//
//        if(curl_error($ch))
//        {
//            print_r(curl_error($ch));
//            curl_close($ch);
//            return null;
//        }
//        curl_close($ch);
//        return $response;
    }
}
?>