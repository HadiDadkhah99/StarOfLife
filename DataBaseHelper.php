<?php


class DataBaseHelper
{

    private DataBaseManager $dataBaseManager;

    public function __construct(DataBaseManager $dataBaseManager)
    {
        $this->dataBaseManager = $dataBaseManager;
    }


    public function classifyModel(DataModel $dataModel): string
    {
        $res = "";

        $vars = $dataModel->getAllVars_string();
        $length = count($vars);
        for ($i = 0; $i < $length; $i++) {
            if ($i == 0)
                $res .= $vars[$i];
            else
                $res .= ",$vars[$i]";
        }

        return $res;
    }

    public function pdoClassifyModel(DataModel $dataModel): string
    {
        $res = "";

        $vars = $dataModel->getAllVars_string();
        $length = count($vars);
        for ($i = 0; $i < $length; $i++) {
            if ($i == 0)
                $res .= ":d$i";
            else
                $res .= ",:d$i";
        }

        return $res;
    }


    public function classifyPrepareStatement(DataModel $dataModel): array
    {
        $res = [];
        $pdoVars = $this->getPdoVars($dataModel);
        $classVars = $dataModel->getAllVars();

        $i = 0;
        foreach ($classVars as $key => $var) {
            $res[$pdoVars[$i]] = $var;
            $i++;
        }

        return $res;
    }

    public function classifySetVar(DataModel $dataModel): string
    {
        $res = "";
        $pdoVars = $this->getPdoVars($dataModel);
        $classVars = $dataModel->getAllVars();

        $i = 0;
        foreach ($classVars as $key => $var) {

            if ($i == 0)
                $res .= "$var=$pdoVars[$i]";
            else
                $res .= ",$var=$pdoVars[$i]";


            $i++;
        }

        return $res;
    }

    private function getPdoVars(DataModel $dataModel): array
    {
        $res = [];

        $vars = $dataModel->getAllVars();
        $length = count($vars);
        for ($i = 0; $i < $length; $i++)
            $res[] = ":d$i";

        return $res;
    }
}