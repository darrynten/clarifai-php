<?php

namespace Clarifai;

/**
 * Single Clarifai Concept
 *
 * @package Clarifai
 */
class ClarifaiConcept {

  /**
   * The ID of the concept
   *
   * @var string $conceptId
   */
  protected $conceptId;

  /**
   * The name of the concept
   *
   * @var string $conceptName
   */
  protected $conceptName;

  /**
   * The date the concept was created at
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
   * The value
   *
   * @var string|null $value
   */
  protected $value = null;

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
    $this->conceptId = $data['id'];
    $this->conceptName = $data['name'];
    $this->createdAt = $data['createdAt'];
    $this->appId = $data['appId'];
    $this->value = $data['value'] || null;

    $this->config = $config;
    $this->rawData = $data;
  }
}
