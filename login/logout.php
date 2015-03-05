<?php 
require_once("classes/user.php");
$user = User::loggedinUser();

if($user)
{
   $user->logout(); 
}

header("Location: ./");
?>