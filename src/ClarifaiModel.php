<?php
/**
 * Clarifai Model
 *
 * @category Model
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai;

/**
 * Single Clarifai Model
 *
 * @package Clarifai
 */
class ClarifaiModel extends Clarifai
{

    /**
     * The ID of the model
     *
     * @var string $modelId
     */
    protected $modelId;

    /**
     * The name of the model
     *
     * @var string $modelName
     */
    protected $modelName;

    /**
     * The date the model was created at
     *
     * @var string $createdAt
     */
    protected $createdAt;

    /**
     * The app ID
     *
     * @var string $appId
     */
    protected $appId;

    /**
     * The output info
     *
     * @var string $outputInfo
     */
    protected $outputInfo;

    /**
     * The model version
     *
     * @var string $modelVersion
     */
    protected $modelVersion;

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
        $this->modelId = $data['id'];
        $this->modelName = $data['name'];
        $this->createdAt = $data['createdAt'];
        $this->appId = $data['appId'];
        $this->value = $data['value'] || null;

        $this->config = $config;
        $this->rawData = $data;
    }

    /**
     * The actual predict call
     *
     * @param array|string $inputs   The inputs
     * @param string|null  $language Language to return results in
     *
     * @return void
     */
    public function predict($inputs, $language = null)
    {
        //
    }

    // This comes later, after predict
    /**
     * Train the model
     *
     * @return void
     */
    public function train()
    {
        //
    }

    /**
     * Update the model
     *
     * @return void
     */
    public function update()
    {
        //
    }

    // mergeConcepts
    // deleteConcepts
    // overwriteConcepts
    //
    // getVersion
    // getVersions
    // getOutputInfo
    // getInputs
}

