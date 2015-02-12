<?php

require_once("database.php");

$animalId = empty($_GET['id']) || !is_numeric($_GET['id']) ? 0:(int) $_GET['id'];

$animalQuery = $pdo->query("SELECT * FROM `animals` WHERE `id` = $animalId");
$animal = false;
if($animalQuery)
{
    $animal = $animalQuery->fetchObject();
    $breeds = array();
    $species = "";
    $hass = $prepHas->execute(array(
        ':animal_id' => $animal->id
        ));
    foreach($prepHas as $hasBreed)
    {
        $prepBreed->execute(array(
            ":breed_id" => $hasBreed['breed_id']
        ));
        $breed = $prepBreed->fetchObject();
        $breeds[] = $breed->type;
        if(empty($species))
        {
            $prepSpecies->execute(array(
                ":species_id" => $breed->species_id
            ));
            $speciesObj = $prepSpecies->fetchObject();
            $species = $speciesObj->type;
        }
    }
}

if(empty($animal))
{
    die("This is not the animal you are looking for.");
}

?><html>
    <head>
        <title><?php echo $animal->name; ?>'s Page</title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <style>
            .red{
                color: #990000;
                font-size: 15em;
            }
            .green{
                color: #009900;
            }
        </style>
        
    </head>
    <body>
        <h1><?php echo $animal->name; ?></h1>
        <div>
            <strong>Species: </strong><?php echo $species; ?>
        </div>
        <div>
            <strong>Breed(s): </strong><?php echo implode(", ", $breeds);?>
        </div>
        <div>
            <strong>Color: </strong><?php echo $animal->color; ?>
        </div>
        <div>
            <strong>Coat: </strong><?php echo $animal->coat_type; ?>
        </div>
        <div>
            <strong>Gender: </strong><?php echo $animal->gender; ?>
        </div>
        <div>
            <strong>Fixed: </strong><?php echo $animal->fixed ? "Yes":"No"; ?>
        </div>
        <div>
            <strong>Available: </strong><i class="fa fa-<?php echo $animal->sold ? "times-circle red":"check-circle green"; ?>"></i>
        </div>
        <div>
            <strong>Price: </strong>$<?php echo number_format($animal->price, 2); ?>
        </div>
    </body>
</html>