<?php
/**
 * Clarifai
 *
 * @category Base
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai;

use DarrynTen\Clarifai\Repository;
use DarrynTen\Clarifai\Request\RequestHandler;

/**
 * Base class for Clarifai manipulation
 *
 * @package Clarifai
 */
class Clarifai
{
    /**
     * @var RequestHandler $clientId
     */
    private $request;

    /**
     * Clarifai constructor
     *
     * @param string $clientId The client ID
     * @param string $clientSecret The client secret
     */
    public function __construct($clientId, $clientSecret)
    {
        $this->request = new RequestHandler($clientId, $clientSecret);
    }

    /**
     * @return RequestHandler
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param array|null $config
     * @param array|null $data
     *
     * @return Repository\ModelRepository
     */
    public function getModelRepository($config = null, $data = null)
    {
        return new Repository\ModelRepository($this->getRequest(), $config, $data);
    }

    /**
     * @param $config
     * @param $data
     *
     * @return Repository\InputRepository
     */
    public function getInputRepository($config = null, $data = null)
    {
        return new Repository\InputRepository($this->getRequest(), $config, $data);
    }

    /**
     * @param $config
     * @param $data
     *
     * @return Repository\SearchInputRepository
     */
    public function getSearchInputRepository($config = null, $data = null)
    {
        return new Repository\SearchInputRepository($this->getRequest(), $config, $data);
    }

    /**
     * @param $config
     * @param $data
     *
     * @return Repository\SearchModelRepository
     */
    public function getSearchModelRepository($config = null, $data = null)
    {
        return new Repository\SearchModelRepository($this->getRequest(), $config, $data);
    }
}
