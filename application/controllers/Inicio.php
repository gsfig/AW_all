<?php
defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' );
// ===========================================================================
// - Inicio.php (1a Versão)
// - Guardar em controllers/
// - Controlador das páginas iniciais
// ===========================================================================
class Inicio extends CI_Controller {
	private $data;
	public function __construct() {
		parent::__construct ();
	}
	
	// função chamada por omissão
	public function index() {
		$this->load->helper('url');
		$this->load->view('header');
		$this->load->view('searchbox');
// 		$this->load->view('papers_view');

		$this->load->view('idToAbstract_view');
		$this->load->view('queriesToId_view');
		$this->load->view('idToTitle_view');
		//$this->load->view('idToTile_CI'); // TEST PURPOSES, REMOVE
		//$this->load->view('idToTitle_JS'); // TEST PURPOSES, REMOVE


//		$this->load->view('idToTitle3');
//		$this->load->view('idToTitle2');
		$this->load->view('idToLink_view');
		
		$this->load->view('compoundsMain_view');
		$this->load->view('compoundsOntology_view');
        $this->load->view('compoundsPathway_view');
		$this->load->view('footer');
		
	}
}