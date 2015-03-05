<?php
require_once("classes/user.php");
//verify input
if(!empty($_POST['username']) && !empty($_POST['password']) && $_POST['password'] == $_POST['confirm_password'])
{
    if(!User::fromUsername($_POST['username']))
    {
        $user = new User();

        $user->username = $_POST['username'];
        $user->salt = User::generateSalt();
        $user->password = User::hashPassword($_POST['password'], $user->salt);
        
        $user->save();
    }
    
    header("Location: ./");
}





