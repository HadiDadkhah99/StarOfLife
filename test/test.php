<?php
require_once '../StarOfLife.php';
start("mm", "root", "");

/** @TABLE (UserTable) */
class UserTable extends DataModel
{
    //Default Primary Key
    /** @IGNORE */
    public $id;

    //custom primary key
    /** @PRIMARY_KEY @AUTO_INCREMENT */
    public $my_key;
    //user name
    /** @COLUMN (name) */
    public $name;
    //user wallet
    public $wallet;

}

class car extends DataModel
{

    public $user_id;
    public $name;
}

class r extends DataModel
{
    /** @IGNORE  */
    public $id;

    /** @COLUMN (car.name) */
    public $name;

}

$user = new UserTable();
$car = new car();
$w = (new WhereQuery())
    ->join($car)->onEqual('car.user_id', 87)->commitJoin();

$data = getAll($user, $w,new r());

//print result
echo json_encode($data, JSON_UNESCAPED_UNICODE);
