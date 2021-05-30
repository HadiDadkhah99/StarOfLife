<?php
require_once './DataModel.php';
require_once './DataBaseHelper.php';
require_once './DataBaseManager.php';

class test extends DataModel
{

    public $name  ;
    public $wallet ;
    public $weight ;

    public function __construct(int $id)
    {
        parent::__construct($id);
    }

}


$d = new DataBaseManager("localhost", "root", "mm", "");

echo json_encode($d->delete(new test(1)));