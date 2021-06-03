<?php
require_once '../StarOfLife.php';

checkHeaderInputs(['Host']);

start("mm", "root", "");

class test extends DataModel
{


    public $name;
    public $wallet;
    public $weight;


}

class car extends DataModel
{

    public $user_id;
    public $name;


}

class join extends DataModel
{

    /** @COLUMN (car.id) */
    public ?int $id;
    /** @COLUMN (test.name) */
    public $name;
    public $wallet;
    public $weight;

    public $user_id;

}


$t = new test(87);

$c = new car();
$w = new WhereQuery();
$j = new join();

echo json_encode(getAll($t));