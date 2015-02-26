<pre>
<?php
require_once("classes/hourlyemployee.php");

//make an instance of Employee
$emp1 = new Employee("Bob parker", "CEO", 354148142.32);
$emp2 = new Employee("Maryjo Wilson", "Facilities", 23512);
$emp3 = new HourlyEmployee("Jared Smith", "Sandwich Maker", 20000);

echo Employee::COMPANY." \r\n";

foreach(Employee::$employees as $emp)
{
    echo "$emp->firstName $emp->lastName is the $emp->job and makes $emp->formattedSalary \r\n";
}

$emp3->clockIn();
sleep(1);
$emp3->clockOut();

echo "$emp3->firstName made $emp3->formattedTotal today.\r\n";
?>
</pre>