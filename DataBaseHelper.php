<?php


class DataBaseHelper
{

    //data base manager
    private DataBaseManager $dataBaseManager;

    public function __construct(DataBaseManager $dataBaseManager)
    {
        $this->dataBaseManager = $dataBaseManager;
    }

    /**
     * Get class var name from pdo var name
     */
    public function getModelVarName(DataModel $dataModel, string $pdoName): ?string
    {
        //pdo vars as (array) like (:d0,:d2,)
        $pdoVars = $this->getPdoVars($dataModel);

        $i = 0;
        foreach ($dataModel->getAllVars() as $key => $var) {
            if ($pdoVars[$i] == $pdoName)
                return $key;
            $i++;
        }

        return null;
    }


    /**
     * Classify DataModel vars as (string) like "id,name,last_name,..."
     */
    public function classifyVarsName(DataModel $dataModel, bool $withAnnotation = false): string
    {
        //result data
        $res = null;

        //get object vars as array of string
        $vars = $dataModel->getAllVars();
        //array length
        $length = count($vars);

        //set data
        foreach ($vars as $key => $value) {

            if ($withAnnotation and (strpos($dataModel->varAnnotation($key), Annotation::IGNORE) or strpos($dataModel->varAnnotation($key), Annotation::AUTO_INCREMENT)))
                continue;

            if (empty($res))
                $res .= $key;
            else
                $res .= " , $key ";
        }

        return $res;
    }


    /**
     * Classify PDO vars as (string) like ":d0,:d1,:d2,..."
     */
    public function classifyPdoVarsName(DataModel $dataModel, bool $withAnnotation = false): string
    {
        //result data
        $res = "";

        //get object vars as array of string
        $vars = $dataModel->getAllVars();
        //array length
        $length = count($vars);

        //set data
        $i = 0;
        foreach ($vars as $key => $value) {

            if ($withAnnotation and (strpos($dataModel->varAnnotation($key), Annotation::IGNORE) or strpos($dataModel->varAnnotation($key), Annotation::AUTO_INCREMENT)))
                continue;

            if ($i == 0)
                $res .= ":d$i";
            else
                $res .= ",:d$i";

            $i++;
        }

        return $res;
    }


    /**
     * Classify PDO vars as (array) like (:d0=>1,:d1=>'php' , ...)
     */
    public function classifyPdoStatement(DataModel $dataModel, bool $withAnnotation = false): array
    {
        //result data like (:d0=>1,:d1=>'php',...)
        $res = [];

        //get pdo vars (:d0,:d1,:d2,...)
        $pdoVars = $this->getPdoVars($dataModel);
        //get object vars (id=>1,name=>'php',...)
        $classVars = $dataModel->getAllVars();

        //index
        $i = 0;
        //set data
        foreach ($classVars as $key => $var) {

            if ($withAnnotation and (strpos($dataModel->varAnnotation($key), Annotation::IGNORE) or strpos($dataModel->varAnnotation($key), Annotation::AUTO_INCREMENT)))
                continue;

            $res[$pdoVars[$i]] = $var;
            $i++;
        }

        return $res;
    }

    /**
     * Classify PDO vars as (array) like "id=:d0,name=:d1,..."
     */
    public function classifyPdoSetVars(DataModel $dataModel, bool $withAnnotation = false): string
    {
        //result data
        $res = "";
        //get pdo vars (:d0,:d1,:d2,...)
        $pdoVars = $this->getPdoVars($dataModel);
        //get object vars (id=>1,name=>'php',...)
        $classVars = $dataModel->getAllVars();

        //index
        $i = 0;
        //set data
        foreach ($classVars as $key => $var) {

            if ($withAnnotation and (strpos($dataModel->varAnnotation($key), Annotation::IGNORE) or strpos($dataModel->varAnnotation($key), Annotation::AUTO_INCREMENT)))
                continue;


            if ($i == 0)
                $res .= "$key=$pdoVars[$i]";
            else
                $res .= ",$key=$pdoVars[$i]";


            $i++;
        }

        return $res;
    }

    /**
     * Classify PDO vars as (string) like ":d0,:d1,..."
     */
    private function getPdoVars(DataModel $dataModel): array
    {
        //result data
        $res = [];

        //get object vars (id=>1,name=>'php',...)
        $vars = $dataModel->getAllVars();

        //array length
        $length = count($vars);
        //set data
        for ($i = 0; $i < $length; $i++)
            $res[] = ":d$i";

        return $res;
    }
}