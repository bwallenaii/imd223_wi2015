<?php
class Database
{
	const HOST = "localhost";
	const USER_NAME = "examplesp2013";
	const PASSWORD = "pass123";
	const DATABASE = "examplesp2013";
	const SHOW_ERRORS = true; //whether or not to show SQL errors
	const SHOW_EMPTY = false; //whether or not to show Error when no result set retrieved
	const HALT_ON_ERROR = true; //whether or not to stop all processing when in error
	private static $db;
    private static $numConnections = 0;
    
	protected $mli;
	
	public function Database()
	{
	   if(self::$db == NULL)
	   {
	       self::$db = new MySQLi(self::HOST, self::USER_NAME, self::PASSWORD, self::DATABASE);
	       if(self::$db->connect_errno)
            {
    			$this->stopError("Connection error: ".self::$db->connect_error);
    		}
        }
        $this->mli = self::$db;
        self::$numConnections++;
	}
	
	protected function stopError($msg)
	{
		if(self::SHOW_ERRORS)
		{
			die("<pre>".print_r($msg, true)."</pre>");
		}
	}
	
	public static function preOut($msg)
	{
		if(self::SHOW_ERRORS)
		{
			echo "<pre>".print_r($msg, true)."</pre>";
		}
	}
	
	/**
	 * @name query
	 * @description Make a basic SQL query. Mostly used as a supporting function within the class.
	 * Only use externally when making queries that will not return a result set. (UPDATE, DELETE, etc.)
	 * @author Brent Allen
	 * @argument	$query	String	The query you wish to make.
	 * @return	Result	Basic query result.
	*/
	public function query($query)
	{
		$result = $this->mli->query($query);
		
		if($this->mli->affected_rows == 0 && self::SHOW_EMPTY)
		{
			self::preOut("No result set: $query");
		}
		if($this->mli->errno)
		{
            $error = "Query Error: ".$this->mli->error." \r\n $query";
            self::HALT_ON_ERROR ?  self::stopError($error):self::preOut($error);
			return NULL;
		}
		else
		{
			return $result;
		}
	}
    
    public function sqlCall($query)
    {
        return $this->query($query);
    }
	
	public function sanitize($str)
	{
		return filter_var($str, FILTER_SANITIZE_STRING);
	}
	
	/**
	 * @name getArray
	 * @description Gets an array of all data objects created by the result set
	 * @author Brent Allen
	 * @argument	$query	String	The query you wish to make.
	 * @return	Array	An array of the result set data objects
	*/
	public function getArray($query)
	{
		$ret = array();
		$res = $this->query($query);
		while($row = $res->fetch_object())
		{
			array_push($ret, $row);
		}
		$res->free();
		return $ret;
	}
	
	/**
	 * @name getItem
	 * @description Gets a single data object from the result set
	 * @author Brent Allen
	 * @argument	$query	String	The query you wish to make.
	 * @return	StdClass	The result data object
	*/
	public function getItem($query)
	{
		$res = $this->query($query);
		$data = $res->fetch_object();
		$res->free();
		return $data;
	}
	
	/**
	 * @name insert
	 * @description Builds and executes an insert statement
	 * @author Brent Allen
	 * @argument	table		String		The database table you insert into
	 * @argument	data		StdClass	Name/value pairs for the fields to insert
	 * @argument	sanitize	Boolean		Whether or not to auto-sanitize the data. Defaults to true.
	 * @return		Boolean		true on success, false otherwise.
	*/
	public function insert($table, $data, $sanitize = true)
	{
		$keys = '';
		$values = '';
		
		foreach($data as $key => $value)
		{
			$keys .= '`'.$key . '`, ';
			$values .= $sanitize ? '"'.$this->sanitize($value). '", ':'"'.$value.'", ';
		}
		
		$keys = substr($keys, 0, -2);
		$values = substr($values, 0, -2);
		
		$query_string = 'INSERT INTO `'.$table.'` ('.$keys.')'.' VALUES ('.$values.')';
		$res = $this->query($query_string);
		return $res;
	}
    
    public function update($table, $tableId, $id, $data, $sanitize = true)
    {
        $query = "UPDATE $table SET ";
        $oldData = $this->getItem("SELECT * FROM `$table` WHERE `$tableId` = '$id'");
        $numUpdates = 0;
        foreach($data as $name => $item)
        {
            if(!isset($oldData->name) || $oldData->$name != $item)
            {
                $numUpdates++;
                if($sanitize)
                {
                    $query .= "`$name` = '".$this->sanitize($item)."', ";
                }
                else
                {
                    $query .= "`$name` = '$item', ";
                }
            }
        }
        if($numUpdates == 0)
        {
            return false;
        }
        $query = substr($query, 0, strlen($query) - 2);
        $query .= " WHERE `$tableId` = '$id'";
        $this->query($query);
        return true;
    }
	
	public function __destruct()
	{
	        self::$numConnections--;
	        if(self::$numConnections == 0)
	        {
	            self::$db->close();
	            self::$db = null;
	        }
	}
}