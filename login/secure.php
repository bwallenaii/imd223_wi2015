<?php
require_once("autoload.php");
$user = \DBObject\User::lockdown();
?><html>
    <head>
        <title>Sign Up/Login</title>
    </head>
    <style>
        label{
            display: block;
        }
    </style>
    <body>
        <h1>Hello, <?php echo $user->username; ?></h1>
        <a href="logout.php">Logout</a>
    </body>
</html>