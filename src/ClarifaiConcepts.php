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

namespace DarrynTen\Clarifai;

use DarrynTen\Clarifai\ClarifaiConcept;

/**
 * Single Clarifai Concept
 *
 * @package Clarifai
 */
class ClarifaiConcepts
{
    /**
     * A collection of concepts
     *
     * @var array $concepts
     */
    protected $concepts;

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
     * @param array $config The config for the concept
     * @param array $data   The data for the concept
     */
    public function __construct(Array $config, Array $data)
    {
        $this->config = $config;
        $this->rawData = $data;

        foreach ($data as $concept) {
            $this->concepts[] = new ClarifaiConcept($config, $concept);
        }
    }

    // list
    // get
    // create
    // search
    //
}

