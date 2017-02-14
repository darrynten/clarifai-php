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

use DarrynTen\Clarifai\Request\RequestHandler;

/**
 * Single Clarifai Input
 *
 * @package Clarifai
 */
class Input extends BaseRepository
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
     * @param RequestHandler $request
     * @param array $config The config for the input
     * @param array $data The data for the input
     */
    public function __construct(RequestHandler $request, array $config, array $data)
    {
        parent::__construct($request);
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
