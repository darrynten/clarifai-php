<?php
/**
 * Clarifai Models
 *
 * @category Model
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

/**
 * Multiple Clarifai Models
 *
 * @package Clarifai
 */
class Models
{
    /**
     * A collection of models
     *
     * @var array $models
     */
    private $models;

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
     * @param array $config The config for the model
     * @param array $data The data for the model
     */
    public function __construct(array $config, array $data)
    {
        $this->config = $config;
        $this->rawData = $data;

        foreach ($data as $model) {
            $this->models[] = new Model($config, $model);
        }
    }

    // init
    // predict
    // train
    // list
    // create
    // get
    // update
    // delete
    // search
    //
    // mergeConcepts
    // deleteConcepts
    // overwriteConcepts
    //
    // getVersion
    // getVersions
    // getOutputInfo
    //
}
