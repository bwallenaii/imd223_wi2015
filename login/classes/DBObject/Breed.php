<?php
namespace DBObject;

class Breed extends \Database\Table
{
    protected $tableName = "breeds";
    
    public function getSpecies()
    {
        return new Species($this->species_id);
    }
}