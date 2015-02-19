<?php
    require_once("database.php");
    
    $speciesQuery = $pdo->query("SELECT * FROM `species` ORDER BY `type` ASC");
    $animalPrep = $pdo->prepare("SELECT * FROM `animals` WHERE `id` = :id LIMIT 1");
    
    if($animalPrep->execute(colonize($_GET)))
    {
        $animal = $animalPrep->fetchObject();
        
        $breeds = array();
        $speciesType = "";
        $hass = $prepHas->execute(array(
            ':animal_id' => $animal->id
            ));
        foreach($prepHas as $hasBreed)
        {
            $prepBreed->execute(array(
                ":breed_id" => $hasBreed['breed_id']
            ));
            $breedObj = $prepBreed->fetchObject();
            $breeds[] = $breedObj->id;
            if(empty($speciesType))
            {
                $prepSpecies->execute(array(
                    ":species_id" => $breedObj->species_id
                ));
                $speciesObj = $prepSpecies->fetchObject();
                $speciesType = $speciesObj->type;
            }
        }
        
        $allBreeds = $pdo->query("SELECT * FROM `breeds` WHERE `species_id` = $speciesObj->id");
    }
    else
    {
        die("This is not the animal you are looking for.");
    }
    
    
?><html>
    <head>
        <title>Create New</title>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <style>
            label{
                display: block;
            }
            .inline{
                display: inline;
            }
            .red{
                color: #990000;
                font-size: 15em;
            }
            .green{
                color: #009900;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/mootools/1.5.1/mootools-yui-compressed.js"></script>
        <script>
            document.addEvent("domready", function(){
                var speciesSelect = document.getElement("select[name='species']");
                var req = new Request.JSON({
                    url:"breeds.php"
                });
                var breedsArea = document.id("breeds");
                
                req.addEvent("complete", function(json){
                    for(var i = 0; i < json.breeds.length;++i)
                    {
                        var label = new Element("label", {
                            "class":"inline"
                        });
                        var input = new Element("input", {
                            type:"checkbox",
                            name: "breeds[]",
                            value:json.breeds[i].id
                        });
                        var span = new Element("span", {
                            html:json.breeds[i].type
                        });
                        
                        label.grab(input);
                        label.grab(span);
                        breedsArea.grab(label);
                    }
                });
                
                speciesSelect.addEvent("change", function(){
                    breedsArea.empty();
                    req.send({
                        data:{
                            id:this.value
                        }
                    });
                });
            });
        </script>
    </head>
    <body>
    <form id="animal-form" action="updateanimal.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?=$animal->id ?>" />
        <label>
            Name: <input type="text" name="name" value="<?= $animal->name ?>" />
        </label>
        <label>
            Species:
            <select name="species">
                <option value="">Select a species...</option>
            <?php foreach($speciesQuery as $species): 
                $species = (Object) $species;
            ?>
                <option value="<?php echo $species->id; ?>"<?= $speciesObj->id == $species->id ? " selected=\"selected\"":""?>><?php echo $species->type; ?></option>
            <?php endforeach; ?>
            </select>
        </label>
        <div id="breeds">
            <?php foreach($allBreeds as $breed): ?>
            <label class="inline">
                <input type="checkbox" name="breeds[]" value="<?=$breed['id'] ?>"<?= in_array($breed['id'], $breeds) ? " checked=\"checked\"":"" ?> />
                <span><?=$breed['type'] ?></span>
            </label>
            <?php endforeach; ?>
        </div>
        
        <label>
            Color: <input type="text" name="color" value="<?= $animal->color ?>" />
        </label>
        <label>
            Coat: <input type="text" name="coat_type" value="<?= $animal->coat_type ?>" />
        </label>
        <h4>Gender</h4>
        <label class="inline">
            <input type="radio" name="gender" value="male"<?= $animal->gender == "male" ? " checked=\"checked\"":""?>>Male
        </label>
        <label class="inline">
            <input type="radio" name="gender" value="female"<?= $animal->gender == "female" ? " checked=\"checked\"":""?>>Female
        </label>
        <h4>Fixed</h4>
        <label class="inline">
            <input type="radio" name="fixed" value="1"<?= $animal->fixed ? " checked=\"checked\"":""?>>Yes
        </label>
        <label class="inline">
            <input type="radio" name="fixed" value="0"<?= !$animal->fixed ? " checked=\"checked\"":""?>>No
        </label>
        <label>
            Price: <input type="number" name="price" value="<?= $animal->price ?>" />
        </label>
        <button type="submit">Update Animal</button>
    </form>
    
    </body>
</html>