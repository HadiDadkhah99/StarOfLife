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
        $this->whereQuery .= " $var=:$var ";
        $this->vars[":$var"] = $value;

        return $this;
    }

    /**
     * @param mixed $value
     */
    public function notEqual(string $var, $value): WhereQuery
    {
        $this->whereQuery .= " NOT $var=:$var ";
        $this->vars[":$var"] = $value;

        return $this;
    }

    public function and(): WhereQuery
    {
        $this->whereQuery .= " AND ";

        return $this;
    }

    public function OR(): WhereQuery
    {
        $this->whereQuery .= " OR ";

        return $this;
    }

    public function orderBy(string $var, bool $order = true): WhereQuery
    {
        $AD = $order ? "ASC" : "DESC";

        $this->whereQuery .= " ORDER BY $var $AD ";

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

