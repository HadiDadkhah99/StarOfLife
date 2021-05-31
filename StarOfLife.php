<?php


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

/**
 * Delete  data of DataBase
 */
function delete(DataModel $dataModel, WhereQuery $whereQuery = null): int
{
    global $dataBase;
    return $dataBase->delete($dataModel, $whereQuery);
}

/**
 * Get Special data of DataBase
 */
function get(DataModel $dataModel, WhereQuery $whereQuery = null): ?DataModel
{
    global $dataBase;
    return $dataBase->get($dataModel, $whereQuery);
}

/**
 * Get all data of DataBase
 *
 */
function getAll(DataModel $dataModel, WhereQuery $whereQuery = null): ?array
{
    global $dataBase;
    return $dataBase->getAll($dataModel->name(), $whereQuery);
}

function start(string $dbName, string $userName, string $passWord, string $host = "localhost", string $charset = "utf8")
{
    require_once dirname(__DIR__) . '/StarOfLife/DataModel.php';
    require_once dirname(__DIR__) . '/StarOfLife/WhereQuery.php';
    require_once dirname(__DIR__) . '/StarOfLife/Annotation.php';
    require_once dirname(__DIR__) . '/StarOfLife/DataBaseHelper.php';
    require_once dirname(__DIR__) . '/StarOfLife/DataBaseManager.php';

    global $dataBase;
    $dataBase = new DataBaseManager($host, $userName, $dbName, $passWord, $charset);

}