<?php


class WhereQuery
{
    private string $whereQuery = "";
    private array $vars;


    /**
     * @param mixed $value
     */
    public function equal(string $var, $value): WhereQuery
    {
        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;

        $this->whereQuery .= " $var=:$e ";
        $this->vars[":$e"] = $value;

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function notEqual(string $var, $value): WhereQuery
    {
        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;

        $this->whereQuery .= " NOT $var=:$e ";
        $this->vars[":$e"] = $value;

        return $this;
    }


    /**
     * @param bool $orEqual
     * @param int|float $value
     */
    public function greatThan(string $var, $value, bool $orEqual = false): WhereQuery
    {
        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;

        $this->whereQuery .= $orEqual ? " $var>=:$e " : " $var>:$e ";
        $this->vars[":$e"] = $value;

        return $this;
    }

    /**
     * @param bool $orEqual
     * @param int|float $value
     */
    public function lessThan(string $var, $value, bool $orEqual = false): WhereQuery
    {
        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;

        $this->whereQuery .= $orEqual ? " $var<=:$e " : " $var<:$e ";
        $this->vars[":$e"] = $value;

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

    public function orderBy(string $var, bool $order = true): WhereQuery
    {
        $e = strpos($var, ".") ? explode(".", $var)[1] : $var;

        $AD = $order ? "ASC" : "DESC";

        $this->whereQuery .= " ORDER BY $e $AD ";

        return $this;
    }

    public function limit(int $count): WhereQuery
    {

        $this->whereQuery .= " LIMIT $count ";

        return $this;
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

}

