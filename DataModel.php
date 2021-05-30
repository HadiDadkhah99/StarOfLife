<?php


class DataModel
{
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getVar(string $varName)
    {
        return get_object_vars($this)[$varName];

    }

    public function name(): string
    {
        return get_class($this);
    }

    public function getAllVars_string(): array
    {
        $res = [];
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value)
            $res[] = "$key";

        return $res;
    }


    public function getAllVars(): array
    {
        return get_object_vars($this);
    }


}