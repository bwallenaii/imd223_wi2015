<?php
require_once("autoload.php");
$animal = new \DBObject\Animal($_GET['id']);

if(!$animal->loaded())
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
            <strong>Species: </strong><?php echo $animal->species->type; ?>
        </div>
        <div>
            <strong>Breed(s): </strong><?php echo $animal->formattedBreeds?>
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
        <!--a href="update.php?id=<?= $animal->id ?>">Update</a-->
        <?php
            echo new \Helper\Element("a", "Update", array("href" => "update.php?id=$animal->id"));
        ?>
    </body>
</html>