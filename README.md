# StarOfLife

The mini php frame work for api

## At the first you need add Main file on your project:

```php
require_once 'StarOfLife.php';
```

Then start frame work with:

```php
start("dbName", "userName", "password");
```

### #Insert new Data on DataBase

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

### #Update Data on DataBase

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

### #Delete Data from DataBase

For delete data you have to create class that extends of (DataModel) and Have the same name with DataBase table
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
delete($user);

?>
```

### #Get Data from DataBase

For delete data you have to create class that extends of (DataModel) and Have the same name with DataBase table
<br>
**default Primary key is $id in DataModel**
<br>
<br>
** Get Special data **

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
$user->id = 87;

$data = get($user);

//print result
echo json_encode($data, JSON_UNESCAPED_UNICODE);


?>
```

** Get All data **

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

$data = getAll($user);

//print result
echo json_encode($data, JSON_UNESCAPED_UNICODE);


?>
```

<br>

### If the class and table names are not the same , use this annotation :

```php
<?php 

/** @TABLE (user_table) */
class UserTable extends DataModel
{
 //...
 //...
 //...
}
```

### If the property and column names are not the same , use this annotation :

```php
<?php 

class UserTable extends DataModel
{
   
   /** @COLUMN (f_name) */
   public $first_name;
    
}
```

# How to use where query ?

### Get all data with custom ``` where ```

** Get All data **

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

//where
$w = new WhereQuery();
//this is SQL Query ( Where my_key > 88 )
$w->greatThan('my_key', 88);

$data = getAll($user, $w);

//print result
echo json_encode($data, JSON_UNESCAPED_UNICODE);


?>
```

# If you have another Primary Key try this way

**Update**

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

class UserTable extends DataModel
{
    //Default Primary Key
    /** @IGNORE */
    public ?int $id;

    //custom primary key
    /** @PRIMARY_KEY  */
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

**Delete**

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

class UserTable extends DataModel
{
    //Default Primary Key
    /** @IGNORE */
    public ?int $id;

    //custom primary key
    /** @PRIMARY_KEY  */
    public $my_key;
    //user name
    public $name;
    //user wallet
    public $wallet;

}

$user = new UserTable();
$user->my_key = 87;

delete($user);

?>
```

## How use where query ?

#### Example 1: Get user if (name == hadi)

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/** @TABLE(user_table) */
class UserTable extends DataModel
{
    
    //user name
    public $name;
    
    //user wallet
    public $wallet;

}

$where=WhereQuery::instance()->equal('name','hadi');

//first argument is selectable table and second argument is where
//SELECT id , name , wallet FROM user_table WHERE name='hadi' 
/** @var  $user UserTable */
$user=get(new UserTable(),$where);


/** if you want to delete or update or insert or ...

$user->name='Hadi Dadkhah';
$user->wallet=313;
update($user);
delete($user);
insert($user);
 
*/

?>
```

#### Example 2: Get user if (wallet > 5)

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/** @TABLE(user_table) */
class UserTable extends DataModel
{
    
    //user name
    public $name;
    
    //user wallet
    public $wallet;

}

//for ( >= ) use bool $orEqual = true 
$where=WhereQuery::instance()->greatThan('wallet',5);

//first argument is selectable table and second argument is where
//SELECT id , name , wallet FROM user_table WHERE wallet > 5 
/** @var  $user UserTable */
$user=get(new UserTable(),$where);


/** if you want to delete or update or insert or ...

$user->name='Hadi Dadkhah';
$user->wallet=313;
update($user);
delete($user);
insert($user);
 
*/

?>
```

#### Example 3: Get user if (name like %h or %h% or ...)

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/** @TABLE(user_table) */
class UserTable extends DataModel
{
    
    //user name
    public $name;
    
    //user wallet
    public $wallet;

}

$where=WhereQuery::instance()->like('name','%h');

//first argument is selectable table and second argument is where
//SELECT id , name , wallet FROM user_table WHERE name LIKE '%h'
/** @var  $user UserTable */
$user=get(new UserTable(),$where);


/** if you want to delete or update or insert or ...

$user->name='Hadi Dadkhah';
$user->wallet=313;
update($user);
delete($user);
insert($user);
 
*/

?>
```

#### Example 4: Get users with limit

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/** @TABLE(user_table) */
class UserTable extends DataModel
{
    
    //user name
    public $name;
    
    //user wallet
    public $wallet;

}

$where=WhereQuery::instance()->limit(5);

//first argument is selectable table and second argument is where
//SELECT id , name , wallet FROM user_table LIMIT 5
/** @var  $users UserTable */
$users=getAll(new UserTable(),$where);



?>
```

#### Example 5: Order users

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/** @TABLE(user_table) */
class UserTable extends DataModel
{
    
    //user name
    public $name;
    
    //user wallet
    public $wallet;

}

$where=WhereQuery::instance()
        ->equal('name','hadi')
        ->and()
        ->greatThan('id',2)
        ->orderBy('id',true);

//first argument is selectable table and second argument is where
//SELECT id , name , wallet FROM user_table WHERE name='hadi' and id=2 ORDER BY id ASC
/** @var  $users UserTable */
$users=getAll(new UserTable(),$where);



?>
```

#### Example 6: Custom order

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/** @TABLE(user_table) */
class UserTable extends DataModel
{
    
    //user name
    public $name;
    
    //user wallet
    public $wallet;
    
    //user status
    public $status;

}

$where=WhereQuery::instance()
        ->equal('name','hadi')
        ->and()
        ->greatThan('id',2)
        ->customOrderBy("ORDER BY CASE status WHEN 'ENABLED' THEN 1 WHEN 'DISABLED' THEN 2 ELSE 3 END ASC");

//first argument is selectable table and second argument is where
/** @var  $users UserTable */
$users=getAll(new UserTable(),$where);



?>
```

#### Example 7: Get users paginated

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/** @TABLE(user_table) */
class UserTable extends DataModel
{
    
    //user name
    public $name;
    
    //user wallet
    public $wallet;

}

$where=WhereQuery::instance()
        /**
         *... any where 
         */
        ->page(1,10);
        // 1 == page number 
        // 10 == items in per page

/** @var  $users UserTable */
$users=getAll(new UserTable(),$where);



?>
```

#### Example 7: join to another table

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/** @TABLE(user_table) */
class UserTable extends DataModel
{
    
    //user name
    public $name;
    
    //user wallet
    public $wallet;

}
/** @TABLE(image_table) */
class ImageTable extends DataModel
{
    
    //image name
    public $image_name;
    
    //foreign key --> user table 
    public $for_user;
    

}

class ResultData extends DataModel
{
    
    //user name
    /** @COLUMN(user_table.name) */
    public $name;
    
    //user wallet
    /** @COLUMN(user_table.wallet) */
    public $wallet;
    
    //image name
    /** @COLUMN(image_table.image_name) */
    public $image_name;
    
    /** @COLUMN(image_table.for_user) */
    public $for_user;

}


//SELECT user_table.* , image_table.* FROM user_table JOIN image_table ON image_table.for_user=user_table.id LIMIT 2
$where=WhereQuery::instance()
        ->join(new ImageTable())
        ->onEqual('image_table.for_user','user_table.id')
        ->commitJoin()
        ->limit(2);
        //... any where
       
/** @var  $users UserTable */
$users=getAll(new UserTable(),$where,new ResultData());



?>
```

### Custom query :)
```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/** @var  $data array */
$data=query("SELECT * FROM user_table WHERE id > :i AND name= :n ",[':i'=>5,':n'=>'hadi']);

?>
```

# How to check GET and POST and HEADER inputs (Suitable for api)

### Check get inputs and post inputs

```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

/**
 * First argument is GET,POST,HEADER inputs that will be checked $_GET['name'] , $_GET['last_name'] , $_POST[...] , HEADER[...]
 * Second argument (true) to check if the inputs are empty (if empty die and show message)
 * Third argument (true) is forced to stop in case of problems
 * return data is bool --> ( if == true no problem else there are problems )
 */
checkGetInputs(['name','last_name'],true,true);
checkPostInputs(['name','last_name'],true,true);
checkHeaderInputs(['name','last_name'],true,true);

//If you set third argument to false use must check it in (if)
if(checkGetInputs(['name','last_name'],true,false)){
//do some thing
}else{
//show error
}

?>
```
# How to create random token
```php
<?php
require_once 'StarOfLife.php';
start("dbName", "userName", "password");

//simple random
$r1=randomHash();
//random hash with custom band and custom length
$r2=randomHash("A-Z,a-z,0-9",128);


//random hash with random band
/** randoms n^n 3^3=27
 * 111 , 222 , 333
 * 112 , 121 , 211
 * 113 , 131 , 311
 * 221 , 212 , 122
 * 311 , 313 , 113
 * 332 , 323 , 233
 * 223 , 232 , 322
 * 123 , 132 , 321
 * 312 , 231 , 213
 */
$r3=randomHashWithBand("123",3);

?>
```


