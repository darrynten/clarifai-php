<?php
/**
 * Clarifai Concepts
 *
 * @category Repository
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Input;
use DarrynTen\Clarifai\Request\RequestHandler;

/**
 * Class BaseRepository for the same methods collection
 *
 * @package DarrynTen\Clarifai\Repository
 */
abstract class BaseRepository
{
    /**
     * @var RequestHandler
     */
    private $request;

    /**
     * Number of the Page
     *
     * @var string $page
     */
    private $page;

    /**
     * Number of entities per page
     *
     * @var string $perPage
     */
    private $perPage;

    /**
     * Action type for Model Concepts Update
     */
    const CONCEPTS_MERGE_ACTION = 'merge';

    /**
     * Action type for Model Concepts Update
     */
    const CONCEPTS_REMOVE_ACTION = 'remove';

    /**
     * Action type for Model Concepts Update
     */
    const CONCEPTS_OVERWRITE_ACTION = 'overwrite';

    /**
     * BaseRepository constructor.
     *
     * @param RequestHandler $request
     */
    public function __construct(RequestHandler $request)
    {
        $this->request = $request;
    }

    /**
     * @return RequestHandler
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param string $page
     *
     * @return $this
     */
    public function setPage(string $page)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * @return string
     */
    public function getPerPage()
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

    /**
     * @return string
     */
    public function getRequestPageInfo()
    {
        return '?page=' . $this->getPage() . '&per_page=' . $this->getPerPage();
    }

    /**
     * @param $url
     *
     * @return string
     */
    public function getRequestUrl($url)
    {
        if ($this->getPerPage() && $this->getPage()) {
            return $url . $this->getRequestPageInfo();
        }

        return $url;
    }

    /**
     * Parses Request Result and gets Inputs
     *
     * @param $inputResult
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getInputsFromResult($inputResult)
    {
        $input_array = [];

        if (!isset($inputResult['inputs'])) {
            throw new \Exception('Inputs Not Found');
        }

        foreach ($inputResult['inputs'] as $rawInput) {
            $input = new Input($rawInput);
            $input_array[] = $input;
        }

        return $input_array;
    }
}
