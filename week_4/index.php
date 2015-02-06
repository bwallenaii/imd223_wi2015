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

$animals = $pdo->query("SELECT * FROM `animals` WHERE `sold` = 0 ORDER BY `name` ASC LIMIT 0,20");
$prepHas = $pdo->prepare("SELECT `breed_id` FROM `animals_has_breeds` WHERE `animal_id` = :animal_id;");
$prepBreed = $pdo->prepare("SELECT * FROM `breeds` WHERE `id` = :breed_id");
$prepSpecies = $pdo->prepare("SELECT * FROM `species` WHERE `id` = :species_id");

?><html>
    <head>
        <title>My Pet Store</title>
        <style>
            table{
                border-collapse: collapse;
            }
            table th{
                border: 1px solid #222222;
                padding: 3px;
                color: #000000;
            }
            table td{
                border: 1px solid #666666;
                padding: 3px;
                color: #222222;
            }
        </style>
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Color</th>
                    <th>Species</th>
                    <th>Breed</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($animals as $animal){
                        $animal = (Object) $animal;
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
                ?>
                    <tr>
                        <td><?php echo $animal->name ?></td>
                        <td><?php echo $animal->color ?></td>
                        <td><?php echo implode(", ", $breeds); ?></td>
                        <td><?php echo $species ?></td>
                        <td><?php echo "\$$animal->price" ?></td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
    </body>
</html>