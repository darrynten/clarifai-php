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
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

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
    public function __construct(RequestHandler $request, array $config = null, array $data = null)
    {
        parent::__construct($request);
        if (!empty($data)) {
            $this->inputId = $data['id'];
            $this->createdAt = $data['createdAt'];
            $this->imageUrl = $data['imageUrl'];

            // TODO
            // $this->concepts = new Concepts($config, $data['concepts']);

            $this->score = $data['score'];
            $this->metaData = $data['metaData'];
        }
        // Geo?

        $this->config = $config;
        $this->rawData = $data;
    }

    /**
     * Add Url
     *
     * @param string $url Url of image
     *
     * @return object
     */
    public function addUrl($url)
    {
        return $this->add(['url' => $url]);
    }

    /**
     * Add method
     *
     * @param array $image
     *
     * @return object
     */
    private function add(array $image)
    {
        $data['inputs'] = [
            [
                'data' => [
                    'image' => $image,
                ],
            ],
        ];

        return $this->getRequest()->request(
            'POST',
            'inputs',
            $data
        );
    }

    /**
     * Add by image path
     *
     * @param string $path Path to image
     *
     * @return object
     */
    public function addPath($path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }

        return $this->add(
            [
                'base64' => base64_encode(file_get_contents($path)),
            ]
        );
    }

    /**
     * Add base64 encoded image
     *
     * @param string $hash base64 encoded image
     *
     * @return object
     */
    public function addEncoded($hash)
    {
        return $this->add(
            [
                'base64' => $hash,
            ]
        );
    }

    // mergeConcepts
    // deleteConcepts
    // overwriteConcepts
    //
    // update
}
