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

require APPPATH . '/libraries/REST_Controller.php';


class Ibent_controller extends REST_Controller {
	function __construct() {
		parent::__construct ();
	}
	private function pull_data(){
// 		retrives text from textbox with name="text"
		return $_POST["text"];		
	}
	private function send_reply($result, $messageOK, $messageFAIL)
	{
		// Check if data store contains documents(in case the database result returns NULL)
		if ($result) {
			// Set the response and exit
			$this->response([
				'payload' => $result,
				'message' => "$messageOK"
			], REST_Controller::HTTP_OK); // OK (200) being the HTTP response code

		} else {
			// Set the response and exit
			$this->response([
				'status' => FALSE,
				'message' => "$messageFAIL"
			], REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
		}
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
			$papers = $this->API_model->ncbi_efetch_papers($paper_ids);
			// convert in simple xml object
			$xml = simplexml_load_string($papers);
			$pmid = (string) $xml->PubmedArticle->MedlineCitation->PMID;

			// ANOTATE
			$abstract = (string) $xml->PubmedArticle->MedlineCitation->Article->Abstract->AbstractText;
			$anottation = $this->annotate($abstract);
			$anottation_decoded = json_decode($anottation,TRUE);
			echo "IBEnt";
			echo '<br>';
// 			echo '<pre>'; print_r($anottation);
			echo '<pre>'; print_r($anottation_decoded);
			echo"<br>" . "JSON" . "<br>";
			print_r($anottation);
			// SAVE DB


			
		
	}
}