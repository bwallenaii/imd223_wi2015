<?php

try{
    $pdo = new PDO(
        "mysql:dbname=example_wi2015_b;host=localhost",
        "example",
        "pass123");
}
catch(PDOException $e)
{
    die("Cannot connect to database: ".$e->getMessage());
}

$prepHas = $pdo->prepare("SELECT `breed_id` FROM `animals_has_breeds` WHERE `animal_id` = :animal_id;");
$prepBreed = $pdo->prepare("SELECT * FROM `breeds` WHERE `id` = :breed_id");
$prepSpecies = $pdo->prepare("SELECT * FROM `species` WHERE `id` = :species_id");
