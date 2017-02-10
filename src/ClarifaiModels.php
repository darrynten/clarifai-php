<?php

namespace Clarifai;

use Clarifai\Model;

/**
 * Single Clarifai Model
 *
 * @package Clarifai
 */
class ClarifaiModels {
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
   * @param array $config
   *   The config for the model
   * @param array $data
   *   The data for the model
   */
  public function __construct(Array $config, Array $data) {
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


