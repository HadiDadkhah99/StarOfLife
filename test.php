<?php
require_once './StarOfLife.php';
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


    public $name;
    public $wallet;
    public $weight;
    public $user_id;

}

$t = new test(87);
$c = new car();


echo json_encode(getAll(new test()));
