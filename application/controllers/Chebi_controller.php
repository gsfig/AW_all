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

		$this->load->model('API_model');
		$chebi_ids = $this->get('id');
		$compound = $this->API_model->chebi_getcompleteentity_chebi($chebi_ids);
//		echo '<pre>', htmlentities($compound), '</pre>';

		/*echo '<br>'; echo "vardump getentity"; echo '<br>';
		var_dump($compound);*/
//		echo $compound;
//		print_r($compound);


		/*echo '<br>'; echo "vardump simplexml"; echo '<br>';
		print $xml->asXML();*/

//		$array = $this->xml2array($compound, 0, 'tag');
//		print_r($result);







//		var_dump($xml);
		$xml = simplexml_load_string($compound);
//		/*echo '<br>'; echo "simple xml"; echo '<br>';
//		print_r($xml);*/

		$p = xml_parser_create();
//		xml_parse_into_struct($p, $compound, $vals);
		xml_parser_free($p);
//		echo '<br>'; echo "VALS"; echo '<br>';
//		print_r($vals);
//		echo '<br>'; echo "index"; echo '<br>';
//		print_r($index);


		$arrayData = $this->xmlToArray($xml);
//		echo json_encode($arrayData);
//		print_r($arrayData);
//		echo $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['charge'];
//		print_r($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['RegistryNumbers']);

//		foreach ($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['ChemicalStructures'] as $a){
//			print_r($a['structure']);echo '<br>';
//			print_r($a['type']);echo '<br>';
//			print_r($a['dimension']);echo '<br>';
//			print_r($a['defaultStructure']);echo '<br>';
//		}
		$path = array('Envelope', 'S:Body', 'getCompleteEntityResponse','return');
		$Envelope = 'Envelope';
		$Body = 'S:Body';
		$entity = 'getCompleteEntityResponse';
		$r = 'return';
		$chebiid = 'chebiId';
		$acsii = 'chebiAsciiName';
		$def = 'definition';
		$Formulae = 'Formulae';
		$charge = 'charge';
		$struc = 'ChemicalStructures';
		$iupac = 'IupacNames';
		$inchi = 'inchi';
		$inchiKey = 'inchiKey';
		$Synonyms = 'Synonyms';
		$reg = 'RegistryNumbers';

		$isarry = $arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['ChemicalStructures'];
//		echo '<br>'; echo "is array???"; echo '<br>';

//		echo '<br>'; echo "cont"; echo '<br>';
//		echo count($isarry);
//		echo '<br>'; echo "is array [0]"; echo '<br>';
//		echo is_array($isarry[0]);
//		echo '<br>'; echo "get Array"; echo '<br>';
//		echo '<pre>', print_r($this->getArray($isarry)), '</pre>';

//		echo '<br>';
//		echo '<pre>', print_r($isarry), '</pre>';

//		echo '<br>'; echo "is array???"; echo '<br>';
//		return is_array($isarry);


		$return = array(
			"ChebiID"=>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['chebiId'])?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['chebiId']:
				null,
			"Chebiascii"=>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['chebiAsciiName']) ?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['chebiAsciiName'] :
				null,
			"definition"=>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['definition'])?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['definition'] :
				null,
			"Formulae" =>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['Formulae'])?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['Formulae'] :
				null,// array
			"charge" =>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['charge'])?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['charge'] :
				null,
			"ChemicalStructures" =>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['ChemicalStructures'])?
					$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['ChemicalStructures'] :
					null,// array
			"IupacNames" =>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['IupacNames'])?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['IupacNames'] :
				null,// array
			"inchi" =>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['inchi'])?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['inchi'] :
				null,
			"inchiKey" =>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['inchiKey'])?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['inchiKey'] :
				null,
			"Synonyms" =>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['Synonyms'])?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['Synonyms']:
				null,// array
			"RegistryNumbers" =>
				isset($arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['RegistryNumbers']) ?
				$arrayData['Envelope']['S:Body']['getCompleteEntityResponse']['return']['RegistryNumbers'] :
				null // array
		);
		echo json_encode($return);



//		echo "<script src='../../js/xml2json.js'></script>  <script>   new X2JS().xml2json('$compound');  </script>"

//		$variable = $_POST['variable'];
//		echo '<br>'; echo "Variable"; echo '<br>';
//		print_r($variable);

/*
		foreach ($index as $key=>$val) {
			if ($key == "molecule") {
				$molranges = $val;
				// each contiguous pair of array entries are the
				// lower and upper range for each molecule definition
				for ($i=0; $i < count($molranges); $i+=2) {
					$offset = $molranges[$i] + 1;
					$len = $molranges[$i + 1] - $offset;
					$tdb[] = parseMol(array_slice($values, $offset, $len));
				}
			} else {
				continue;
			}
		}
		return $tdb;
	}

	function parseMol($mvalues)
	{
		for ($i=0; $i < count($mvalues); $i++) {
			$mol[$mvalues[$i]["tag"]] = $mvalues[$i]["value"];
		}
		return new AminoAcid($mol);
	}*/










/*		$keys = array(
			"chebi"=>"CHEBIID",
			"chebiascii"=>"CHEBIASCIINAME",
			"definition"=>"DEFINITION",
			"formulae" => "FORMULAE",// array
			"charge" => "CHARGE",
			"chemStruct" => "CHEMICALSTRUCTURES", // array
			"iupac" => "IUPACNAMES", // array
			"inchi" => "INCHI",
			"inchikey" => "INCHIKEY",
			"synonyms" => "SYNONYMS",// array
			"registrynumbers" => "REGISTRYNUMBERS"// array
			);
		$values = array(
			"chebi"=>"value",
			"chebiascii"=>"value",
			"definition"=>"value",
			"formulae" => "FORMULAE",// array
			"charge" => "CHARGE",
			"chemStruct" => "CHEMICALSTRUCTURES", // array
			"iupac" => "IUPACNAMES", // array
			"inchi" => "INCHI",
			"inchikey" => "INCHIKEY",
			"synonyms" => "SYNONYMS",// array
			"registrynumbers" => "REGISTRYNUMBERS"// array
		);*/



//		echo '<br>'; echo "Formulae"; echo '<br>';
//		$index = $index['FORMULAE']['0'];
//		echo 'index: '. $index;echo '<br>';
//		$n = ++$index;
//		$value = $vals[$n]['value'];
//		echo 'value: '. $value; echo '<br>';

//		echo '<br>'; echo "chemStruct"; echo '<br>';
//		$index = $index[$keys['chemStruct']]['0'];
//		echo 'index: '. $index;echo '<br>';
//		$n = ++$index;
//		$value = $vals[$n]['value'];
//		echo 'value: '. $value; echo '<br>';








//		$result = $this->xml2array($compound, 0, 'tag');
//		$result2 = $result['S:Envelope']['S:Body']['getCompleteEntityResponse']['return'];

//		$result = $this->xmlstr_to_array($compound);
//		print_r($result);
//		$chebiId = $result2['chebiId'];
//		$chebiAsciiName = $result2['chebiAsciiName'];
//		$definition = $result2['definition'];



//		echo '<br>'; echo "ALLL"; echo '<br>';


		/*echo "Index array\n";
		print_r($index);
		echo '<br>'; echo "vals"; echo '<br>';
		echo "\nVals array\n";
		print_r($vals);*/





		/*echo '<br>'; echo "Children"; echo '<br>';
		foreach ($xml->children() as $child)
		{
			echo "Child node: " . var_dump($child) . "<br>";
		}*/




		/*$doc = new DOMDocument();
		$doc->loadXML($compound);
		$dom_article = $doc->documentElement;
		$entities = $dom_article->getElementsByTagName("*");

		foreach($entities as $e){ // get the image tags

			if ($e->hasAttributes()) {
				echo '<br>';echo '<br>';echo "Has atribute";echo '<br>';
				var_dump($e);
				for ($i = $e->attributes->length - 1; $i >= 0; --$i)
					$e->removeAttributeNode($e->attributes->item($i));
			}
		}
		echo '<br>'; echo "DOMDocument"; echo '<br>';
		echo '<pre>', htmlentities($doc->saveXML()), '</pre>';*/
		
		
		
		

//		echo '<br>'; echo "namespace"; echo '<br>';
//		$namespaces = $xml->getDocNamespaces();
//		var_dump($namespaces);


//		echo '<br>'; echo "prefix and namespace"; echo '<br>';
		/*echo $namespaces["S"];echo '<br>';
		print_r(array_keys($namespaces));echo '<br>';
		echo array_keys($namespaces)[0];*/

//		$prefix = array_keys($namespaces)[0] ;
//		$namespace = $namespaces[$prefix];
//		echo $prefix;echo '<br>';
//		echo $namespace;

		/*$trimed = str_replace($prefix . ':', "",$compound );
		$trimed = str_replace($namespace, "",$trimed );*/

		// Remove the XML namespace opening tags
//		$string = str_replace('<S:', '<', $compound);
		// Remove the XML namespace closing tags
//		$string = str_replace('</S:', '</', $string);
		// For good measure, remove anything that has to do with XML namespace
//		$string = str_replace('xmlns:S', 'nonsense', $string);
//		$string = str_replace('xmlns=', '', $string);
//		$string = str_replace($namespace, '', $string);
//		echo '<br>'; echo "replace1"; echo '<br>';
//		echo '<pre>', htmlentities($string), '</pre>';
		// Load into simplexml_load_string()
//		$xml = simplexml_load_string($string);
		// Echoes Success
//		echo $xml->Response[0]->ResponseStatus[0]->StatusCode;
//		echo '<br>'; echo "replace"; echo '<br>';
//		echo '<pre>', htmlentities($string), '</pre>';
//		var_dump($xml);
//		echo '<pre>', htmlentities($xml), '</pre>';
//		echo (string) $xml->Envelope[0]->Body[0]->getCompleteEntityResponse;

//		foreach( $xml as $a){
//			echo $a;
//		}



//		echo '<br>'; echo "xpath"; echo '<br>';
//		$xml->registerXPathNamespace($prefix, $namespace);
//		$result = $xml->xpath('///');
//		print_r($result);




//		echo '<br>'; echo "trimmed"; echo '<br>';
//
//		echo '<pre>', htmlentities($trimed), '</pre>';







//		$sxe->registerXPathNamespace('c','http://example.org/chapter-title');


//		$pmid = (string) $xml->S:envelope->MedlineCitation->PMID;
		/*echo '<br>'; echo "simpleXMLElement"; echo '<br>';
		$xmlelm = new SimpleXMLElement($xml);
		foreach($xmlelm->children() as $child) {
			echo $child->getName();
		}*/


		/*$n = $namespaces[0];
		echo $n;*/

//		str_replace()

//		echo '<br>'; echo "simple xml after remove"; echo '<br>';




		/*$json = json_encode($xml);


		echo '<br>'; echo "json encode"; echo '<br>';
		var_dump($json);
		$array = json_decode($json,TRUE);


		echo '<br>'; echo "json decode" ; echo '<br>';
		print_r($array);*/




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
	/**
	 * xml2array() will convert the given XML text to an array in the XML structure.
	 * Link: http://www.bin-co.com/php/scripts/xml2array/
	 * Arguments : $contents - The XML text
	 *                $get_attributes - 1 or 0. If this is 1 the function will get the attributes as well as the tag values - this results in a different array structure in the return value.
	 *                $priority - Can be 'tag' or 'attribute'. This will change the way the resulting array sturcture. For 'tag', the tags are given more importance.
	 * Return: The parsed XML in an array form. Use print_r() to see the resulting array structure.
	 * Examples: $array =  xml2array(file_get_contents('feed.xml'));
	 *              $array =  xml2array(file_get_contents('feed.xml', 1, 'attribute'));
	 */
	function xml2array($contents, $get_attributes=0, $priority = 'tag') {
		if(!$contents) return array();

		if(!function_exists('xml_parser_create')) {
			//print "'xml_parser_create()' function not found!";
			return array();
		}

		//Get the XML parser of PHP - PHP must have this module for the parser to work
		$parser = xml_parser_create('');
		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);

		if(!$xml_values) return;//Hmm...

		//Initializations
		$xml_array = array();
		$parents = array();
		$opened_tags = array();
		$arr = array();

		$current = &$xml_array; //Refference

		//Go through the tags.
		$repeated_tag_index = array();//Multiple tags with same name will be turned into an array
		foreach($xml_values as $data) {
			unset($attributes,$value);//Remove existing values, or there will be trouble

			//This command will extract these variables into the foreach scope
			// tag(string), type(string), level(int), attributes(array).
			extract($data);//We could use the array by itself, but this cooler.

			$result = array();
			$attributes_data = array();

			if(isset($value)) {
				if($priority == 'tag') $result = $value;
				else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
			}

			//Set the attributes too.
			if(isset($attributes) and $get_attributes) {
				foreach($attributes as $attr => $val) {
					if($priority == 'tag') $attributes_data[$attr] = $val;
					else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				}
			}

			//See tag status and do the needed.
			if($type == "open") {//The starting of the tag '<tag>'
				$parent[$level-1] = &$current;
				if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
					$current[$tag] = $result;
					if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
					$repeated_tag_index[$tag.'_'.$level] = 1;

					$current = &$current[$tag];

				} else { //There was another element with the same tag name

					if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
						$repeated_tag_index[$tag.'_'.$level]++;
					} else {//This section will make the value an array if multiple tags with the same name appear together
						$current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
						$repeated_tag_index[$tag.'_'.$level] = 2;

						if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
							$current[$tag]['0_attr'] = $current[$tag.'_attr'];
							unset($current[$tag.'_attr']);
						}

					}
					$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
					$current = &$current[$tag][$last_item_index];
				}

			} elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
				//See if the key is already taken.
				if(!isset($current[$tag])) { //New Key
					$current[$tag] = $result;
					$repeated_tag_index[$tag.'_'.$level] = 1;
					if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

				} else { //If taken, put all things inside a list(array)
					if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

						// ...push the new element into that array.
						$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

						if($priority == 'tag' and $get_attributes and $attributes_data) {
							$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag.'_'.$level]++;

					} else { //If it is not an array...
						$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
						$repeated_tag_index[$tag.'_'.$level] = 1;
						if($priority == 'tag' and $get_attributes) {
							if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

								$current[$tag]['0_attr'] = $current[$tag.'_attr'];
								unset($current[$tag.'_attr']);
							}

							if($attributes_data) {
								$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
					}
				}

			} elseif($type == 'close') { //End of tag '</tag>'
				$current = &$parent[$level-1];
			}
		}

		return($xml_array);
	}













	/**
	 * convert xml string to php array - useful to get a serializable value
	 *
	 * @param string $xmlstr
	 * @return array
	 *
	 * @author Adrien aka Gaarf & contributors
	 * @see http://gaarf.info/2009/08/13/xml-string-to-php-array/
	 */
	function xmlstr_to_array($xmlstr) {
		$doc = new DOMDocument();
		$doc->loadXML($xmlstr);
		$root = $doc->documentElement;
		$output = $this->domnode_to_array($root);
		$output['@root'] = $root->tagName;
		return $output;
	}
	function domnode_to_array($node) {
		$output = array();
		switch ($node->nodeType) {
			case XML_CDATA_SECTION_NODE:
			case XML_TEXT_NODE:
				$output = trim($node->textContent);
				break;
			case XML_ELEMENT_NODE:
				for ($i=0, $m=$node->childNodes->length; $i<$m; $i++) {
					$child = $node->childNodes->item($i);
					$v = $this->domnode_to_array($child);
					if(isset($child->tagName)) {
						$t = $child->tagName;
						if(!isset($output[$t])) {
							$output[$t] = array();
						}
						$output[$t][] = $v;
					}
					elseif($v || $v === '0') {
						$output = (string) $v;
					}
				}
				if($node->attributes->length && !is_array($output)) { //Has attributes but isn't an array
					$output = array('@content'=>$output); //Change output into an array.
				}
				if(is_array($output)) {
					if($node->attributes->length) {
						$a = array();
						foreach($node->attributes as $attrName => $attrNode) {
							$a[$attrName] = (string) $attrNode->value;
						}
						$output['@attributes'] = $a;
					}
					foreach ($output as $t => $v) {
						if(is_array($v) && count($v)==1 && $t!='@attributes') {
							$output[$t] = $v[0];
						}
					}
				}
				break;
		}
		return $output;
	}











	/**
	 * Create plain PHP associative array from XML.
	 *
	 * Example usage:
	 *   $xmlNode = simplexml_load_file('example.xml');
	 *   $arrayData = xmlToArray($xmlNode);
	 *   echo json_encode($arrayData);
	 *
	 * @param SimpleXMLElement $xml The root node
	 * @param array $options Associative array of options
	 * @return array
	 * @link http://outlandishideas.co.uk/blog/2012/08/xml-to-json/ More info
	 * @author Tamlyn Rhodes <http://tamlyn.org>
	 * @license http://creativecommons.org/publicdomain/mark/1.0/ Public Domain
	 */
	function xmlToArray($xml, $options = array()) {
		$defaults = array(
			'namespaceSeparator' => ':',//you may want this to be something other than a colon
			'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
			'alwaysArray' => array(),   //array of xml tag names which should always become arrays
			'autoArray' => true,        //only create arrays for tags which appear more than once
			'textContent' => '$',       //key used for the text content of elements
			'autoText' => true,         //skip textContent key if node has no attributes or child nodes
			'keySearch' => false,       //optional search and replace on tag and attribute names
			'keyReplace' => false       //replace values for above search values (as passed to str_replace())
		);
		$options = array_merge($defaults, $options);
		$namespaces = $xml->getDocNamespaces();
		$namespaces[''] = null; //add base (empty) namespace

		//get attributes from all namespaces
		$attributesArray = array();
		foreach ($namespaces as $prefix => $namespace) {
			foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
				//replace characters in attribute name
				if ($options['keySearch']) $attributeName =
					str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
				$attributeKey = $options['attributePrefix']
					. ($prefix ? $prefix . $options['namespaceSeparator'] : '')
					. $attributeName;
				$attributesArray[$attributeKey] = (string)$attribute;
			}
		}

		//get child nodes from all namespaces
		$tagsArray = array();
		foreach ($namespaces as $prefix => $namespace) {
			foreach ($xml->children($namespace) as $childXml) {
				//recurse into child nodes
				$childArray = $this->xmlToArray($childXml, $options);
				list($childTagName, $childProperties) = each($childArray);

				//replace characters in tag name
				if ($options['keySearch']) $childTagName =
					str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
				//add namespace prefix, if any
				if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;

				if (!isset($tagsArray[$childTagName])) {
					//only entry with this key
					//test if tags of this type should always be arrays, no matter the element count
					$tagsArray[$childTagName] =
						in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
							? array($childProperties) : $childProperties;
				} elseif (
					is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
					=== range(0, count($tagsArray[$childTagName]) - 1)
				) {
					//key already exists and is integer indexed array
					$tagsArray[$childTagName][] = $childProperties;
				} else {
					//key exists so convert to integer indexed array with previous value in position 0
					$tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
				}
			}
		}

		//get text content of node
		$textContentArray = array();
		$plainText = trim((string)$xml);
		if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;

		//stick it all together
		$propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
			? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;

		//return node as array
		return array(
			$xml->getName() => $propertiesArray
		);
	}



}