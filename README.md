# StarOfLife
The mini php frame work

## At the first you need add Main file on your project:

```php
require_once 'StarOfLife.php';
```
Then start frame work with:
```php
start("dbName", "userName", "password");
```

### Insert new Data on DataBase
For insert new data you have to create class that extends of (DataModel) and Have the same name with DataBase table
```php
<?php 
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

class UserTable extends DataModel
{
    //user name
    public $name;
    //user wallet
    public $wallet;

}

$user = new UserTable();
$user->name="Hadi";
$user->wallet=5000;
insert($user);

?>
```

### Update Data on DataBase
For update data you have to create class that extends of (DataModel) and Have the same name with DataBase table
<br>
**default Primary key is $id in DataModel**
```php
<?php 
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

class UserTable extends DataModel
{
    //user name
    public $name;
    //user wallet
    public $wallet;

}

$user = new UserTable(5);
$user->name="Hadi";
$user->wallet=5000;
update($user);

?>
```

## If you have another Primary Key try this way

```php
<?php
require_once '../StarOfLife.php';
start("dbName", "userName", "password");

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
$user->my_key = 87;
$user->name = "Hadi";
$user->wallet = 5000;

update($user);

?>
```
