<?php

namespace DarrynTen\Clarifai\Entity;

/**
 * Clarifai Pagination
 *
 * @package Clarifai
 */
class Pagination
{
    /**
     * Number of the Page
     *
     * @var string $pageNum
     */
    private $pageNum;

    /**
     * Number of entities per page
     *
     * @var string $perPage
     */
    private $perPage;


    /**
     * Pagination constructor.
     *
     * @param $pageNum
     * @param $perPage
     */
    public function __construct($pageNum ,$perPage)
    {
        $this->setPageNum($pageNum);
        $this->setPerPage($perPage);
    }

    /**
     * @return string
     */
    public function getPageNum(): string
    {
        return $this->pageNum;
    }

    /**
     * @param string $pageNum
     *
     * @return $this
     */
    public function setPageNum(string $pageNum)
    {
        $this->pageNum = $pageNum;

        return $this;
    }

    /**
     * @return string
     */
    public function getPerPage(): string
    {
        return $this->perPage;
    }

    /**
     * @param string $perPage
     *
     * @return $this
     */
    public function setPerPage(string $perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

}
