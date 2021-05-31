<?php
require_once './StarOfLife.php';
start("localhost", "root", "mm", "");

class test extends DataModel
{

    /**
     * @PRIMARY_KEY @AUTO_INCREMENT
     *
     */
    public ?int $id;


    public $name = "";
    public $wallet;
    public $weight;





}

$o = new test(null);
$o->name = "hadi";
$o->wallet = 56;
$o->weight = 2342;
$o->id = 5;

$w=new WhereQuery();

insert($o);


//$dh = new DataBaseHelper(new DataBaseManager("localhost", "root", "mm", ""));
//var_dump($dh->classifyVarsName($o, true));
