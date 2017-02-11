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

namespace DarrynTen\Clarifai;

/**
 * Single Clarifai Input
 *
 * @package Clarifai
 */
class ClarifaiInput extends Clarifai
{

    /**
     * The ID of the input
     *
     * @var string $inputId
     */
    protected $inputId;

    /**
     * The date the input was created at
     *
     * @var string $createdAt
     */
    protected $createdAt;

    /**
     * The image URL
     *
     * @var string $imageUrl
     */
    protected $imageUrl;

    /**
     * The concepts associated with this input
     *
     * @var Concepts $concepts
     */
    protected $concepts;

    /**
     * The input score
     *
     * @var float $score
     */
    protected $score;

    /**
     * The metadata
     *
     * @var array $metaData
     */
    protected $metaData;

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
     * @param array $config The config for the input
     * @param array $data   The data for the input
     */
    public function __construct(Array $config, Array $data)
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

