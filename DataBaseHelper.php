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
     * Classify DataModel vars as (string) like "id,name,last_name,..."
     */
    public function classifyModel(DataModel $dataModel): string
    {
        //result data
        $res = "";

        //get object vars as array of string
        $vars = $dataModel->getAllVars_string();
        //array length
        $length = count($vars);

        //set data
        for ($i = 0; $i < $length; $i++) {
            if ($i == 0)
                $res .= $vars[$i];
            else
                $res .= ",$vars[$i]";
        }

        return $res;
    }

    /**
     * Classify PDO vars as (string) like ":d0,:d1,:d2,..."
     */
    public function pdoClassifyModel(DataModel $dataModel): string
    {
        //result data
        $res = "";

        //get object vars as array of string
        $vars = $dataModel->getAllVars_string();
        //array length
        $length = count($vars);

        //set data
        for ($i = 0; $i < $length; $i++) {
            if ($i == 0)
                $res .= ":d$i";
            else
                $res .= ",:d$i";
        }

        return $res;
    }


    /**
     * Classify PDO vars as (array) like ":d0,:d1,:d2,..."
     */
    public function classifyPrepareStatement(DataModel $dataModel): array
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
            $res[$pdoVars[$i]] = $var;
            $i++;
        }

        return $res;
    }

    /**
     * Classify PDO vars as (array) like "id=:d0,name=:d1,..."
     */
    public function classifySetVar(DataModel $dataModel): string
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

            if ($i == 0)
                $res .= "$var=$pdoVars[$i]";
            else
                $res .= ",$var=$pdoVars[$i]";


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