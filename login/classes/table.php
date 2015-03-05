<?php
require_once("pdatabase.php");
class Table extends PDatabase
{
    protected $tableName = "";
    protected $tableId = "id";
    private static $columnsForTable = array();
    protected $columns;
    protected $data; //holds the data from the row
    protected $verifyFields = true; //ensure the field exists on get/set
    protected $className;
    protected $isNew = true;
    protected $autoTrim = true; //trim strings on setting
    
    public function __construct($id = null)
    {
        parent::__construct();
        $this->data = new StdClass();
        $this->className = get_class($this);
        if(is_object($id))
        {
        	$this->data = $id;
            $tid = $this->tableId;
            if(!empty($this->$tid))
            {
                $this->isNew = false;
            }
        }
        else if($id)
        {
            $this->data = $this->queryData("SELECT * FROM `$this->tableName` WHERE `$this->tableId` = $id;");
            $this->isNew = false;
        }
        
        if(!isset(self::$columnsForTable[$this->tableName]))
        {
            self::$columnsForTable[$this->tableName] = $this->queryData("SHOW COLUMNS FROM `$this->tableName`", array(), true);
        }
        $this->columns = self::$columnsForTable[$this->tableName];
    }
    
    public static function build($class, $id = null)
    {
        return new $class($id);
    }
    
    public function getPage($offset = 0, $limit = 30)
    {
        $ret = array();
        if($limit > 0)
        {
            $items = $this->queryData("SELECT * FROM `$this->tableName` LIMIT $offset, $limit", array(), true);
        }
        else
        {
            $items = $this->queryData("SELECT * FROM `$this->tableName`", array(), true);
        }
        
        foreach($items as $item)
        {
        	$ret[] = self::build($this->className, $item);
        }
        
        return $ret;
    }
    
    public function getAll()
    {
        return self::getPage(0, 0);
    }
    
    public function hasField($field)
    {
        $ret = false;
        foreach($this->columns as $column)
        {
            if($column->Field == $field)
            {
                $ret = true;
                break;
            }
        }
        
        return $ret;
    }
    
    public function save()
    {
        if($this->isNew)
        {
            $this->insert($this->tableName, $this->data);
            $this->data = $this->queryData("SELECT * FROM `$this->tableName` ORDER BY `$this->tableId` DESC LIMIT 1");
        }
        else
        {
            $tid = $this->tableId;
            $this->update($this->tableName, $tid, $this->$tid, $this->data);
        }
    }
    
    public static function startSession()
    {
        $sessId = session_id();
        if(empty($sessId))
        {
            session_start();
        }
    }
    
    public function __get($key)
    {
        $func = "get".ucfirst($key);
        if(method_exists($this, $func))
        {
            return $this->$func();
        }
        if($this->verifyFields && !$this->hasField($key))
        {
            throw new Exception("$key does not exist on table $this->tableName.");
        }
        if(!empty($this->data->$key))
        {
            return $this->data->$key;
        }
        else
        {
            return null;
        }
    }
    
    public function __set($key, $val)
    {
        $func = "set".ucfirst($key);
        if(method_exists($this, $func))
        {
            $this->$func($val);
            return;
        }
        if($this->verifyFields && !$this->hasField($key))
        {
            throw new Exception("$key does not exist on table $this->tableName.");
            return;
        }
        $this->data->$key = $this->autoTrim && is_string($val) ? trim($val):$val;
    }
}