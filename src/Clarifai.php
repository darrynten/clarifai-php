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
     * @param $config
     * @param $data
     *
     * @return Repository\Model
     */
    public function getModel($config, $data)
    {
        return new Repository\Model($this->getRequest(), $config, $data);
    }

    /**
     * @param $config
     * @param $data
     *
     * @return Repository\Models
     */
    public function getModels($config, $data)
    {
        return new Repository\Models($this->getRequest(), $config, $data);
    }

    /**
     * @param $config
     * @param $data
     *
     * @return Repository\Concept
     */
    public function getConcept($config, $data)
    {
        return new Repository\Concept($this->getRequest(), $config, $data);
    }

    /**
     * @param $config
     * @param $data
     *
     * @return Repository\Concepts
     */
    public function getConcepts($config, $data)
    {
        return new Repository\Concepts($this->getRequest(), $config, $data);
    }

    /**
     * @param $config
     * @param $data
     *
     * @return Repository\Input
     */
    public function getInput($config, $data)
    {
        return new Repository\Input($this->getRequest(), $config, $data);
    }

    /**
     * @param $config
     * @param $data
     *
     * @return Repository\Inputs
     */
    public function getInputs($config, $data)
    {
        return new Repository\Inputs($this->getRequest(), $config, $data);
    }
}
