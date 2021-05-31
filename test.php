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
$o->name = "ever";
$o->wallet = 654321;
$o->weight = 123456;
$o->id = 77;

$w=new WhereQuery();
$w->NOT()->equal('id',$o->id);


echo json_encode(getAll($o,$w));


