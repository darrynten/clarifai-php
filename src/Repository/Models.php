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

use DarrynTen\Clarifai\Request\RequestHandler;

/**
 * Multiple Clarifai Models
 *
 * @package Clarifai
 */
class Models extends BaseRepository
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
     * @param RequestHandler $request
     * @param array $config The config for the model
     * @param array $data The data for the model
     */
    public function __construct(RequestHandler $request, array $config, array $data)
    {
        parent::__construct($request);
        $this->config = $config;
        $this->rawData = $data;

        foreach ($data as $model) {
            $this->models[] = new ModelRepository($this->getRequest(), $config, $model);
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
