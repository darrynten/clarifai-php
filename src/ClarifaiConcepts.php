<?php

namespace Clarifai;

use Clarifai\Concept;

/**
 * Single Clarifai Concept
 *
 * @package Clarifai
 */
class ClarifaiConcepts {
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
   * @param array $config
   *   The config for the concept
   * @param array $data
   *   The data for the concept
   */
  public function __construct(Array $config, Array $data) {
    $this->config = $config;
    $this->rawData = $data;

    foreach ($data as $concept) {
      $this->concepts[] = new Concept($config, $concept);
    }
  }

  // list
  // get
  // create
  // search
  //
}

