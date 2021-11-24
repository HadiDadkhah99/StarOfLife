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

    //edededed
    /** @var $controller WhereQueryController */
    private $controller;
    //join helper
    /** @var $joinQueryHelper JoinQueryHelper */
    private $joinQueryHelper;
    /** @var int */
    private $counter = 0;


    /** Group by query */
    private $groupBy = "";

    /**
     * WhereQuery constructor.
     */
    public function __construct()
    {
        $this->controller = new WhereQueryController($this);
        $this->joinQueryHelper = new JoinQueryHelper($this);
    }

    public static function instance(): WhereQuery
    {
        return new WhereQuery();
    }


    /**
     * @param mixed $value
     */
    public function equal(string $var, $value): WhereQuery
    {

        $this->whereQuery .= !$this->checkUseWhere ? " WHERE " : "";

        if ($value) {
            $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
            $this->whereQuery .= " $var=:{$e}$this->counter";
            $this->vars[":{$e}$this->counter"] = $value;
        } else
            $this->whereQuery .= " $var IS NULL ";


        $this->checkUseWhere = true;
        $this->counter++;
        return $this;
    }

    /**
     * @param mixed $value
     */
    public function notEqual(string $var, $value): WhereQuery
    {
        $this->whereQuery .= !$this->checkUseWhere ? " WHERE " : "";

        if ($value) {
            $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
            $this->whereQuery .= " NOT $var=:{$e}$this->counter ";
            $this->vars[":{$e}$this->counter"] = $value;
        } else
            $this->whereQuery .= " $var IS NOT NULL ";

        $this->checkUseWhere = true;

        $this->counter++;
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
        $this->whereQuery .= $orEqual ? " $var>=:{$e}$this->counter " : " $var>:{$e}$this->counter ";
        $this->vars[":{$e}$this->counter"] = $value;

        $this->checkUseWhere = true;

        $this->counter++;
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
        $this->whereQuery .= $orEqual ? " $var<=:{$e}$this->counter " : " $var<:{$e}$this->counter ";
        $this->vars[":{$e}$this->counter"] = $value;

        $this->checkUseWhere = true;

        $this->counter++;
        return $this;
    }


    public function like(string $var, string $like): WhereQuery
    {
        $this->controller->likeControl();

        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;
        $this->whereQuery .= !$this->checkUseWhere ? " WHERE " : "";
        $this->whereQuery .=" $var LIKE :{$e}$this->counter " ;
        $this->vars[":{$e}$this->counter"] = $like;

        $this->checkUseWhere = true;

        $this->counter++;

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
     * Group by (just simple group by)
     * @throws Exception
     */
    public function groupBy(string $var, bool $asc = true): WhereQuery
    {
        //control
        $this->controller->groupByControl();

        $this->groupBy .= " GROUP BY $var " . ($asc ? " ASC " : " DESC ");

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


    /**
     * Get string query
     * @return string
     */
    public function getWhereQuery(): string
    {
        return $this->whereQuery . $this->groupBy;
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

