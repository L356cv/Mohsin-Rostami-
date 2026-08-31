<?php

// Name: Mohsin Rostami
// Student ID: 51


// Task 1
class Library
{
    const MAX_BOOKS = 3;
}

// This value is a constant because the maximum number of books is a fixed rule.
echo "Maximum books allowed: " . Library::MAX_BOOKS;

echo "<br>";


// Task 2
class StudentCounter
{
    public static $count = 0;

    public static function addStudent()
    {
        self::$count++;
    }
}

StudentCounter::addStudent();
StudentCounter::addStudent();
StudentCounter::addStudent();

echo "Total students: " . StudentCounter::$count;

echo "<br>";


// Task 3
abstract class Vehicle
{
    abstract public function start();
}

class Car extends Vehicle
{
    public function start()
    {
        echo "Car engine started.";
    }
}

class Bike extends Vehicle
{
    public function start()
    {
        echo "Bike started.";
    }
}

$car = new Car();
$bike = new Bike();

$car->start();

echo "<br>";

$bike->start();

?>
