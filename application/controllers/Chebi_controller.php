<?php
defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Chebi_controller extends REST_Controller {
	function __construct() {
		parent::__construct ();
	}	
    
    	private function pull_data(){
        // retrives text from textbox with name="text"
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

	private function getArray($array){

		if(!isset($array)){
			return null;
		}
		if(array_key_exists(0,$array)){
			return $array;
		}
		else{
			return array($array);
		}

	}

	public function compounds_get(){

        $this->load->model('Chemical_model');
        $this->load->model('API_model');
        $this->load->library('utilities');

		$chebi_id = $this->get('id');
//        echo "chbi id in compounds_get: "; print_r($chebi_id); echo "\n";
        
        if(!is_numeric($chebi_id)){
//            $chem_name = $chebi_id;
//            $chebi_id = $this->Chemical_model->getChemicalByName($chem_name);
//            echo "compounds_get: not numeric \n";
            $this->send_reply(null, "ok", "Please enter a valid Chebi ID");
            die();
        }

        $ChemProperties = $this->Chemical_model->getChemical($chebi_id);
        if(count($ChemProperties) < 1){
             // get from Chebi
            $compound = $this->API_model->chebi_getcompleteentity_chebi($chebi_id);
            $ChemFromChebi = $this->utilities->chebiToDB($compound);
//            echo "FROM CHEBI: " . print_r($ChemFromChebi) . "\n";
            // can be array or not:
            // Chemical structures,
            // IupacNames
            // Synonyms
            // RegistryNumbers

            // save to DB
//            print("going to insert DB"); echo "\n";
            $inserted = $this->Chemical_model->post_ChemicalDB($ChemFromChebi);

            //get from DB
            $ChemProperties = $this->Chemical_model->getChemical($chebi_id);
//            echo "controller compounds_get, Chem from DB: "; print_r($ChemProperties); echo "\n";
//            $chemName = $ChemProperties[0]->chebiname;
            $chemName = $ChemProperties->chebiname;

            $chemName = ucwords($chemName);

            $url = $this->utilities->getUrlDbpedia($chemName);
            $data = $this->cheminfo($url);

//            echo "cheminfo dbpedia: \n";
//            print_r($data);

            if(isset($data)){
                $chemInfo = $this->utilities->chemInfo($data);
                $this-> Chemical_model -> updateDbpedia($chebi_id, $chemInfo);
                $ChemProperties = $this->Chemical_model->getChemical($chebi_id); // get chemical with Dbpedia info
            }
        }
        $this->send_reply($ChemProperties, "ok", "Chemical not found in Chebi");
//        return $ChemProperties;

	}

    // TODO: DELETE, this is bdpedia
/*    public function compounds_main()
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
    
    }*/
    /*
     * WebService
     */
      public function cheminfo_get(){

          $this->load->library('utilities');
        $term = $this->get('name');
        $url = $this->utilities->getUrlDbpedia($term);
        $this->load->model('Dbpedia_model');
        $result =  json_decode($this->Dbpedia_model->request($url));
        $this->send_reply($result, "", "request failed");
//        return $result;
    }
    /*
     * same as cheminfo_get but for controller usage
     */
    private function cheminfo($url){
        $this->load->model('Dbpedia_model');
        $result =  json_decode($this->Dbpedia_model->request($url));
        return $result;
    }




//    private function chebiGetEntity($chebi_ids){
//        // can be array or not:
//        // Chemical structures,
//        // IupacNames
//        // Synonyms
//        // RegistryNumbers
//
//
//        $this->load->model('API_model');
//        $compound = $this->API_model->chebi_getcompleteentity_chebi($chebi_ids);
//
////        print("chebiGetEntity");
////        print_r($compound);
//
//        if(is_null($compound) || count($compound)< 1){
//            return null;
//        }
//
//        $xml = simplexml_load_string($compound);
//
//        $p = xml_parser_create();
//        xml_parser_free($p);
//
//        $arrayData = $this->xmlToArray($xml);
//
//        $return = array(
//            "ChebiID"=>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['chebiId'])?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['chebiId']:
//                    null,
//            "Chebiascii"=>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['chebiAsciiName']) ?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['chebiAsciiName'] :
//                    null,
//            "definition"=>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['definition'])?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['definition'] :
//                    null,
//            "Formulae" =>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['Formulae'])?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['Formulae'] :
//                    null,// array
//            "charge" =>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['charge'])?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['charge'] :
//                    null,
//            "ChemicalStructures" =>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['ChemicalStructures'])?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['ChemicalStructures'] :
//                    null,// array
//            "IupacNames" =>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['IupacNames'])?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['IupacNames'] :
//                    null,// array
//            "inchi" =>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['inchi'])?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['inchi'] :
//                    null,
//            "inchiKey" =>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['inchiKey'])?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['inchiKey'] :
//                    null,
//            "Synonyms" =>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['Synonyms'])?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['Synonyms']:
//                    null,// array
//            "RegistryNumbers" =>
//                isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['RegistryNumbers']) ?
//                    $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['RegistryNumbers'] :
//                    null // array
//        );
//        return json_encode($return);
//
//    }



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




//    /**
//	 * Create plain PHP associative array from XML.
//	 *
//	 * Example usage:
//	 *   $xmlNode = simplexml_load_file('example.xml');
//	 *   $arrayData = xmlToArray($xmlNode);
//	 *   echo json_encode($arrayData);
//	 *
//	 * @param SimpleXMLElement $xml The root node
//	 * @param array $options Associative array of options
//	 * @return array
//	 * @link http://outlandishideas.co.uk/blog/2012/08/xml-to-json/ More info
//	 * @author Tamlyn Rhodes <http://tamlyn.org>
//	 * @license http://creativecommons.org/publicdomain/mark/1.0/ Public Domain
//	 */
//	private function xmlToArray($xml, $options = array()) {
//		$defaults = array(
//			'namespaceSeparator' => ':',//you may want this to be something other than a colon
//			'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
//			'alwaysArray' => array(),   //array of xml tag names which should always become arrays
//			'autoArray' => true,        //only create arrays for tags which appear more than once
//			'textContent' => '$',       //key used for the text content of elements
//			'autoText' => true,         //skip textContent key if node has no attributes or child nodes
//			'keySearch' => false,       //optional search and replace on tag and attribute names
//			'keyReplace' => false       //replace values for above search values (as passed to str_replace())
//		);
//		$options = array_merge($defaults, $options);
//		$namespaces = $xml->getDocNamespaces();
//		$namespaces[''] = null; //add base (empty) namespace
//
//		//get attributes from all namespaces
//		$attributesArray = array();
//		foreach ($namespaces as $prefix => $namespace) {
//			foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
//				//replace characters in attribute name
//				if ($options['keySearch']) $attributeName =
//					str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
//				$attributeKey = $options['attributePrefix']
//					. ($prefix ? $prefix . $options['namespaceSeparator'] : '')
//					. $attributeName;
//				$attributesArray[$attributeKey] = (string)$attribute;
//			}
//		}
//
//		//get child nodes from all namespaces
//		$tagsArray = array();
//		foreach ($namespaces as $prefix => $namespace) {
//			foreach ($xml->children($namespace) as $childXml) {
//				//recurse into child nodes
//				$childArray = $this->xmlToArray($childXml, $options);
//				list($childTagName, $childProperties) = each($childArray);
//
//				//replace characters in tag name
//				if ($options['keySearch']) $childTagName =
//					str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
//				//add namespace prefix, if any
//				if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
//
//				if (!isset($tagsArray[$childTagName])) {
//					//only entry with this key
//					//test if tags of this type should always be arrays, no matter the element count
//					$tagsArray[$childTagName] =
//						in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
//							? array($childProperties) : $childProperties;
//				} elseif (
//					is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
//					=== range(0, count($tagsArray[$childTagName]) - 1)
//				) {
//					//key already exists and is integer indexed array
//					$tagsArray[$childTagName][] = $childProperties;
//				} else {
//					//key exists so convert to integer indexed array with previous value in position 0
//					$tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
//				}
//			}
//		}
//
//		//get text content of node
//		$textContentArray = array();
//		$plainText = trim((string)$xml);
//		if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
//
//		//stick it all together
//		$propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
//			? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
//
//		//return node as array
//		return array(
//			$xml->getName() => $propertiesArray
//		);
//	}



}