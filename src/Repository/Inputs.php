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

/**
 * Multiple Clarifai Inputs
 *
 * @package Clarifai
 */
class Inputs
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
     * @param array $config The config for the inputs
     * @param array $data The inputs
     */
    public function __construct(array $config, array $data)
    {
        $this->config = $config;
        $this->rawData = $data;

        foreach ($data as $input) {
            $this->inputs[] = new Input($config, $input);
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
