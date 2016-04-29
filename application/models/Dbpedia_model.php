<?php
/**
 * Created by PhpStorm.
 * User: gon
 * Date: 28-04-2016
 * Time: 18:26
 */
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