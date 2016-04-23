<?php

class API_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}
	const NCBI_BASE = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/';
	const NCBI_METHOD_EFETCH = 'efetch.fcgi?';
	const NCBI_METHOD_POST = 'epost.fcgi?';
	const NCBI_DB_PUBMED = 'db=pubmed&';
	const NCBI_RETMODE_XML = '&retmode=xml';
	const NCBI_RETMODE_JSON = '&retmode=json';

	const NCBI_METHOD_ESEARCH = 'esearch.fcgi?';
	const NCBI_METHOD_ESUMMARY = 'esummary.fcgi?';
	const NCBI_METHOD_ELINK = 'elink.fcgi?';
	
	const CHEBI_BASE = 'http://www.ebi.ac.uk/webservices/chebi/2.0/test/';
	const CHEBI_METHOD_GETCOMPLETEENTITY = 'getCompleteEntity?';
	const CHEBI_METHOD_GETONTOLOGYPARENTS = 'getOntologyParents?';
	const CHEBI_METHOD_GETONTOLOGYCHILDREN = 'getOntologyChildren?';
	const CHEBI_METHOD_GETALLONTOLOGYCHILDRENINPATH ='getOntologyChildrenPath';
	const CHEBI_CATEGORY = '&searchCategory=CHEBI+NAME';
	
	const REACTOME_BASE = 'http://www.reactome.org/AnalysisService/';
	const REACTOME_METHOD_GETIDENTIFIERID = 'identifier/';
	const REACTOME_METHOD_GETPROJECTION = '/projection';
	const REACTOME_PAGES = '?pageSize=20&page=1&';
	const REACTOME_SORT = 'sortBy=ENTITIES_PVALUE&';
	const REACTOME_ORDER = 'order=ASC&';
	const REACTOME_RESOURCE = 'resource=TOTAL';
	
	public function ncbi_efetch_papers($paper_ids){
		// example
// 		http://eutils.ncbi.nlm.nih.gov/entrez/eutils/efetch.fcgi?db=pubmed&id=11748933,11700088&retmode=xml
// 		base, method, DB, ids, retmode
		// EFETCH doesn't allow JSON
		$payload = 'id='.$paper_ids;
		$url = API_model::NCBI_BASE . API_model::NCBI_METHOD_EFETCH . API_model::NCBI_DB_PUBMED . $payload . API_model::NCBI_RETMODE_XML;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
		curl_setopt($curl, CURLOPT_URL, $url);
// 		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
		// Send request.
		$result = curl_exec($curl);
		curl_close($curl);

//		echo "<br>"."EFETCH"."<br>".$result . "<br>";
 		return $result;
			
	}
	public function ncbi_esearch_papers($query_terms){
	    // example
	    // 		http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?db=pubmed&term=carbon+dioxide&retmode=xml
	    // 		base, method, DB, ids, retmode
	
	    $payload = 'term='.$query_terms;
	    $url = API_model::NCBI_BASE . API_model::NCBI_METHOD_ESEARCH . API_model::NCBI_DB_PUBMED . $payload . API_model::NCBI_RETMODE_JSON;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
	    # Send request.
	    $result = curl_exec($curl);
	    curl_close($curl);
	
	    return $result;
	    	
	}
	
	public function ncbi_esummary_papers($p_ids){
	    // example
	    // 		http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi?db=pubmed&id=11748933&retmode=xml
	    // 		base, method, DB, ids, retmode
	    // remove white space
	    $payload = 'id='.preg_replace('/\s+/', '', $p_ids);
	    $url = API_model::NCBI_BASE . API_model::NCBI_METHOD_ESUMMARY  . API_model::NCBI_DB_PUBMED . $payload . API_model::NCBI_RETMODE_JSON ;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
	    # Send request.
	    $result = curl_exec($curl);
	    curl_close($curl);
	
	    return $result;
	    	
	}
	
	public function ncbi_elink_papers($pa_ids){
	    // example
	    // 		http://eutils.ncbi.nlm.nih.gov/entrez/eutils/elink.fcgi?db=pubmed&id=11748933&retmode=xml
	    // 		base, method, DB, ids, retmode
	    // remove white space
	    $payload = 'id='.preg_replace('/\s+/', '',$pa_ids);
	    $url = API_model::NCBI_BASE . API_model::NCBI_METHOD_ELINK . API_model::NCBI_DB_PUBMED . $payload . API_model::NCBI_RETMODE_XML;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
	    # Send request.
	    $result = curl_exec($curl);
	    curl_close($curl);
	
	    return $result;
	    	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function chebi_getcompleteentity_chebi($chebi_ids){
	    // example
	    // 		http://www.ebi.ac.uk/webservices/chebi/2.0/test/getCompleteEntity?chebiId=CHEBI:15377&searchCategory=CHEBI+NAME
	    // 		base, method, parameter
	    $payload = 'chebiId=CHEBI:'.$chebi_ids;
	    $url = API_model::CHEBI_BASE . API_model::CHEBI_METHOD_GETCOMPLETEENTITY . $payload . API_model::CHEBI_CATEGORY;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
	    # Send request.
	    $result = curl_exec($curl);
	    curl_close($curl);
	
	    return $result;
	    	
	}
	
	public function chebi_getontologyparents_chebi($chebi_ids){
	    // example
	    // 		http://www.ebi.ac.uk/webservices/chebi/2.0/test/getOntologyParents?chebiId=CHEBI:15377&searchCategory=CHEBI+NAME
	    // 		base, method, parameter
	    $payload = 'chebiId=CHEBI:'.$chebi_ids;
	    $url = API_model::CHEBI_BASE . API_model::CHEBI_METHOD_GETONTOLOGYPARENTS . $payload . API_model::CHEBI_CATEGORY;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
	    # Send request.
	    $result = curl_exec($curl);
	    curl_close($curl);
	
	    return $result;
	    	
	}
	
	
	public function chebi_getontologychildren_chebi($chebi_ids){
	    // example
	    // 		http://www.ebi.ac.uk/webservices/chebi/2.0/test/getOntologyChildren?chebiId=CHEBI:15377&searchCategory=CHEBI+NAME
	    // 		base, method, parameter
	    $payload = 'chebiId=CHEBI:'.$chebi_ids;
	    $url = API_model::CHEBI_BASE . API_model::CHEBI_METHOD_GETONTOLOGYCHILDREN . $payload . API_model::CHEBI_CATEGORY;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
	    # Send request.
	    $result = curl_exec($curl);
	    curl_close($curl);
	
	    return $result;
	    	
	}
	
	
	public function chebi_getallontologychildreninpath_chebi($chebi_ids){
	    // example
	    // 		http://www.ebi.ac.uk/webservices/chebi/2.0/test/getOntologyChildrenPath?chebiId=CHEBI:15377&searchCategory=CHEBI+NAME
	    // 		base, method, parameter
	    $payload = 'chebiId=CHEBI:'.$chebi_ids;
	    $url = API_model::CHEBI_BASE . API_model::CHEBI_METHOD_GETALLONTOLOGYCHILDRENINPATH . $payload . API_model::CHEBI_CATEGORY;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
	    # Send request.
	    $result = curl_exec($curl);
	    curl_close($curl);
	
	    return $result;
	    	
	}
	public function reactome_getidentifierid_chebi($chebi_ids){
	    // example
	    // 		http://www.reactome.org/AnalysisService/identifier/17544?pageSize=20&page=1&sortBy=ENTITIES_PVALUE&order=ASC&resource=TOTAL
	    // 		base, method, pages, sort, order, resource
	    $payload = $chebi_ids;
	    $url = API_model::REACTOME_BASE . API_model::REACTOME_METHOD_GETIDENTIFIERID . $payload . API_model::REACTOME_PAGES .
	    API_model::REACTOME_SORT .  API_model::REACTOME_ORDER .  API_model::REACTOME_RESOURCE;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
	    # Send request.
	    $result = curl_exec($curl);
	    curl_close($curl);
	
	    return $result;
	    	
	}
	
	
	public function reactome_getidentifierprojection_chebi($chebi_ids){
	    // example
	    // 		http://www.reactome.org/AnalysisService/identifier/17544/projection?pageSize=20&page=1&sortBy=ENTITIES_PVALUE&order=ASC&resource=TOTAL
	    // 		base, method, pages, sort, order, resource
	    $payload = $chebi_ids;
	    $url = API_model::REACTOME_BASE . API_model::REACTOME_METHOD_GETIDENTIFIERID . $payload . API_model::REACTOME_METHOD_GETPROJECTION . API_model::REACTOME_PAGES . API_model::REACTOME_SORT .  API_model::REACTOME_ORDER .  API_model::REACTOME_RESOURCE;
	
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE); //Return the response as a string instead of outputting it to the screen
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:text/plain'));
	    # Send request.
	    $result = curl_exec($curl);
	    curl_close($curl);
	
	    return $result;
	    	
	}
	

}

?>