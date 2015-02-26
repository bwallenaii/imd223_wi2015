<?php

class Employee
{
    const COMPANY = "ACME Widgets";
    public static $employees = array();
    private $_firstName = "";
    private $_lastName = "";
    private $_id;
    private $_job;
    private $_salary;
    
    public function __construct($name = "", $job = "", $salary = 0)
    {
        if(!empty($name))
        {
            $nameParts = explode(" ", $name, 2);
            $this->firstName = $nameParts[0];
            $this->lastName = $nameParts[1];
        }
        $this->job = $job;
        $this->salary = $salary;
        self::$employees[] = $this;
        $this->id = count(self::$employees);
    }
    
    public function setFirstName($fname)
    {
        if(is_string($fname))
        {
            $this->_firstName = ucfirst($fname);
        }
        else
        {
            throw new Exception("Invalid First Name.");
        }
    }
    
    public function getFirstName()
    {
        return $this->_firstName;
    }
    
    public function setLastName($lname)
    {
        if(is_string($lname))
        {
            $this->_lastName = ucfirst($lname);
        }
        else
        {
            throw new Exception("Invalid Last Name.");
        }
    }
    
    public function getLastName()
    {
        return $this->_lastName;
    }
    
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function setJob($job)
    {
        $this->_job = $job;
    }
    
    public function getJob()
    {
        return $this->_job;
    }
    
    public function setSalary($salary)
    {
        if(is_numeric($salary))
        {
            $this->_salary = $salary;
        }
    }
    
    public function getSalary()
    {
        return $this->_salary;
    }
    
    public function getFormattedSalary()
    {
        return "$".number_format($this->_salary, 2);
    }
    
    public function __get($k)
    {
    	$func = "get".ucfirst($k);
        if(method_exists($this, $func))
        {
            return $this->$func();
        }
        throw new Exception("$k does not exist.");
    }
    
    public function __set($k, $v)
    {
    	$func = "set".ucfirst($k);
        if(method_exists($this, $func))
        {
            $this->$func($v);
            return;
        }
        throw new Exception("$k does not exist.");
    }

    
    public function __destruct()
    {
       //echo self::$_firstName."$this->_lastName died.";
    }
}