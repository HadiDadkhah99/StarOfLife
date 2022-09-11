<?php
require_once dirname(__DIR__) . '/StarOfLife/util/Annotation.php';
require_once dirname(__DIR__) . '/StarOfLife/util/Helper.php';
require_once dirname(__DIR__) . '/StarOfLife/model/DataModel.php';
require_once dirname(__DIR__) . '/StarOfLife/query/WhereQuery.php';
require_once dirname(__DIR__) . '/StarOfLife/request/RequestChecker.php';
require_once dirname(__DIR__) . '/StarOfLife/hash/RandomHash.php';
require_once dirname(__DIR__) . '/StarOfLife/err/Err.php';

/* @var $dataBase DataBaseManager */
$dataBase = null;


/**
 * Check inputs of GET request
 */
function checkGetInputs(array $inputs, bool $checkEmptyValue = true, bool $printErr = true): ?bool
{
    return RequestChecker::checkGetInputs($inputs, $checkEmptyValue, $printErr);
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
function checkHeaderInputs(array $inputs, bool $checkEmptyValue = true, bool $printErr = true): ?bool
{
    return RequestChecker::checkHeaderInputs($inputs, $checkEmptyValue, $printErr);
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
function getAll(DataModel $dataModel, WhereQuery $whereQuery = null, DataModel $returnModel = null): array
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

/**
 * Get count of column
 * @param DataModel $dataModel
 * @param string $columnName
 * @return int|null
 * @throws Exception
 */
function dataCount(DataModel $dataModel, string $columnName, WhereQuery $whereQuery = null): int
{

    global $dataBase;
    if (empty($dataBase))
        throw new Exception("Please first start ( dbName , userName , passWord )");


    return $dataBase->rowCount($dataModel, $columnName, $whereQuery);

}


/**
 *
 */

/**
 * @param string $type
 * @param int $length
 * Default type is : A-Z,a-z,0-9
 * or you can set custom type like:
 *
 * $type=a-z,0-9
 * or
 * $type=A-Z,a-z
 * or
 * $type=0-9
 * or
 * .
 * .
 * .
 */
function randomHash(string $type = '', int $length = 32): string
{
    $hash = new RandomHash($type, $length);
    return $hash->randomHash();
}

/**
 * @param string $band
 * @param int $length
 * Default type is : A-Z,a-z,0-9
 * or you can set custom type like:
 *
 * $type=a-z,0-9
 * or
 * $type=A-Z,a-z
 * or
 * $type=0-9
 * or
 * .
 * .
 * .
 */
function randomHashWithBand(string $band = '', int $length = 32): string
{
    $hash = new RandomHash('', $length);
    $hash->setBand($band);
    return $hash->randomHash();
}


/**
 * For search in DataModel array ;)
 * @throws Exception
 */
function searchInDataModel(array $array, string $propertyName, $search): array
{
    $res = [];


    $index = 0;

    /** @var  $item DataModel */
    foreach ($array as $item) {

        if (!$item instanceof DataModel)
            throw new Exception("This array is not a type of DataModel array!");


        if (empty($item->getVar($propertyName)))
            throw new Exception("This property is not exist!");

        if ($item->getVar($propertyName) == $search)
            $res["$index"] = $item;

        $index++;
    }

    return $res;

}

/**
 * For sort array of DataModel
 * @throws Exception
 */
function sortOnDataModel(array $array, string $propertyName, bool $asc = true): array
{
    if (count($array) <= 1)
        return $array;
    else {
        /** @var  $pivot DataModel */
        $pivot = $array[0];
        if (!$pivot instanceof DataModel)
            throw new Exception("This array is not a type of DataModel array!");

        $left = array();
        $right = array();
        for ($i = 1; $i < count($array); $i++) {

            /** @var  $item DataModel */
            $item = $array[$i];
            if (!$item instanceof DataModel)
                throw new Exception("This array is not a type of DataModel array!");



            if ($asc) {
                if (intval($item->getVar($propertyName)) < intval($pivot->getVar($propertyName)))
                    $left[] = $item;
                else
                    $right[] = $item;
            } else {
                if (intval($item->getVar($propertyName)) > intval($pivot->getVar($propertyName)))
                    $left[] = $item;
                else
                    $right[] = $item;
            }

        }
        return array_merge(sortOnDataModel($left, $propertyName, $asc), array($pivot), sortOnDataModel($right, $propertyName, $asc));
    }
}


/**
 * @param string $dbName
 * @param string $userName
 * @param string $passWord
 * @param string $host
 * @param string $charset
 * Start StarOfLife
 */
function start(string $dbName, string $userName, string $passWord, string $host = "localhost", string $charset = "utf8")
{

    require_once dirname(__DIR__) . '/StarOfLife/dataBase/DataBaseHelper.php';
    require_once dirname(__DIR__) . '/StarOfLife/dataBase/DataBaseManager.php';

    global $dataBase;
    $dataBase = null;
    $dataBase = new DataBaseManager($host, $userName, $dbName, $passWord, $charset);


}