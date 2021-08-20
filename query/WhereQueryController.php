<?php


class WhereQueryController
{

    /** @var $whereQuery WhereQuery */
    private $whereQuery;

    /** @var $useLimit bool */
    private $useLimit = false;
    /** @var $usePage bool */
    private $usePage = false;
    /** @var $useOrderBy bool */
    private $useOrderBy = false;
    /** @var $uselike bool */
    private $uselike = false;
    /** @var $useGroupBy bool */
    private $useGroupBy = false;
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

    /**
     * @throws Exception
     */
    public function likeControl(): void
    {

        if ($this->useOrderBy)
            throw new Exception("Err in page control : You don't have allow use orderBy() before like() !");
        else if ($this->usePage)
            throw new Exception("Err in page control : You don't have allow use page() before like() !");
        else if ($this->useLimit)
            throw new Exception("Err in page control : You don't have allow use limit() before like() !");

        //use
        $this->uselike = true;
    }

    /**
     * @throws Exception
     */
    public function groupByControl(): void
    {

        if ($this->useOrderBy)
            throw new Exception("Err in page control : You don't have allow use orderBy() before like() !");
        else if ($this->usePage)
            throw new Exception("Err in page control : You don't have allow use page() before like() !");
        else if ($this->useLimit)
            throw new Exception("Err in page control : You don't have allow use limit() before like() !");

        //use
        $this->useGroupBy = true;
    }

}