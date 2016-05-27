<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Utilities{

    public function chebiToDB($compound){

//        print("chebiGetEntity");
//        print_r($compound);

        if(is_null($compound) || count($compound)< 1){
            return null;
        }

        $xml = simplexml_load_string($compound);

        $p = xml_parser_create();
        xml_parser_free($p);

        $arrayData = $this->xmlToArray($xml);

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
        return json_encode($return);
    }

    public function getUrlDbpedia($term)
    {
//        Cefatrizine 131730

        /* Dá todos os que pertencem a Chemical114806838
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX type: <http://dbpedia.org/class/yago/>
PREFIX prop: <http://dbpedia.org/property/>
PREFIX res: <http://dbpedia.org/resource/>
SELECT ?a
WHERE { ?a a type:Chemical114806838 }
        */

        $format = 'json';
        $query = "PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
            PREFIX type: <http://dbpedia.org/class/yago/>
            PREFIX prop: <http://dbpedia.org/property/>
            PREFIX res: <http://dbpedia.org/resource/>
            SELECT ?smiles ?molecularWeight ?pubchem 
            WHERE {
                    res:" . $term . " a type:Chemical114806838 ;
                    prop:smiles ?smiles ;
                    prop:molecularWeight ?molecularWeight ;
                    prop:pubchem ?pubchem.
                }";
        $searchUrl = 'http://dbpedia.org/sparql?'
            . 'query=' . urlencode($query)
            . '&format=' . $format;

        return $searchUrl;
    }

    public function chemInfo($data){
        $smiles = isset($data->results->bindings[0]->smiles->value) ?
            $data->results->bindings[0]->smiles->value :
            null;
        $molecularWeight = isset($data->results->bindings[0]->molecularWeight-> value) ?
            $data->results->bindings[0]->molecularWeight-> value :
            null;

        $pubchem = isset($data->results->bindings[0]->pubchem-> value) ?
            $data->results->bindings[0]->pubchem-> value :
            null;

        $data = array(
            'smiles' => $smiles,
            'pubchem' => $pubchem,
            'molecularWeight' => $molecularWeight
        );
        return $data;
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
    private function xmlToArray($xml, $options = array()) {
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