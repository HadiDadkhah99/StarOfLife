<?php
require_once dirname(__DIR__) . '/StarOfLife/WhereQueryController.php';
require_once dirname(__DIR__) . '/StarOfLife/JoinQueryHelper.php';


class WhereQuery
{

    private bool $checkUseWhere = false;
    private string $whereQuery = "";
    private array $vars = [];

    //controller
    private WhereQueryController $controller;
    //join helper
    private JoinQueryHelper $joinQueryHelper;


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


    public function and(): WhereQuery
    {
        $this->whereQuery .= " AND ";

        return $this;
    }

    public function or(): WhereQuery
    {
        $this->whereQuery .= " OR ";

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

        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
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
     * @throws Exception
     */
    public function join(DataModel $dataModel, string $as = null): JoinQueryHelper
    {
        return $this->joinQueryHelper->join($dataModel, $as);
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

}

