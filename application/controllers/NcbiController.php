<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );


// echo $_SERVER['SERVER_ADDR'];
// echo $_SERVER['SERVER_PORT'];


class NcbiController extends CI_Controller {
	function __construct() {
		parent::__construct ();
	}	
	private function pull_data(){
// 		retrives text from textbox with name="text"
		return $_POST["text"];		
	}
	
	
	public function queriesToId()
	{
	    // loads API model to use below
	    $this->load->model('API_model');
	    // send API model text from textbox($query_terms)
	    //return list of papers id
	    $query_terms = $this->API_model->ncbi_esearch_papers($this->pull_data());
	    echo '<pre>', htmlentities($query_terms), '</pre>';
	}

    /**
     * @input: NCBI id's
     * @output: array with id and corresponding Title
     */
	public function idToTitle()
	{
	    // loads API model to use below
		$this->load->model('API_model');
	    // send API model text from textbox($p_ids)
	    //return list of papers' titles
	    $temp = $this->API_model->ncbi_esummary_papers($this->pull_data());
        $summary = json_decode($temp);
        $array = array();

        foreach ($summary->result->uids as $uid){
            $array[$uid] = $summary->result->$uid->title;
        }
        print_r($array);

	}
	
	public function idToLink()
	{
	    // loads API model to use below
	    $this->load->model('API_model');
	    // send API model text from textbox($pa_ids)
	    //return list of related articles' links
	    $pa_ids = $this->API_model->ncbi_elink_papers($this->pull_data());
// 	    $xml = simplexml_load_string($pa_ids);
// 	    $xml2 = $xml -> xpath('//eLinkResult/LinkSet/LinkSetDb/LinkName');
// 	    $db = $xml2[0];
// 	    echo "db name: \n";
// 	    echo $db;
// 	    $xml2 = $xml -> xpath('//eLinkResult/LinkSet/LinkSetDb/LinkName');
// 	    echo "\n xml2:  \n";
// 	    echo '<pre>',print_r($xml2);
	    
// 	    foreach ($xml2 as $linkName){
// 	        $xml -> xpath("//eLinkResult/LinkSet/LinkSetDb/[$linkName]");
// 	    }
	     echo "\n all:  \n";
	    echo '<pre>', htmlentities($pa_ids), '</pre>';
	    
	    
	    
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}

