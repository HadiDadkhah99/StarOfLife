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


    public function insert(DataModel $dataModel): int
    {

        $statement = $this->pdo->prepare("INSERT INTO {$dataModel->name()} ({$this->dataBaseHelper->classifyModel($dataModel)})VALUES({$this->dataBaseHelper->pdoClassifyModel($dataModel)})");

        $vars = $this->dataBaseHelper->classifyPrepareStatement($dataModel);
        foreach ($vars as $key => $var)
            $statement->bindParam($key, $var);
        $statement->execute();

        return $this->pdo->lastInsertId();
    }

    public function update(DataModel $dataModel, string $whereQuery = null): bool
    {
        if (empty($whereQuery))
            $whereQuery = "id={$dataModel->id}";


        $statement = $this->pdo->prepare("UPDATE {$dataModel->name()} SET {$this->dataBaseHelper->classifySetVar($dataModel)} WHERE $whereQuery");

        $vars = $this->dataBaseHelper->classifyPrepareStatement($dataModel);
        foreach ($vars as $key => $var)
            $statement->bindParam($key, $var);

        return $statement->execute();

    }


    public function delete(DataModel $dataModel, string $whereQuery = null): bool
    {

        if (empty($whereQuery))
            $whereQuery = "id={$dataModel->id}";

        $statement = $this->pdo->prepare("DELETE FROM {$dataModel->name()} WHERE $whereQuery");
        return $statement->execute();

    }

    public function get(DataModel $dataModel, string $whereQuery = null): ?DataModel
    {

        if (empty($whereQuery))
            $whereQuery = "id={$dataModel->id}";

        $statement = $this->pdo->prepare("SELECT * FROM {$dataModel->name()} WHERE $whereQuery");
        $statement->execute();
        $data = $statement->fetch(PDO::FETCH_ASSOC);

        if (is_array($data)) {

            $classname = $dataModel->name();
            $object = new $classname($dataModel->id);
            $reflectionClass = new ReflectionClass($classname);
            foreach ($dataModel->getAllVars() as $key => $var)
                $reflectionClass->getProperty($key)->setValue($object, $data[$key]);


            return $object;
        }

        return null;
    }

    public function getAll(string $className, string $whereQuery = null): ?array
    {
        if (!empty($whereQuery))
            $whereQuery = "WHERE $whereQuery";

        $statement = $this->pdo->prepare("SELECT * FROM $className $whereQuery");
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($data)) {

            $resultData = [];

            foreach ($data as $res) {
                $object = new $className(intval($res['id']));
                $reflectionClass = new ReflectionClass($className);
                foreach ($object->getAllVars() as $key => $var)
                    $reflectionClass->getProperty($key)->setValue($object, $res[$key]);

                $resultData[] = $object;
            }


            return $resultData;
        }

        return null;
    }
}