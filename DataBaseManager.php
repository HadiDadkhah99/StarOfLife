<?php
require_once 'StarOfLife.php';

class DataBaseManager
{

    private PDO $pdo;
    private DataBaseHelper $dataBaseHelper;

    public function __construct(string $host, string $username, string $dbName, string $passWord, string $charset = "utf8")
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=$charset", $username, $passWord);
        $this->dataBaseHelper = new DataBaseHelper($this);
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
        $statement = $this->pdo->prepare("INSERT INTO {$dataModel->name()} ({$this->dataBaseHelper->classifyVarsName($dataModel,true)})VALUES({$this->dataBaseHelper->classifyPdoVarsName($dataModel,true)}) $where");

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
        $statement = $this->pdo->prepare("UPDATE {$dataModel->name()} SET {$this->dataBaseHelper->classifyPdoSetVars($dataModel,true)} $where");


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
        $statement = $this->pdo->prepare("DELETE FROM {$dataModel->name()} $where");

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

        if (!empty($whereQuery))
            $where = " {$whereQuery->getWhereQuery()} ";
        else
            $where = " WHERE {$dataModel->name()}.{$dataModel->getPrimaryKeyName()} = {$dataModel->getPrimaryKeyValue()} ";


        //prepare statement
        $statement = $this->pdo->prepare("SELECT * FROM {$dataModel->name()} $where");

        //set where vars
        if (!empty($whereQuery)) {
            foreach ($whereQuery->getVars() as $key => &$var)
                $statement->bindParam($key, $var);
        }



        //run query
        $statement->execute();

        //select data
        $data = $statement->fetch(PDO::FETCH_ASSOC);


        //check data is not empty
        if (is_array($data)) {

            //get data model class name
            $classname = $returnModel->name();
            //create new object of data model class
            $object = new $classname($dataModel->id);

            //reflection for set data for all of class vars
            $reflectionClass = new ReflectionClass($classname);

            //set vars
            foreach ($returnModel->getAllVars() as $key => $var)
                $reflectionClass->getProperty($key)->setValue($object, $data[$key]);

            //return data
            return $object;
        }

        return null;
    }

    /**
     * Get all data
     * The returned data is array of DataModel (if data is not in DataBase so the returned data is null)
     */
    public function getAll(string $className, DataModel $returnModel, WhereQuery $whereQuery = null): ?array
    {

        if (!empty($whereQuery))
            $where = $whereQuery->getWhereQuery();
        else
            $where = "";


        //prepare statement
        $statement = $this->pdo->prepare("SELECT * FROM $className $where");


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
                //create object off DataModel class name
                $object = new $returnModel (intval($res['id']));

                //reflection for set data for all of class vars
                $reflectionClass = new ReflectionClass($returnModel->name());

                //set vars
                foreach ($object->getAllVars() as $key => $var)
                    $reflectionClass->getProperty($key)->setValue($object, $res[$key]);

                //put on array
                $resultData[] = $object;
            }

            //return data
            return $resultData;
        }

        return null;
    }
}