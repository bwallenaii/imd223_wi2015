<html>
    <head>
        <title>My First PHP script</title>
    </head>
    <body>
        <?php include("navigation.php"); ?>
        <?php //require("slider.php"); ?>
        <h1>Some PHP is below</h1>
        <p>Not here.</p>
        <p><?php 
        echo "Here it was!</p>\r\n\t"."<p>Here is some more.";
        ?></p>
        
        <?php
            $names = array("George", "Fred", "Alicia");
            //pushing data in with the array_push() function.
            array_push($names, "Juniper");
            //pushing data in without a function
            $names[] = "Harry";
            echo count($names)."<br />";
            
            $employee = array('firstName' => "George", 'lastName' => "Rogers");
            //adding an item in an associative array
            $employee['job'] = "Janitor";
            
            //echo $employee['firstName']." ".$employee['lastName']." : ".$employee['job'];
            
            foreach($employee as $index => $data)
            {
                echo "$index: $data <br />\r\n";
            }
            //typecast array to object
            $empObj = (Object) array(
                "firstName" => "Fred",
                "lastName" => "Billings",
                "job" => "CFO",
            );
            //create Object then add the items to it
            $empObj2 = new stdClass();
            $empObj2->firstName = "Rita";
            $empObj2->lastName = "Smith";
            $empObj2->job = "CEO";
            $empObj2->salary = 1861541;
            
            echo "$empObj->firstName works here.";
        ?>
        <pre>
        <?php print_r($empObj); ?>
        
        <?php var_dump($empObj2); ?>
        </pre>
    </body>
</html>