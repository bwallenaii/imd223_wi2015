<?php
require_once("autoload.php");
$loggedin = \DBObject\User::login($_POST['username'], $_POST['password']);

header("Location: secure.php");