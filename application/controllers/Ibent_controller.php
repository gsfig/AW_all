<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
// ===========================================================================
// - Inicio.php (1a Versão)
// - Guardar em controllers/
// - Controlador das páginas iniciais
// ===========================================================================

// echo "ibent_annotate controller ".PHP_EOL;

// echo $_SERVER['SERVER_ADDR'];
// echo $_SERVER['SERVER_PORT'];


class Ibent_annotate extends REST_Controller {
	function __construct() {
		parent::__construct ();
	}	
	private function pull_data(){
// 		retrives text from textbox with name="text"
		return $_POST["text"];		
	}
	private function annotate($text_anotate){
		$this->load->model('Ibent_model');
		return $this->Ibent_model->annotate($text_anotate);	
	}
	public function free_text()
	{	
		$text_to_anotate = $this->pull_data();
		echo $text_to_anotate;
		$anottation_decoded = json_decode($this->annotate($text_to_anotate),TRUE);
// 		$anottation = $this->annotate($text_to_anotate);
		echo '<pre>'; print_r($anottation_decoded);
	}




	// TODO: Remove this function
	public function idToAbstract()
	{
//		27100513
		// loads API model to use below
		$this->load->model('API_model');
		// remove white space
		$paper_ids = preg_replace('/\s+/', '', $this->pull_data());
		
		// check if paper exists in DB
		// if exists, return data
		
		// if does not exist, fetch, annotate, save to DB
		
			// FETCH
			
			// send API model text from textbox(papers ID)
			// returns abstracts
// 			$papers = $this->API_model->ncbi_efetch_papers($paper_ids);
// 			echo '<pre>', htmlentities($papers), '</pre>';
			$papers = $this->API_model->ncbi_efetch_papers($paper_ids);
// 			echo '<ul>';
// 			echo 'tipo de variavel do model: '.gettype($papers);
			// convert in simple xml object
			$xml = simplexml_load_string($papers);
// 			echo '<ul>';
// 			echo 'tipo de objecto do xml: '.get_class($xml);
// 			echo '<ul>';
			
			$pmid = (string) $xml->PubmedArticle->MedlineCitation->PMID;
// 			echo '<ul>';
// 			echo '<pre>', htmlentities($papers), '</pre>';
// 			echo '<ul>';
			
			// ANOTATE
			$abstract = (string) $xml->PubmedArticle->MedlineCitation->Article->Abstract->AbstractText;
			$anottation = $this->annotate($abstract);
			$anottation_decoded = json_decode($anottation,TRUE);
// 			$anottation = $this->annotate($abstract);
			echo "IBEnt";
			echo '<br>';
// 			echo '<pre>'; print_r($anottation);
			echo '<pre>'; print_r($anottation_decoded);
			// SAVE DB
// 			$title = (string) $xml->PubmedArticle->MedlineCitation->Article->ArticleTitle;
// 			$this->load->model('DB_model');
// 			$this->DB_model->insert_paper($pmid,$title,$abstract);
// 			$this->DB_model->insert_annotated_paper($pmid,$anottation);
			echo"<br>" . "JSON" . "<br>";
			print_r($anottation);
			
		
	}
}