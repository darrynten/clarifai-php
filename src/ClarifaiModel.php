<?php

namespace Clarifai;

/**
 * Single Clarifai Model
 *
 * @package Clarifai
 */
class ClarifaiModel extends Clarifai {

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
   * @param array $config
   *   The config for the concept
   * @param array $data
   *   The data for the concept
   */
  public function __construct(Array $config, Array $data) {
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
   * @param array|string $inputs
   *   The inputs
   *   @param array $inputs['image']
   *     The image
   *     @param string $inputs['image']['url']
   *       URL or base64 encoded image
   *     @param array $inputs['image']['crop']
   *       Array containing crop info - top, left, bottom and right
   * @param string|null $language
   *   The language to return results in, e.g. en, de
   */
  public function predict($inputs, $language = null) {
    //
  }

  // This comes later, after predict
  public function train() {
    //
  }

  // mergeConcepts
  // deleteConcepts
  // overwriteConcepts
  // update
  // getVersion
  // getVersions
  // getOutputInfo
  // getInputs
}

