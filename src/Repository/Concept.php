<?php
/**
 * Clarifai Single Concept
 *
 * @category Concept
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

/**
 * Single Clarifai Concept
 *
 * @package Clarifai
 */
class Concept
{
    /**
     * The ID of the concept
     *
     * @var string $conceptId
     */
    private $conceptId;

    /**
     * The name of the concept
     *
     * @var string $conceptName
     */
    private $conceptName;

    /**
     * The date the concept was created at
     *
     * @var string $createdAt
     */
    private $createdAt;

    /**
     * The app ID
     *
     * @var string $appId
     */
    private $appId;

    /**
     * The value
     *
     * @var string|null $value
     */
    private $value = null;

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
     * @param array $config The config for the concept
     * @param array $data The data for the concept
     */
    public function __construct(array $config, array $data)
    {
        $this->conceptId = $data['id'];
        $this->conceptName = $data['name'];
        $this->createdAt = $data['createdAt'];
        $this->appId = $data['appId'];
        $this->value = $data['value'] || null;

        $this->config = $config;
        $this->rawData = $data;
    }
}
