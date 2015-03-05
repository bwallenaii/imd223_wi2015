<?php
session_start();

?><html>
    <head>
        <title>Using Data</title>
    </head>
    <body>
        <p>Showing something below here</p>
        <pre>
        <?php 
            var_dump($_SESSION);
            var_dump($_COOKIE);
        ?></pre>
    </body>
</html>