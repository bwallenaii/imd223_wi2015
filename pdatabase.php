<?php

class PDatabase
{
	const HOST = "localhost";
	const USER_NAME = "examplefa2013";
	const PASSWORD = "pass123";
	const DATABASE = "examplefa2013";
	const SHOW_ERRORS = true;
    const HALT_ON_ERROR = true;
	
	private static $dbs = array();
	
	protected $pdo;
    protected $pdoStatements = array();
	
	public function __construct()
	{
		$this->connect();
	}
    
    protected static function stopError($msg)
	{
		if(self::HALT_ON_ERROR)
		{
			die("<pre>".print_r($msg, true)."</pre>");
		}
		else
		{
			self::preOut($msg);
		}
	}
	
	public static function preOut($msg, $force = false)
	{
		if(self::SHOW_ERRORS || $force)
		{
			echo "<pre>";
			print_r($msg);
			echo "</pre>";
		}
	}
	
	protected function connect($user = self::USER_NAME, $password = self::PASSWORD, $database = self::DATABASE, $host = self::HOST)
	{
		if(empty(self::$dbs[$database]))
		{
            try{
                self::$dbs[$database] = new PDO("mysql:dbname=$database;host=$host", $user, $password);
            }
			catch(Exception $e)
            {
                die("Database connection failure: ".$e->getMessage());
            }
		}
        $this->pdo = self::$dbs[$database];
	}
    
    //good for inserts, updates, or if you want the PDOStatement back
    public function query($q, Array $attrs = array())
    {
        if($q instanceof PDOStatement)
        {
        	$res = $q;
            if(!$res->execute($attrs))
            {
                self::stopError(print_r($res->errorInfo(), true)."<br /><br />");
                return false;
            }
        }
        else
        {
            $res = $this->getPDO()->query($q);
        }
        if($res === false)
        {
            self::stopError(print_r($this->pdo->errorInfo(), true)."<br /><br />Full query: $q");
            return false;
        }
        return $res;
    }
    
    public function getPDO()
    {
        return $this->pdo;
    }
    
    //good for making select statements; returns a full data object or
    // an array of data objects if the statement works.
    public function queryData($q, Array $attrs = array(), $forceArray = false)
    {
        $res = $this->query($q, $attrs);
        if($res === false || $res->rowCount() == 0)
        {
        	return false;
        }
        if($res->rowCount() == 1 && !$forceArray)
        {
        	
        	return $res->fetchObject();
        }
        $ret = array();
        while($item = $res->fetchObject())
        {
            $ret[] = $item;
        }
        return $ret;
    }
    
    public function prepare($query)
    {
        return $this->pdo->prepare($query);
    }
    
    public function addStatement($name, $query)
    {
        $this->pdoStatements[$name] = $this->pdo->prepare($query);
        return $this->pdoStatements[$name];
    }
    
    public function runStatement($name, Array $attrs, $returnData = true, $forceArray = false)
    {
        if(!empty($this->pdoStatements[$name]))
        {
            $res = $this->queryData($this->pdoStatements[$name], $attrs, $forceArray);
            if($returnData)
            {
                return $res;
            }
            return true;
        }
        return false;
    }
    
    public function sanitize($str)
	{
		return $this->pdo->quote($str);
	}
    
    //turns an object into attributes for prepared statements
    public function buildAttrs($obj)
    {
        $ret = array();
        foreach($obj as $a => $b)
        {
            $ret[":".$a] = $b;
        }
        return $ret;
    }
    
    /**
	 * @name insert
	 * @description Builds and executes an insert statement
	 * @author Brent Allen
	 * @argument	table		String		The database table you insert into
	 * @argument	data		StdClass	Name/value pairs for the fields to insert
	 * @return		Boolean		true on success, false otherwise.
	*/
	public function insert($table, $data)
	{
		$keys = "";
		$values = "";
		
		foreach($data as $key => $value)
		{
			$keys .= "`$key`, ";
			$values .= ":$key, ";
		}
		
		$keys = substr($keys, 0, -2);
		$values = substr($values, 0, -2);
		
		$p = $this->prepare("INSERT INTO `$table` ($keys) VALUES ($values)");
		
		return $this->query($p, $this->buildAttrs($data));
	}
    
    public function update($table, $tableId, $id, $data, $sanitize = true)
    {
        $query = "UPDATE $table SET ";
        
        foreach($data as $key => $value)
		{
			$query .= "`$key` = :$key, ";
		}
        $query = substr($query, 0, strlen($query) - 2);
        $query .= " WHERE `$tableId` = '$id'";
        $p = $this->prepare($query);
        $this->query($p, $this->buildAttrs($data));
        return $this->query($p, $this->buildAttrs($data));
    }
}