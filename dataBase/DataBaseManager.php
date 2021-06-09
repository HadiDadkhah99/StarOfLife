<?php

class DataBaseManager
{

    /** @var $pdo PDO */
    private $pdo;
    /** @var $dataBaseHelper DataBaseHelper */
    private $dataBaseHelper;

    public function __construct(string $host, string $username, string $dbName, string $passWord, string $charset = "utf8")
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=$charset", $username, $passWord);
        $this->dataBaseHelper = new DataBaseHelper($this);


    }

    /**
     * Run custom query
     * @return bool|array
     */
    public function customQuery(string $query, array $inputVars, bool $isOutput)
    {

        //prepare sql query
        $statement = $this->pdo->prepare($query);

        //set vars
        foreach ($inputVars as $key => &$var) {

            if (is_int(strpos($key, ":")))
                $statement->bindParam("$key", $var);

            else
                $statement->bindParam(":$key", $var);

        }//end for

        //exec query
        if (!$isOutput) return $statement->execute();


        $statement->execute();

        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (is_array($data))
            return count($data) == 1 ? $data[0] : $data;

        return [];

    }

    /**
     * Insert new data (send object of DataModel)
     * returned data is last inserted id
     */
    public function insert(DataModel $dataModel, WhereQuery $whereQuery = null): int
    {
        $where = "";

        //check where
        if (!empty($whereQuery))
            $where = "WHERE " . $whereQuery->getWhereQuery();


        //prepare statement
        $statement = $this->pdo->prepare("INSERT INTO {$dataModel->getTableName()} ({$this->dataBaseHelper->classifyVarsName($dataModel,true)})VALUES({$this->dataBaseHelper->classifyPdoVarsName($dataModel,true)}) $where");

        //**classify variables (bind param(:d0,$var)
        $vars = $this->dataBaseHelper->classifyPdoStatement($dataModel, true);


        //set variables
        foreach ($vars as $key => &$var)
            $statement->bindParam($key, $var);


        //set where vars
        if (!empty($whereQuery)) {
            foreach ($whereQuery->getVars() as $key => &$var)
                $statement->bindParam($key, $var);
        }


        //run query
        $statement->execute();

        //get last inserted id
        return $this->pdo->lastInsertId();
    }

    /**
     * Update data (send object of DataModel)
     * Please set id for data model object (Primary Key)
     */
    public function update(DataModel $dataModel, WhereQuery $whereQuery = null): bool
    {

        //check where
        if (!empty($whereQuery))
            $where = "WHERE " . $whereQuery->getWhereQuery();
        else
            $where = "WHERE {$dataModel->getPrimaryKeyName()}={$dataModel->getPrimaryKeyValue()}";


        //prepare statement
        $statement = $this->pdo->prepare("UPDATE {$dataModel->getTableName()} SET {$this->dataBaseHelper->classifyPdoSetVars($dataModel,true)} $where");


        //**classify variables (bind param(:d0,$var)
        $vars = $this->dataBaseHelper->classifyPdoStatement($dataModel, true);

        //set variables
        foreach ($vars as $key => &$var)
            $statement->bindParam($key, $var);


        //set where vars
        if (!empty($whereQuery)) {
            foreach ($whereQuery->getVars() as $key => &$var)
                $statement->bindParam($key, $var);
        }

        //run query
        return $statement->execute();

    }

    /**
     * Delete data (send object of DataModel)
     * Please set id for data model object (Primary Key)
     */
    public function delete(DataModel $dataModel, WhereQuery $whereQuery = null): bool
    {

        //check where
        if (!empty($whereQuery))
            $where = "WHERE " . $whereQuery->getWhereQuery();
        else
            $where = "WHERE {$dataModel->getPrimaryKeyName()}={$dataModel->getPrimaryKeyValue()}";


        //prepare statement
        $statement = $this->pdo->prepare("DELETE FROM {$dataModel->getTableName()} $where");

        //set where vars
        if (!empty($whereQuery)) {
            foreach ($whereQuery->getVars() as $key => &$var)
                $statement->bindParam($key, $var);
        }

        //run query
        return $statement->execute();

    }

    /**
     * Get special data (send object of DataModel)
     * Please set id for data model object (Primary Key)
     * The returned data is object of DataModel (if data is not in DataBase so the returned data is null)
     */
    public function get(DataModel $dataModel, DataModel $returnModel, WhereQuery $whereQuery = null): ?DataModel
    {

        //class name
        $className = $dataModel->name();

        if (!empty($whereQuery))
            $where = " {$whereQuery->getWhereQuery()} ";
        else
            $where = " WHERE {$dataModel->name()}.{$dataModel->getPrimaryKeyName()} = {$dataModel->getPrimaryKeyValue()} ";


        //prepare statement
        $statement = $this->pdo->prepare("SELECT {$this->dataBaseHelper->getSelectionQuery(new $className,$whereQuery)} FROM {$dataModel->getTableName()} $where");


        //set where vars
        if (!empty($whereQuery)) {
            foreach ($whereQuery->getVars() as $key => &$var)
                $statement->bindParam($key, $var);
        }


        //run query
        $statement->execute();

        //select data
        $res = $statement->fetch(PDO::FETCH_ASSOC);


        //check data is not empty
        if (is_array($res)) {

            //return data
            return $this->setModelVars($className, $res, $returnModel, $whereQuery);

        }

        return null;
    }

    /**
     * Get all data
     * The returned data is array of DataModel (if data is not in DataBase so the returned data is null)
     */
    public function getAll(DataModel $dataModel, DataModel $returnModel, WhereQuery $whereQuery = null): ?array
    {

        if (!empty($whereQuery))
            $where = $whereQuery->getWhereQuery();
        else
            $where = "";


        //prepare statement
        $statement = $this->pdo->prepare("SELECT {$this->dataBaseHelper->getSelectionQuery($dataModel,$whereQuery)} FROM {$dataModel->getTableName()} $where");


        //set where vars
        if (!empty($whereQuery)) {
            foreach ($whereQuery->getVars() as $key => &$var)
                $statement->bindParam($key, $var);
        }


        //run query
        $statement->execute();
        //get all data
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);


        //check data is not empty
        if (is_array($data)) {

            //result data
            $resultData = [];

            foreach ($data as $res) {

                //put on array
                $resultData[] = $this->setModelVars($dataModel->name(), $res, $returnModel, $whereQuery);
            }

            //return data
            return $resultData;
        }

        return null;
    }

    function setModelVars(string $className, array $res, DataModel $returnModel, WhereQuery $whereQuery = null): DataModel
    {
        if (empty($whereQuery))
            $whereQuery = new WhereQuery();

        //create object off DataModel class name
        $object = new $returnModel ();

        //reflection for set data for all of class vars
        $reflectionClass = new ReflectionClass($returnModel->name());

        //set vars
        foreach ($object->getAllVars() as $key => $var) {

            //ignore columns
            if (strpos($object->varAnnotation($key), Annotation::IGNORE))
                continue;

            if (!empty($columnName = $object->getColumnName($key))) {

                if (strpos($columnName, "."))
                    $resKey = str_replace(".", "_", $columnName);
                else
                    $resKey = !empty($this->dataBaseHelper->findVarInClasses(new $className, $key)) ? "{$object->getTableName()}_{$columnName}" : "{$this->dataBaseHelper->findVarInClasses($whereQuery,$key)->getTableName()}_{$columnName}";

            } else

                $resKey = !empty($this->dataBaseHelper->findVarInClasses(new $className, $key)) ? "{$object->getTableName()}_{$key}" : "{$this->dataBaseHelper->findVarInClasses($whereQuery,$key)->getTableName()}_{$key}";


            $reflectionClass->getProperty($key)->setValue($object, $res[$resKey]);


        }

        return $object;
    }


}