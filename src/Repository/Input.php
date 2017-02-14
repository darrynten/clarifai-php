<?php
/**
 * Clarifai Input
 *
 * @category Input
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

/**
 * Single Clarifai Input
 *
 * @package Clarifai
 */
class Input
{

    /**
     * The ID of the input
     *
     * @var string $inputId
     */
    private $inputId;

    /**
     * The date the input was created at
     *
     * @var string $createdAt
     */
    private $createdAt;

    /**
     * The image URL
     *
     * @var string $imageUrl
     */
    private $imageUrl;

    /**
     * The concepts associated with this input
     *
     * @var Concepts $concepts
     */
    private $concepts;

    /**
     * The input score
     *
     * @var float $score
     */
    private $score;

    /**
     * The metadata
     *
     * @var array $metaData
     */
    private $metaData;

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
     * @param array $config The config for the input
     * @param array $data The data for the input
     */
    public function __construct(array $config, array $data)
    {
        $this->inputId = $data['id'];
        $this->createdAt = $data['createdAt'];
        $this->imageUrl = $data['imageUrl'];

        // TODO
        // $this->concepts = new Concepts($config, $data['concepts']);

        $this->score = $data['score'];
        $this->metaData = $data['metaData'];

        // Geo?

        $this->config = $config;
        $this->rawData = $data;
    }

    // mergeConcepts
    // deleteConcepts
    // overwriteConcepts
    //
    // update
}
