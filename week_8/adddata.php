<?php
session_start();
setcookie("frosting", "chocolate", strtotime("+30 days"));
?><html>
    <head>
        <title>Adding Data</title>
    </head>
    <body>
        <p>Adding something below here</p>
        <?php $_SESSION['animal'] = "chicken"; 
            $_SESSION['rock'] = "diamond";
            setcookie("type", "Chocolate Chip", strtotime("+30 days"));
        ?>
    </body>
</html>