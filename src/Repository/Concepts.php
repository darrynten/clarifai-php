<?php
/**
 * Clarifai Concepts
 *
 * @category Concept
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

use DarrynTen\Clarifai\Request\RequestHandler;

/**
 * Single Clarifai Concept
 *
 * @package Clarifai
 */
class Concepts extends BaseRepository
{
    /**
     * A collection of concepts
     *
     * @var array $concepts
     */
    private $concepts;

    /**
     * The config
     *
     * @var object $config
     */
    private $config;

    /**
     * The raw data
     *
     * @var array $rawData
     */
    private $rawData;

    /**
     * Constructor
     *
     * @param RequestHandler $request
     * @param array $config The config for the concept
     * @param array $data The data for the concept
     */
    public function __construct(RequestHandler $request, array $config, array $data)
    {
        parent::__construct($request);
        $this->config = $config;
        $this->rawData = $data;

        foreach ($data as $concept) {
            $this->concepts[] = new Concept($this->getRequest(), $config, $concept);
        }
    }

    // list
    // get
    // create
    // search
    //
}
