<?php
    require_once("database.php");
    
    $speciesQuery = $pdo->query("SELECT * FROM `species` ORDER BY `type` ASC");
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
        
    </head>
    <body>
    <form id="animal-form" action="dunno.php" method="post" enctype="multipart/form-data">
        <label>
            Name: <input type="text" name="name" />
        </label>
        <label>
            Species:
            <select name="species">
            <?php foreach($speciesQuery as $species): 
                $species = (Object) $species;
            ?>
                <option value="<?php echo $species->id; ?>"><?php echo $species->type; ?></option>
            <?php endforeach; ?>
            </select>
        </label>
        <div id="breeds"></div>
        
        <label>
            Color: <input type="text" name="color" />
        </label>
        <label>
            Coat: <input type="text" name="coat_type" />
        </label>
        <h4>Gender</h4>
        <label class="inline">
            <input type="radio" name="gender" value="male">Male
        </label>
        <label class="inline">
            <input type="radio" name="gender" value="female">Female
        </label>
        <h4>Fixed</h4>
        <label class="inline">
            <input type="radio" name="fixed" value="1">Yes
        </label>
        <label class="inline">
            <input type="radio" name="fixed" value="0">No
        </label>
        <label>
            Price: <input type="number" name="price" />
        </label>
        <button type="submit">Add Animal</button>
    </form>
    
    </body>
</html>