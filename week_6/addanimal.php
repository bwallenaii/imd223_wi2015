<?php
require_once("database.php");

$prepIn = $pdo->prepare("INSERT INTO `animals` (`name`,`color`,`price`,`coat_type`,`gender`,`fixed`,`sold`) VALUES (:name,:color,:price,:coat_type,:gender,:fixed,:sold)");
$prepGetAnimal = $pdo->prepare("SELECT * FROM `animals` ORDER BY `id` DESC LIMIT 1");
$prepJoin = $pdo->prepare("INSERT INTO `animals_has_breeds` (`animal_id`,`breed_id`) VALUES (:animal_id,:breed_id)");

$dbValues = colonize($_POST);

unset($dbValues[":species"]);
$dbValues[":sold"] = 0;

if($prepIn->execute($dbValues) && $prepGetAnimal->execute())
{
    $animal = $prepGetAnimal->fetchObject();
    
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
    header("Location: ./");
}


