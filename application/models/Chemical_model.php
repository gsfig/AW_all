<?php

class Chemical_model extends CI_Model
{

    public function post_chemical_compound($ChemProperties){
        // can be array or not:
        // Chemical structures,
        // iupacNames
        // synonyms
        // registryNumbers


        
        

    }
    public function getChemicalByName($name){
        $query = $this->db->get_where('chemicalcompound', array('chebiname' => $name));
        $array = $query->result();

        if(empty($array)){
            $query = $this->db->get_where('synonym', array('name' => $name));
            $synonyms = $query->result();
        }
        if(empty($synonyms)){
            
        }
    }


    public function getChemical($chebi_id){

        $query = $this->db->get_where('chemicalcompound', array('chebiid' => $chebi_id));
        $array = $query->result();

//        print_r($array[0]);
        if(count($array) < 1){
            return $array;
        }

        $chemid = $array[0]->idchemicalcompound;
        $query = $this->db->get_where('iupac', array('fkchemcomp' => $chemid));
        $iupac = $query->result();

        if(!empty($iupac)){
            $array[0]->iupacName = $iupac[0]->name;
            $array[0]->iupacType = $iupac[0]->iupacType;
        }

        $query = $this->db->get_where('synonym', array('fkchemcomp' => $chemid));
        $synonyms = $query->result();

        if(!empty($synonyms)){
            $synonymArray = array();
            foreach ($synonyms as $s){
                $newSyn = array(
                    'name' => $s->name,
                    'source' => $s->source
                );
                array_push($synonymArray, $newSyn );
            }
            $array[0]->synonyms = $synonymArray;
        }

        $query = $this->db->get_where('registry', array('fkchemcomp' => $chemid));
        $registry = $query->result();

        if(!empty($registry)){
            $registryArray = array();
            foreach ($registry as $r){
                $newRes = array(
                    'type' => $r->type,
                    'nregistry' => $r->nregistry ,
                    'source' => $r->source
                );
                array_push($registryArray, $newRes );
            }
            $array[0]->registry = $registryArray;
        }
        return $array[0];

    }

    public function post_ChemicalDB($ChemProperties){

//        echo "post_ChemicalDB, chem from chebi: "; print_r($ChemProperties); echo "\n";

//        echo "chem model post chemDB \n";

        $chemProp = json_decode($ChemProperties);
//        $chemProp = $ChemProperties;
//        print_r($chemProp);


//        print_r($chemProp);

//        print_r($chemProp->ChemicalStructures);
//        die();
//        echo "chem struct: \n";
//        print_r($chemProp->ChemicalStructures);

        $twoD = null;
        $twoDType = null;
        $threeD = null;
        $threeDType = null;

        if(is_array($chemProp->ChemicalStructures)){
            foreach ($chemProp->ChemicalStructures as $struct){
                if($struct->defaultStructure){
                    if($struct->dimension == '2D'){
                        $twoD = $struct->structure;
                        $twoDType = $struct->type;
                    }
                    if($struct->dimension == '3D'){
                        $threeD = $struct->structure;
                        $threeDType = $struct->type;
                    }
                }
                if(is_null($threeD) and $struct->dimension == '3D'){ // if 3D struct isnt default
                    $threeD = $struct->structure;
                    $threeDType = $struct->type;
                }
            }
        }
        else if(!is_null($chemProp->ChemicalStructures)){  // only one Chem structure
            if($chemProp->ChemicalStructures->dimension == '2D'){
                $twoD = $chemProp->ChemicalStructures->structure;
                $twoDType = $chemProp->ChemicalStructures->type;
            }
            if($chemProp->ChemicalStructures->dimension == '3D'){
                $threeD = $chemProp->ChemicalStructures->structure;
                $threeDType = $chemProp->ChemicalStructures->type;
            }

        }
        if(is_null($chemProp->Formulae)){
            $formula_source = null;
            $formula_data = null;
        }
        else{
            $formula_source = isset($chemProp->Formulae->source) ?
                $chemProp->Formulae->source :
                    null;
            $formula_data = isset($chemProp->Formulae->data) ?
                $chemProp->Formulae->data :
                    null;

            if(is_null($formula_source)){

                $formula_source = isset($chemProp->Formulae[0]->source) ?
                    $chemProp->Formulae[0]->source :
                    null;
            }
            if(is_null($formula_data)){

                $formula_data = isset($chemProp->Formulae[0]->data) ?
                    $chemProp->Formulae[0]->data :
                    null;
            }

        }
        $chebiid = (int)str_replace('CHEBI:', '', $chemProp->ChebiID);
        $data = array(
            // dava com ['text'] em vez de ->text
            'chebiid' => $chebiid,
            'chebiname' => $chemProp->Chebiascii,
            'smiles' => null,
            'inchi' => $chemProp->inchi,
            'inchikey' => $chemProp->inchiKey,
            'formula_source' => $formula_source,
            'formula_data' => $formula_data,
            'definition' => is_string($chemProp->definition) ?
                $chemProp->definition :
                null,
            'boilingpoint' => null,
            'flashpoint' => null,
            'molecularWeight' => null,
            'netcharge' =>$chemProp->charge,
            'twoD' =>$twoD,
            'twoD_type' => $twoDType,
            'threeD' =>$threeD,
            'threeD_type' => $threeDType
        );

//        echo "CHEMICAL MODEL ISERT DATA: \n";
//        print_r($data);
//        echo "\n";

        $this->db->insert('chemicalcompound', $data);
        $rows = $this->db->affected_rows();
        if($rows > 0){
            $this->db->select('idchemicalcompound');
            $query = $this->db->get_where('chemicalcompound', array('chebiid' => $chebiid));
            $result = $query->result();
            $fkChem = (int)$result[0]->idchemicalcompound;

            // After insert chemical, iupac
            if(is_null($chemProp->iupacNames)){} // dont insert

            else if(is_array($chemProp->iupacNames)){
                foreach ($chemProp->iupacNames as $iupac){
                    $data = array(
                        'name' => $iupac->data,
                        'fkchemcomp' => $fkChem,
                        'iupacType' => $iupac->type
                    );
                    $this->db->insert('iupac', $data);
                }

            }
            else if(!is_null($chemProp->iupacNames)) {  // only one iupac
                $data = array(
                    'name' => $chemProp->iupacNames->data,
                    'fkchemcomp' => $fkChem,
                    'iupacType' => $chemProp->iupacNames->type
                );
                $this->db->insert('iupac', $data);
            }

            // after insert chemical, synonyms
            if(is_null($chemProp->synonyms)){} // dont insert

            else if(is_array($chemProp->synonyms)){
                foreach ($chemProp->synonyms as $synonym){
                    $data = array(
                        'name' => $synonym->data,
                        'fkchemcomp' => $fkChem,
                        'source' => $synonym->source
                    );
                    $this->db->insert('synonym', $data);
                }
            }
            else if(!is_null($chemProp->synonyms)) {  // only one synonym
                $data = array(
                    'name' => $chemProp->synonyms->data,
                    'fkchemcomp' => $fkChem,
                    'source' => $chemProp->synonyms->source
                );
                $this->db->insert('synonym', $data);
            }

            // after insert chemical, registry numbers
            if(is_null($chemProp->registryNumbers)){} // dont insert

            else if(is_array($chemProp->registryNumbers)){
                foreach ($chemProp->registryNumbers as $registry){
                    $data = array(
                        'nregistry' => (int)$registry->data,
                        'fkchemcomp' => $fkChem,
                        'type' => $registry->type,
                        'source' => $registry->source
                    );
                    $this->db->insert('registry', $data);
                }
            }
            else if(!is_null($chemProp->registryNumbers)) {  // only one synonym
                $data = array(
                    'nregistry' => (int)$chemProp->registryNumbers->data,
                    'fkchemcomp' => $fkChem,
                    'type' => $chemProp->registryNumbers->type,
                    'source' => $chemProp->registryNumbers->source
                );
                $this->db->insert('registry', $data);
            }

        }
        return(true);
    }

    public function updateDbpedia($chebi_id, $data){
        $this->db->where('chebiid', $chebi_id);
        $this->db->update('chemicalcompound', $data);
    }

}