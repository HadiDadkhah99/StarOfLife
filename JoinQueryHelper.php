<?php


class JoinQueryHelper
{


    private WhereQuery $whereQuery;
    private string $joinQuery = "";


    public function __construct(WhereQuery $whereQuery)
    {
        $this->whereQuery = $whereQuery;
    }


    public function join(DataModel $dataModel, string $as = null): JoinQueryHelper
    {
        $this->joinQuery .= empty($as) ? " JOIN {$dataModel->name()} ON " : " JOIN {$dataModel->name()} as $as ON ";

        return $this;
    }

    /**
     * @param string $var
     * @param $value mixed
     * @throws Exception
     */
    public function onEqual(string $var, $value, bool $not=false): JoinQueryHelper
    {
        if (!strpos($var, "."))
            throw new Exception("Err in set column name: please set var name like ( test_table.name )");

        //compare column with column
        if (is_string($value) && strpos($value, ".")) {
            $this->joinQuery .= $not ? " NOT $var=$value " : " $var = $value ";
        } //compare column with mixed value
        else {
            $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
            $this->joinQuery .= $not ? " NOT $var=:$e " : " $var=:$e ";
            $vars = $this->whereQuery->getVars();
            $vars[":$e"] = $value;
            $this->whereQuery->setVars($vars);
        }

        return $this;
    }

    /**
     * @param string $var
     * @param $value mixed
     * @throws Exception
     */
    public function onNotEqual(string $var, $value): void
    {
        $this->onEqual($var, $value, true);
    }

    /**
     * @param string $var
     * @param $value mixed
     * @throws Exception
     */
    public function onLessThan(string $var, $value, bool $orEqual): JoinQueryHelper
    {
        if (!strpos($var, "."))
            throw new Exception("Err in set column name: please set var name like ( test_table.name )");

        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;

        $this->joinQuery .= $orEqual ? "  $var<=:$e " : "  $var<:$e ";
        $vars = $this->whereQuery->getVars();
        $vars[":$e"] = $value;
        $this->whereQuery->setVars($vars);

        return $this;
    }


    /**
     * @param string $var
     * @param $value mixed
     * @throws Exception
     */
    public function onGreaterThan(string $var, $value, bool $orEqual): JoinQueryHelper
    {
        if (!strpos($var, "."))
            throw new Exception("Err in set column name: please set var name like ( test_table.name )");

        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;

        $this->joinQuery .= $orEqual ? "  $var>=:$e " : "  $var>:$e ";
        $vars = $this->whereQuery->getVars();
        $vars[":$e"] = $value;
        $this->whereQuery->setVars($vars);

        return $this;
    }


    public function not(): JoinQueryHelper
    {


        $this->joinQuery .= " NOT ";

        return $this;
    }


    /**
     * @throws Exception
     */
    public function commitJoin(): WhereQuery
    {
        if (!(strpos($this->joinQuery, "=") or strpos($this->joinQuery, ">") or strpos($this->joinQuery, "<")))
            throw new Exception("Err in join query: You must set at last one check statement like ( onEqual() )");

        $this->whereQuery->setWhereQuery($this->joinQuery . $this->whereQuery->getWhereQuery());

        return $this->whereQuery;
    }


}