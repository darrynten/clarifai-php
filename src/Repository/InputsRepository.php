<?php
/**
 * Clarifai Inputs
 *
 * @category Input
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

use DarrynTen\Clarifai\Request\RequestHandler;

/**
 * Multiple Clarifai Inputs
 *
 * @package Clarifai
 */
class InputsRepository extends BaseRepository
{
    /**
     * A collection of inputs
     *
     * @var array $inputs
     */
    private $inputs;

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
     * @param array $config The config for the inputs
     * @param array $data The inputs
     */
    public function __construct(RequestHandler $request, array $config, array $data)
    {
        parent::__construct($request);
        $this->config = $config;
        $this->rawData = $data;

        foreach ($data as $input) {
            $this->inputs[] = new InputRepository($this->getRequest(), $config, $input);
        }
    }

    // list
    // get
    // create
    // search
    // delete
    // update
    //
    // mergeConcepts
    // deleteConcepts
    // overwriteConcepts
    //
    // getStatus
}
