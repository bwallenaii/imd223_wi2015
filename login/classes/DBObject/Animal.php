<?php
namespace DBObject;

class Animal extends \Database\Table
{
    protected $tableName = "animals";
    
    public static function getAnimals($offset = 0, $limit = 30)
    {
        $animal = new self();
        return $animal->getPage($offset, $limit);
    }
    
    public function getFormattedBreeds()
    {
        $breedNames = array();
        foreach($this->breeds as $breed)
        {
            $breedNames[] = $breed->type;
        }
        
        return implode(", ", $breedNames);
    }
    
    public function getBreeds()
    {
        $this->addStatement("hasBreeds", "SELECT `breed_id` FROM `animals_has_breeds` WHERE `animal_id` = :animal_id;");
        $breedIds = $this->runStatement("hasBreeds", array(":animal_id" => $this->id), true);
        $ret = array();
        
        foreach($breedIds as $bid)
        {
            $ret[] = new Breed(is_numeric($bid) ? $bid:$bid->breed_id);
        }
        return $ret;
    }
    
    public function getSpecies()
    {
        return $this->breeds[0]->species;
    }
}