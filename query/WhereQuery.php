<?php
require_once dirname(__DIR__) . '/query/WhereQueryController.php';
require_once dirname(__DIR__) . '/query/JoinQueryHelper.php';


class WhereQuery
{

    /** @var $checkUseWhere bool */
    private $checkUseWhere = false;
    /** @var $whereQuery string */
    private $whereQuery = "";
    /** @var $vars array */
    private $vars = [];
    /** @var $joinedTables array */
    private $joinedTables = [];

    //controller
    /** @var $controller WhereQueryController */
    private $controller;
    //join helper
    /** @var $joinQueryHelper JoinQueryHelper */
    private $joinQueryHelper;


    /**
     * WhereQuery constructor.
     */
    public function __construct()
    {
        $this->controller = new WhereQueryController($this);
        $this->joinQueryHelper = new JoinQueryHelper($this);
    }


    /**
     * @param mixed $value
     */
    public function equal(string $var, $value): WhereQuery
    {
        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
        $this->whereQuery .= !$this->checkUseWhere ? " WHERE " : "";
        $this->whereQuery .= " $var=:$e ";
        $this->vars[":$e"] = $value;

        $this->checkUseWhere = true;
        return $this;
    }

    /**
     * @param mixed $value
     */
    public function notEqual(string $var, $value): WhereQuery
    {
        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
        $this->whereQuery .= !$this->checkUseWhere ? " WHERE " : "";
        $this->whereQuery .= " NOT $var=:$e ";
        $this->vars[":$e"] = $value;

        $this->checkUseWhere = true;

        return $this;
    }


    /**
     * @param bool $orEqual
     * @param int|float $value
     */
    public function greatThan(string $var, $value, bool $orEqual = false): WhereQuery
    {
        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
        $this->whereQuery .= !$this->checkUseWhere ? " WHERE " : "";
        $this->whereQuery .= $orEqual ? " $var>=:$e " : " $var>:$e ";
        $this->vars[":$e"] = $value;

        $this->checkUseWhere = true;

        return $this;
    }

    /**
     * @param bool $orEqual
     * @param int|float $value
     */
    public function lessThan(string $var, $value, bool $orEqual = false): WhereQuery
    {
        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
        $this->whereQuery .= !$this->checkUseWhere ? " WHERE " : "";
        $this->whereQuery .= $orEqual ? " $var<=:$e " : " $var<:$e ";
        $this->vars[":$e"] = $value;

        $this->checkUseWhere = true;

        return $this;
    }


    public function like(string $var, string $like): WhereQuery
    {
        //control
        $this->controller->likeControl();
        $this->whereQuery .= !$this->checkUseWhere ? " WHERE " : "";
        $this->whereQuery .= " $var LIKE '$like' ";

        $this->checkUseWhere = true;

        return $this;
    }



    public function and(): WhereQuery
    {
        $this->whereQuery .= " AND ";

        return $this;
    }

    public function openAnd(): WhereQuery
    {
        $this->whereQuery .= " AND (";

        return $this;
    }

    public function or(): WhereQuery
    {
        $this->whereQuery .= " OR ";

        return $this;
    }

    public function openOr(): WhereQuery
    {
        $this->whereQuery .= " OR (";

        return $this;
    }

    public function close(): WhereQuery
    {
        $this->whereQuery .= " ) ";

        return $this;
    }


    public function not(): WhereQuery
    {
        $this->whereQuery .= " NOT ";
        return $this;
    }

    /**
     * @throws Exception
     */
    public function orderBy(string $var, bool $asc = true): WhereQuery
    {
        //control
        $this->controller->orderByControl();

        // $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
        $e = $var;
        $AD = $asc ? "ASC" : "DESC";
        $this->whereQuery .= " ORDER BY $e $AD ";


        return $this;
    }


    /**
     * @throws Exception
     */
    public function customOrderBy(string $orderBy): WhereQuery
    {
        //control
        $this->controller->orderByControl();
        $this->whereQuery .= " $orderBy ";
        return $this;
    }

    /**
     * @throws Exception
     */
    public function limit(int $count): WhereQuery
    {
        //control
        $this->controller->limitControl();

        $this->whereQuery .= " LIMIT $count ";


        return $this;
    }


    /**
     * page number start from 0
     * $count is count of page items
     * @throws Exception
     */
    public function page(int $page, int $count): WhereQuery
    {
        //control
        $this->controller->pageControl();

        $this->whereQuery .= " LIMIT " . ($page * $count) . " , $count ";

        return $this;
    }

    /**
     * @param string|null $as
     * @throws Exception
     */
    public function join(DataModel $dataModel, string $as = null): JoinQueryHelper
    {
        return $this->joinQueryHelper->join($dataModel, $as);
    }

    /**
     * @throws Exception
     */
    public function leftJoin(DataModel $dataModel, string $as = null): JoinQueryHelper
    {

        return $this->joinQueryHelper->leftJoin($dataModel, $as);
    }

    public function setWhereQuery(string $whereQuery): void
    {
        $this->whereQuery = $whereQuery;
    }


    public function getWhereQuery(): string
    {
        return $this->whereQuery;
    }


    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @param array $vars
     */
    public function setVars(array $vars): void
    {
        $this->vars = $vars;
    }

    /**
     * @return array
     */
    public function getJoinedTables(): array
    {
        return $this->joinedTables;
    }

    /**
     * @param array $joinedTables
     */
    public function setJoinedTables(array $joinedTables): void
    {
        $this->joinedTables = $joinedTables;
    }

}

