<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );

class Chebi_compound extends CI_Controller {
	function __construct() {
		parent::__construct ();
	}	
    
    	private function pull_data(){
        // retrives text from textbox with name="text"
		return $_POST["text"];		
	}
    
    public function compounds_main()
	{		
        // loads API model to use below
		$this->load->model('API_model');
		// remove white space
		$chebi_ids = preg_replace('/\s+/', '', $this->pull_data());
	
    // check if compound exists in DB
		// if exists, return data
		
		// if does not exist, get complete entity, save to DB
		
			// GET COMPLETE ENTITY
			
			// send API model text from textbox(chebi ID)
			// returns 
			$compound = $this->API_model->chebi_getcompleteentity_chebi($chebi_ids);
			echo '<pre>', htmlentities($compound), '</pre>';
			
			// SAVE DB
    
    }  
    
    public function compounds_ontology()
	{		
        // loads API model to use below
		$this->load->model('API_model');
		// remove white space
		$chebi_ids = preg_replace('/\s+/', '', $this->pull_data());
	
    // check if compound exists in DB
		// if exists, return data
		
		// if does not exist, get ontology parents, save to DB
		
			// GET ONTOLOGY PARENTS
            // GET ONTOLOGY CHILDREN
            // GET ALL ONTOLOGY CHILDREN IN PATH
			
			// send API model text from textbox(chebi ID)
			// returns 
			$parents = $this->API_model->chebi_getontologyparents_chebi($chebi_ids);
			echo '<pre>', htmlentities($parents), '</pre>';

            $child = $this->API_model->chebi_getontologychildren_chebi($chebi_ids);
			echo '<pre>', htmlentities($child), '</pre>';
        
            $path = $this->API_model->chebi_getallontologychildreninpath_chebi($chebi_ids);
			echo '<pre>', htmlentities($path), '</pre>';
        
			
			// SAVE DB
    
    } 
    public function compounds_pathway()
    {
        // loads API model to use below
        $this->load->model('API_model');
        // remove white space
        $chebi_ids = preg_replace('/\s+/', '', $this->pull_data());
    
        // check if compound exists in DB
        // if exists, return data
    
        // if does not exist, get pathways, save to DB
    
        // GET PATHWAYS
        // GET PROJECTIONS HOMO SAPIENS
        	
        // send API model text from textbox(chebi ID)
        // returns
        $pathways = $this->API_model->reactome_getidentifierid_chebi($chebi_ids);
        echo '<pre>', htmlentities($pathways), '</pre>';
    
        $projections = $this->API_model->reactome_getidentifierprojection_chebi($chebi_ids);
        echo '<pre>', htmlentities($projections), '</pre>';
    
        	
        // SAVE DB
    
    }

}