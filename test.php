<?php
require_once './StarOfLife.php';
start("mm", "root", "");

class test extends DataModel
{


    public $name = "";
    public $wallet;
    public $weight;


}

$o = new test(null);
$o->name = "ever";
$o->wallet = 654321;
$o->weight = 123456;
$o->id = 44;

$w = new WhereQuery();
$w->greatThan('id', 74,true);


echo json_encode(getAll($o,$w));



