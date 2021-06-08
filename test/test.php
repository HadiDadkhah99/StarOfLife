<?php
require_once '../StarOfLife.php';
start("mm", "root", "");

class UserTable extends DataModel
{
    //Default Primary Key
    /** @IGNORE */
    public ?int $id;

    //custom primary key
    /** @PRIMARY_KEY @AUTO_INCREMENT */
    public $my_key;
    //user name
    public $name;
    //user wallet
    public $wallet;

}

$user = new UserTable();

//where
$w = new WhereQuery();
//this is SQL Query ( Where my_key=88 )
$w->greatThan('my_key', 88);

$data = getAll($user, $w);

//print result
echo json_encode($data, JSON_UNESCAPED_UNICODE);
