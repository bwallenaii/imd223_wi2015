<?php
require_once("employee.php");
class HourlyEmployee extends Employee
{
    const YEARLY_HOURS = 2080;
    private $timeWorked = 0;
    private $startTime = 0;
    private $endTime = 0;
    private $_hourlyWage = 0;
    
    public function setSalary($salary)
    {
        parent::setSalary($salary);
        $this->hourlyWage = round($salary/self::YEARLY_HOURS, 2);
    }
    
    public function setHourlyWage($amount)
    {
        $this->_hourlyWage = $amount;
    }
    
    public function getHourlyWage()
    {
        return $this->_hourlyWage;
    }
    
    public function getFormattedSalary()
    {
        return parent::getFormattedSalary()." (\$$this->hourlyWage/hour)";
    }
    
    public function getTotalEarned()
    {
        return $this->timeWorked * $this->hourlyWage;
    }
    
    public function getFormattedTotal()
    {
        return "$".number_format($this->totalEarned, 2);
    }
    
    public function clockIn()
    {
        $this->startTime = time();
    }
    
    public function clockOut()
    {
        if(empty($this->startTime) || $this->startTime > time())
        {
            throw new Exception("Not properly clocked in. Cannot clock out.");
        }
        $this->endTime = time();
        $this->timeWorked = $this->endTime - $this->startTime;
    }
}