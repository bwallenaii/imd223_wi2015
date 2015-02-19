<?php

require_once("database.php");

$animals = $pdo->query("SELECT * FROM `animals` WHERE `sold` = 0 ORDER BY `name` ASC LIMIT 0,20");


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
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    </head>
    <body>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Color</th>
                    <th>Species</th>
                    <th>Breed</th>
                    <th>View</th>
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
                        <td><a href="animal.php?id=<?php echo $animal->id ?>"><i class="fa fa-eye"></i></a></td>
                    </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        <a href="new.php">Create New <i class="fa fa-plus"></i></a>
    </body>
</html>