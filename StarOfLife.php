<?php

require_once dirname(__DIR__) . '/StarOfLife/DataModel.php';
require_once dirname(__DIR__) . '/StarOfLife/WhereQuery.php';
require_once dirname(__DIR__) . '/StarOfLife/Annotation.php';
require_once dirname(__DIR__) . '/StarOfLife/DataBaseHelper.php';
require_once dirname(__DIR__) . '/StarOfLife/DataBaseManager.php';

/* @var $dataBase DataBaseManager */
$dataBase = null;

/**
 * Insert new data in DataBase
 */
function insert(DataModel $dataModel, WhereQuery $whereQuery = null): int
{
    global $dataBase;
    return $dataBase->insert($dataModel, $whereQuery);
}

/**
 * Insert new data in DataBase
 */
function update(DataModel $dataModel, WhereQuery $whereQuery = null): int
{
    global $dataBase;
    return $dataBase->update($dataModel, $whereQuery);
}


function start(string $host, string $userName, string $dbName, string $passWord)
{
    global $dataBase;
    $dataBase = new DataBaseManager($host, $userName, $dbName, $passWord);

}