<?php


class WhereQueryController
{

    private WhereQuery $whereQuery;

    private bool $useLimit = false;
    private bool $usePage = false;
    private bool $useOrderBy = false;

    /**
     * @param WhereQuery $whereQuery
     */
    public function __construct(WhereQuery $whereQuery)
    {
        $this->whereQuery = $whereQuery;
    }


    /**
     * @throws Exception
     */
    public function pageControl(): void
    {

        if ($this->usePage)
            throw new Exception("Err in page control : Delicate page() method !");
        else if ($this->useLimit)
            throw new Exception("Err in page control : You don't have allow use limit() with page() !");

        //use
        $this->usePage = true;
    }

    /**
     * @throws Exception
     */
    public function limitControl(): void
    {

        if ($this->useLimit)
            throw new Exception("Err in page control : Delicate limit() method !");
        else if ($this->usePage)
            throw new Exception("Err in page control : You don't have allow use limit() with page() !");

        //use
        $this->useLimit = true;
    }

    /**
     * @throws Exception
     */
    public function orderByControl(): void
    {

        if ($this->useOrderBy)
            throw new Exception("Err in page control : Delicate orderBy() method !");
        else if ($this->usePage)
            throw new Exception("Err in page control : You don't have allow use page() before orderBy() !");
        else if ($this->useLimit)
            throw new Exception("Err in page control : You don't have allow use limit() before orderBy() !");

        //use
        $this->useOrderBy = true;
    }


}