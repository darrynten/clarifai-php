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

namespace DarrynTen\Clarifai;

use DarrynTen\Clarifai\ClarifaiInput;

/**
 * Multiple Clarifai Inputs
 *
 * @package Clarifai
 */
class ClarifaiInputs
{
    /**
     * A collection of inputs
     *
     * @var array $inputs
     */
    protected $inputs;

    /**
     * The config
     *
     * @var object $config
     */
    protected $config;

    /**
     * The raw data
     *
     * @var array $rawData
     */
    protected $rawData;

    /**
     * Constructor
     *
     * @param array $config The config for the inputs
     * @param array $data   The inputs
     */
    public function __construct(Array $config, Array $data)
    {
        $this->config = $config;
        $this->rawData = $data;

        foreach ($data as $input) {
            $this->inputs[] = new ClarifaiInput($config, $input);
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


