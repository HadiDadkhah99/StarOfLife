<?php


class DataModel
{
    //primary key
    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Get variable value as string or int of float or...
     */
    public function getVar(string $varName)
    {
        return get_object_vars($this)[$varName];

    }

    /**
     * Class name of this object
     */
    public function name(): string
    {
        return get_class($this);
    }

    /**
     * Get all object vars as string like "id,name,..."
     */
    public function getAllVars_string(): array
    {
        $res = [];
        $vars = get_object_vars($this);
        foreach ($vars as $key => $value)
            $res[] = "$key";

        return $res;
    }


    /**
     * Get all vars as array
     */
    public function getAllVars(): array
    {
        return get_object_vars($this);
    }


}