<?php
require_once("database.php");
$animalPrep = $pdo->prepare("SELECT * FROM `animals` WHERE `id` = :id LIMIT 1");
$updatePrep = $pdo->prepare("UPDATE `animals` SET `name` = :name,`color` = :color,`price` = :price,`coat_type` = :coat_type,`gender` = :gender,`fixed` = :fixed,`sold` = :sold WHERE `id` = :id");
$prepJoin = $pdo->prepare("INSERT INTO `animals_has_breeds` (`animal_id`,`breed_id`) VALUES (:animal_id,:breed_id)");

if($animalPrep->execute(array(":id" => $_POST['id'])))
{
    $animal = $animalPrep->fetchObject();
    
    $dbValues = colonize($_POST);

    unset($dbValues[":species"]);
    $dbValues[":sold"] = 0;
    
    if(!$updatePrep->execute($dbValues))
    {
        echo "<pre>";
        var_dump($updatePrep->errorInfo());
        echo "</pre>";
    }
    $pdo->query("DELETE FROM `animals_has_breeds` WHERE `animal_id` = $animal->id");
    foreach($_POST['breeds'] as $breedId)
    {
       $worked = $prepJoin->execute(array(
            ":animal_id" => $animal->id,
            ":breed_id" => $breedId
        ));
        if(!$worked)
        {
            echo "<pre>";
            var_dump($prepJoin->errorInfo());
            echo "</pre>";
        }
    }
}
header("Location: ./");