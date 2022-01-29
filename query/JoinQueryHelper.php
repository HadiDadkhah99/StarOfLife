<?php


class JoinQueryHelper
{


    /** @var $whereQuery WhereQuery */
    private $whereQuery;
    /** @var $joinQuery string */
    private $joinQuery = "";


    public function __construct(WhereQuery $whereQuery)
    {
        $this->whereQuery = $whereQuery;
    }


    public function join(DataModel $dataModel, string $as = null): JoinQueryHelper
    {
        //add to joined tables
        $temp = $this->whereQuery->getJoinedTables();
        $temp[] = $dataModel->name();
        $this->whereQuery->setJoinedTables($temp);

        $this->joinQuery .= empty($as) ? " JOIN {$dataModel->getTableName()} ON " : " JOIN {$dataModel->getTableName()} as $as ON ";

        return $this;
    }

    public function leftJoin(DataModel $dataModel, string $as = null): JoinQueryHelper
    {
        //add to joined tables
        $temp = $this->whereQuery->getJoinedTables();
        $temp[] = $dataModel->name();
        $this->whereQuery->setJoinedTables($temp);
        
        $this->joinQuery .= empty($as) ? " LEFT JOIN {$dataModel->getTableName()} ON " : " JOIN {$dataModel->getTableName()} as $as ON ";

        return $this;
    }

    /**
     * @param string $var
     * @param $value mixed
     * @throws Exception
     */
    public function onEqual(string $var, $value, bool $not = false): JoinQueryHelper
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
    public function onNotEqual(string $var, $value): JoinQueryHelper
    {
        $this->onEqual($var, $value, true);

        return $this;
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

    public function and(): JoinQueryHelper
    {


        $this->joinQuery .= " AND ";

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