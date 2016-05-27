<?php

class Chemical_model extends CI_Model
{

    public function post_chemical_compound($ChemProperties){
        // can be array or not:
        // Chemical structures,
        // IupacNames
        // Synonyms
        // RegistryNumbers


        
        

    }
    public function getChemicalByName($name){
        $query = $this->db->get_where('ChemicalCompound', array('chebiname' => $name));
        $array = $query->result();

        if(empty($array)){
            $query = $this->db->get_where('Synonym', array('name' => $name));
            $synonyms = $query->result();
        }
        if(empty($synonyms)){
            
        }
    }


    public function getChemical($chebi_id){

        $query = $this->db->get_where('ChemicalCompound', array('chebiid' => $chebi_id));
        $array = $query->result();

//        print_r($array[0]);
        if(count($array) < 1){
            return $array;
        }

        $chemid = $array[0]->idChemicalCompound;
        $query = $this->db->get_where('Iupac', array('fkchemcomp' => $chemid));
        $iupac = $query->result();

        if(!empty($iupac)){
            $array[0]->iupacName = $iupac[0]->name;
            $array[0]->iupacType = $iupac[0]->iupacType;
        }

        $query = $this->db->get_where('Synonym', array('fkchemcomp' => $chemid));
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

        $query = $this->db->get_where('Registry', array('fkchemcomp' => $chemid));
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

        $this->db->insert('ChemicalCompound', $data);
        $rows = $this->db->affected_rows();
        if($rows > 0){
            $this->db->select('idChemicalCompound');
            $query = $this->db->get_where('ChemicalCompound', array('chebiid' => $chebiid));
            $result = $query->result();
            $fkChem = (int)$result[0]->idChemicalCompound;

            // After insert chemical, iupac
            if(is_null($chemProp->IupacNames)){} // dont insert

            else if(is_array($chemProp->IupacNames)){
                foreach ($chemProp->IupacNames as $iupac){
                    $data = array(
                        'name' => $iupac->data,
                        'fkchemcomp' => $fkChem,
                        'iupacType' => $iupac->type
                    );
                    $this->db->insert('Iupac', $data);
                }

            }
            else if(!is_null($chemProp->IupacNames)) {  // only one iupac
                $data = array(
                    'name' => $chemProp->IupacNames->data,
                    'fkchemcomp' => $fkChem,
                    'iupacType' => $chemProp->IupacNames->type
                );
                $this->db->insert('Iupac', $data);
            }

            // after insert chemical, synonyms
            if(is_null($chemProp->Synonyms)){} // dont insert

            else if(is_array($chemProp->Synonyms)){
                foreach ($chemProp->Synonyms as $synonym){
                    $data = array(
                        'name' => $synonym->data,
                        'fkchemcomp' => $fkChem,
                        'source' => $synonym->source
                    );
                    $this->db->insert('Synonym', $data);
                }
            }
            else if(!is_null($chemProp->Synonyms)) {  // only one synonym
                $data = array(
                    'name' => $chemProp->Synonyms->data,
                    'fkchemcomp' => $fkChem,
                    'source' => $chemProp->Synonyms->source
                );
                $this->db->insert('Synonym', $data);
            }

            // after insert chemical, registry numbers
            if(is_null($chemProp->RegistryNumbers)){} // dont insert

            else if(is_array($chemProp->RegistryNumbers)){
                foreach ($chemProp->RegistryNumbers as $registry){
                    $data = array(
                        'nregistry' => (int)$registry->data,
                        'fkchemcomp' => $fkChem,
                        'type' => $registry->type,
                        'source' => $registry->source
                    );
                    $this->db->insert('Registry', $data);
                }
            }
            else if(!is_null($chemProp->RegistryNumbers)) {  // only one synonym
                $data = array(
                    'nregistry' => (int)$chemProp->RegistryNumbers->data,
                    'fkchemcomp' => $fkChem,
                    'type' => $chemProp->RegistryNumbers->type,
                    'source' => $chemProp->RegistryNumbers->source
                );
                $this->db->insert('Registry', $data);
            }

        }
        return(true);
    }

    public function updateDbpedia($chebi_id, $data){
        $this->db->where('chebiid', $chebi_id);
        $this->db->update('ChemicalCompound', $data);
    }

}