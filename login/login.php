<?php
require_once("classes/user.php");
$loggedin = User::login($_POST['username'], $_POST['password']);

header("Location: secure.php");