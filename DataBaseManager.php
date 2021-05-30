<?php

class DataBaseManager
{

    private PDO $pdo;
    private DataBaseHelper $dataBaseHelper;

    public function __construct(string $host, string $username, string $dbName, string $passWord)
    {
        $this->pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $passWord);
        $this->dataBaseHelper = new DataBaseHelper($this);
    }


    /**
     * Insert new data (send object of DataModel)
     * returned data is last inserted id
     */
    public function insert(DataModel $dataModel): int
    {

        //prepare statement
        $statement = $this->pdo->prepare("INSERT INTO {$dataModel->name()} ({$this->dataBaseHelper->classifyModel($dataModel)})VALUES({$this->dataBaseHelper->pdoClassifyModel($dataModel)})");

        //**classify variables (bind param(:d0,$var)
        $vars = $this->dataBaseHelper->classifyPrepareStatement($dataModel);

        //set variables
        foreach ($vars as $key => $var)
            $statement->bindParam($key, $var);

        //run query
        $statement->execute();

        //get last inserted id
        return $this->pdo->lastInsertId();
    }

    /**
     * Update data (send object of DataModel)
     * Please set id for data model object (Primary Key)
     */
    public function update(DataModel $dataModel, string $whereQuery = null): bool
    {
        //check where query
        if (empty($whereQuery))
            $whereQuery = "id={$dataModel->id}";

        //prepare statement
        $statement = $this->pdo->prepare("UPDATE {$dataModel->name()} SET {$this->dataBaseHelper->classifySetVar($dataModel)} WHERE $whereQuery");

        //**classify variables (bind param(:d0,$var)
        $vars = $this->dataBaseHelper->classifyPrepareStatement($dataModel);

        //set variables
        foreach ($vars as $key => $var)
            $statement->bindParam($key, $var);

        //run query
        return $statement->execute();

    }

    /**
     * Delete data (send object of DataModel)
     * Please set id for data model object (Primary Key)
     */
    public function delete(DataModel $dataModel, string $whereQuery = null): bool
    {

        //check where query
        if (empty($whereQuery))
            $whereQuery = "id={$dataModel->id}";

        //prepare statement
        $statement = $this->pdo->prepare("DELETE FROM {$dataModel->name()} WHERE $whereQuery");

        //run query
        return $statement->execute();

    }

    /**
     * Get special data (send object of DataModel)
     * Please set id for data model object (Primary Key)
     * The returned data is object of DataModel (if data is not in DataBase so the returned data is null)
     */
    public function get(DataModel $dataModel, string $whereQuery = null): ?DataModel
    {

        //check where query
        if (empty($whereQuery))
            $whereQuery = "id={$dataModel->id}";

        //prepare statement
        $statement = $this->pdo->prepare("SELECT * FROM {$dataModel->name()} WHERE $whereQuery");

        //run query
        $statement->execute();

        //select data
        $data = $statement->fetch(PDO::FETCH_ASSOC);


        //check data is not empty
        if (is_array($data)) {

            //get data model class name
            $classname = $dataModel->name();
            //create new object of data model class
            $object = new $classname($dataModel->id);

            //reflection for set data for all of class vars
            $reflectionClass = new ReflectionClass($classname);

            //set vars
            foreach ($dataModel->getAllVars() as $key => $var)
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
    public function getAll(string $className, string $whereQuery = null): ?array
    {
        //check where query
        if (!empty($whereQuery))
            $whereQuery = "WHERE $whereQuery";

        //prepare statement
        $statement = $this->pdo->prepare("SELECT * FROM $className $whereQuery");
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
                $object = new $className(intval($res['id']));

                //reflection for set data for all of class vars
                $reflectionClass = new ReflectionClass($className);

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