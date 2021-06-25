<?php
require_once dirname(__DIR__) . '/StarOfLife/util/Annotation.php';
require_once dirname(__DIR__) . '/StarOfLife/model/DataModel.php';
require_once dirname(__DIR__) . '/StarOfLife/query/WhereQuery.php';
require_once dirname(__DIR__) . '/StarOfLife/request/RequestChecker.php';

/* @var $dataBase DataBaseManager */
$dataBase = null;


/**
 * Check inputs of GET request
 */
function checkGetInputs(array $inputs, bool $checkEmptyValue = true, bool $printErr = true): void
{
    RequestChecker::checkGetInputs($inputs, $checkEmptyValue, $printErr);
}

/**
 * Check inputs of POST request
 */
function checkPostInputs(array $inputs, bool $checkEmptyValue = true, bool $printErr = true): ?bool
{
    return RequestChecker::checkPostInputs($inputs, $checkEmptyValue, $printErr);
}


/**
 * Check inputs of Header
 */
function checkHeaderInputs(array $inputs, bool $checkEmptyValue = true, bool $printErr = true): void
{
    RequestChecker::checkHeaderInputs($inputs, $checkEmptyValue, $printErr);
}

/**
 * Insert new data in DataBase
 * @throws Exception
 */
function insert(DataModel $dataModel, WhereQuery $whereQuery = null): int
{
    global $dataBase;
    if (empty($dataBase))
        throw new Exception("Please first start ( dbName , userName , passWord )");

    return $dataBase->insert($dataModel, $whereQuery);
}

/**
 * Insert new data in DataBase
 * @throws Exception
 */
function update(DataModel $dataModel, WhereQuery $whereQuery = null): int
{
    global $dataBase;
    if (empty($dataBase))
        throw new Exception("Please first start ( dbName , userName , passWord )");

    return $dataBase->update($dataModel, $whereQuery);
}

/**
 * Delete  data of DataBase
 * @throws Exception
 */
function delete(DataModel $dataModel, WhereQuery $whereQuery = null): int
{
    global $dataBase;
    if (empty($dataBase))
        throw new Exception("Please first start ( dbName , userName , passWord )");

    return $dataBase->delete($dataModel, $whereQuery);
}

/**
 * Get Special data of DataBase
 * @throws Exception
 */
function get(DataModel $dataModel, WhereQuery $whereQuery = null, DataModel $returnModel = null): ?DataModel
{
    global $dataBase;
    if (empty($dataBase))
        throw new Exception("Please first start ( dbName , userName , passWord )");

    $returnModel = empty($returnModel) ? $dataModel : $returnModel;
    return $dataBase->get($dataModel, $returnModel, $whereQuery);
}

/**
 * Get all data of DataBase
 * @param DataModel|null $returnModel
 * @throws Exception
 */
function getAll(DataModel $dataModel, WhereQuery $whereQuery = null, DataModel $returnModel = null): ?array
{
    global $dataBase;
    if (empty($dataBase))
        throw new Exception("Please first start ( dbName , userName , passWord )");

    $returnModel = empty($returnModel) ? $dataModel : $returnModel;
    return $dataBase->getAll($dataModel, $returnModel, $whereQuery);
}

/**
 * Custom query
 * @return bool|array
 * @throws Exception
 */
function query(string $query, array $inputVars = null)
{

    global $dataBase;
    if (empty($dataBase))
        throw new Exception("Please first start ( dbName , userName , passWord )");

    $inputVars = empty($inputVars) ? [] : $inputVars;

    return $dataBase->customQuery($query, $inputVars, is_int(strpos($query, "SELECT")));

}

function start(string $dbName, string $userName, string $passWord, string $host = "localhost", string $charset = "utf8")
{

    require_once dirname(__DIR__) . '/StarOfLife/dataBase/DataBaseHelper.php';
    require_once dirname(__DIR__) . '/StarOfLife/dataBase/DataBaseManager.php';

    global $dataBase;
    $dataBase = new DataBaseManager($host, $userName, $dbName, $passWord, $charset);

}