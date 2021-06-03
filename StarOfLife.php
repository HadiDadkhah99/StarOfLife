<?php
require_once dirname(__DIR__) . '/StarOfLife/RequestChecker.php';

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
function checkPostInputs(array $inputs, bool $checkEmptyValue = true, bool $printErr = true): void
{
    RequestChecker::checkPostInputs($inputs, $checkEmptyValue, $printErr);
}

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
function get(DataModel $dataModel, WhereQuery $whereQuery = null, DataModel $returnModel = null): ?DataModel
{
    global $dataBase;
    $returnModel = empty($returnModel) ? $dataModel : $returnModel;
    return $dataBase->get($dataModel, $returnModel, $whereQuery);
}

/**
 * Get all data of DataBase
 * @param DataModel|null $returnModel
 */
function getAll(DataModel $dataModel, WhereQuery $whereQuery = null, DataModel $returnModel = null): ?array
{
    global $dataBase;
    $returnModel = empty($returnModel) ? $dataModel : $returnModel;
    return $dataBase->getAll($dataModel->name(), $returnModel, $whereQuery);
}

/**
 * Custom query
 * @return bool|array
 */
function query(string $query, array $inputVars = null)
{
    global $dataBase;
    $inputVars = empty($inputVars) ? [] : $inputVars;

    return $dataBase->customQuery($query, $inputVars, is_int(strpos($query, "SELECT")));

}

function start(string $dbName, string $userName, string $passWord, string $host = "localhost", string $charset = "utf8")
{
    require_once dirname(__DIR__) . '/StarOfLife/Annotation.php';
    require_once dirname(__DIR__) . '/StarOfLife/DataModel.php';
    require_once dirname(__DIR__) . '/StarOfLife/WhereQuery.php';
    require_once dirname(__DIR__) . '/StarOfLife/DataBaseHelper.php';
    require_once dirname(__DIR__) . '/StarOfLife/DataBaseManager.php';

    global $dataBase;
    $dataBase = new DataBaseManager($host, $userName, $dbName, $passWord, $charset);

}