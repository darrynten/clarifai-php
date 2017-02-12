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

namespace DarrynTen\Clarifai;

/**
 * Multiple Clarifai Models
 *
 * @package Clarifai
 */
class ClarifaiModels
{
    /**
     * A collection of models
     *
     * @var array $models
     */
    protected $models;

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
     * @param array $config The config for the model
     * @param array $data The data for the model
     */
    public function __construct(array $config, array $data)
    {
        $this->config = $config;
        $this->rawData = $data;

        foreach ($data as $model) {
            $this->models[] = new ClarifaiModel($config, $model);
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
