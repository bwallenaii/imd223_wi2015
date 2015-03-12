<?php
namespace DBObject;
use \Database\Table as Table;
use \Database\PDatabase as Pdatabase;
class User extends Table
{
    protected $tableName = "users";
    
    public static function lockdown()
    {
        $user = self::loggedinUser();

        if(!$user)
        {
            header("Location: ./");
        }
        
        return $user;
    }
    
    public static function login($username, $password)
    {
        $user = self::fromUsername($username);
        if($user)
        {
            if($user->password == self::hashPassword($password, $user->salt))
            {
                self::startSession();
                $_SESSION['userId'] = $user->id;
                return true;
            }
        }
        
        return false;
    }
    
    public static function loggedinUser()
    {
        self::startSession();
        
        if(!empty($_SESSION['userId']))
        {
            $user = new self($_SESSION['userId']);
            $id = $user->id;
            if(!empty($id))
            {
                return $user;
            }
        }
        return false;
    }
    
    public static function fromUsername($username)
    {
        $pdb = new PDatabase();
        $username = $pdb->sanitize($username);
        $user = $pdb->queryData("SELECT `id` FROM `users` WHERE `username` LIKE $username");
        
        if($user)
        {
            return new self($user->id);
        }
        return false;
    }
    
    public static function generateSalt($length = 10)
    {
        $letters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $salt = "";
        
        while(strlen($salt) < $length)
        {
            $salt .= $letters[rand(0, strlen($letters)-1)];
        }
        
        return $salt;
    }
    
    public static function hashPassword($password, $salt)
    {
        $hash = hash('SHA512', $salt.$password);
        
        return $hash;
    }
    
    public function logout()
    {
        self::startSession();
        $_SESSION['userId'] = null;
    }
}
