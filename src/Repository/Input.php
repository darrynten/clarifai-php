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
     * Uses Image publicly accessible URL
     */
    const IMG_URL = 'url';

    /**
     * Uses Image local path
     */
    const IMG_PATH = 'path';

    /**
     * Uses Base64 Encoded Image
     */
    const IMG_BASE64 = 'base64';

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
     * Add One Image method
     *
     * @param array $image
     *
     * @return object
     */
    public function add(array $image)
    {
        $data['inputs'] = [];

        $data['inputs'][] = $this->addNewImage($image);

        return $this->getRequest()->request(
            'POST',
            'inputs',
            $data
        );

    }

    /**
     * Adds Multiple Inputs
     *
     * @param array $images
     *
     * @return object
     */
    public function addMultiple(array $images)
    {
        $data['inputs'] = [];

        foreach ($images as $image) {

            $data['inputs'][] = $this->addNewImage($image);
        }

        return $this->getRequest()->request(
            'POST',
            'inputs',
            $data
        );
    }

    /**
     * Adds new Image to Custom Input
     *
     * @param array $image
     * @return array
     */
    public function addNewImage(array $image)
    {
        $data = [];

        if (!isset($image['method'])) {
            $image['method'] = 'url';
        }

        if (isset($image['image'])) {
            $data['data'] = [
                'image' => $this->generateImageAddress($image['image'], $image['method']),
            ];
        }

        if (isset($image['id'])) {
            $data = $this->addImageId($data, $image['id']);
        }

        if (isset($image['crop']) && is_array($image['crop'])) {
            $data['data']['image'] = $this->addImageCrop($data['data']['image'], $image['crop']);
        }

        if (isset($image['concepts']) && is_array($image['concepts'])) {
            $data['data'] = $this->addImageConcepts($data['data'], $image['concepts']);
        }

        if (isset($image['metadata']) && is_array($image['metadata'])) {
            $data['data'] = $this->addImageMetadata($data['data'], $image['metadata']);
        }

        return $data;
    }

    /**
     * Generate Image With Download Type
     *
     * @param $image
     * @param null $method
     * @return array
     */
    public function generateImageAddress(string $image, $method = null)
    {

        if ($method == self::IMG_BASE64) {

            return ['base64' => $image];

        } elseif ($method == self::IMG_PATH) {

            if (!file_exists($image)) {
                throw new FileNotFoundException($image);
            }

            return ['base64' => base64_encode(file_get_contents($image))];

        } else {

            return ['url' => $image];

        }

    }

    /**
     * Adds Image Id to Image Data
     *
     * @param array $data
     * @param string $id
     * @return array
     */
    public function addImageId(array $data, string $id)
    {

        $data['id'] = $id;

        return $data;
    }

    /**
     * Adds Image Crop to Image Data
     *
     * @param array $data
     * @param array $crop
     * @return array
     */
    public function addImageCrop(array $data, array $crop)
    {

        $data['crop'] = $crop;

        return $data;
    }

    /**
     * Adds Image Concepts to Image Data
     *
     * @param array $data
     * @param array $concepts
     * @return array
     */
    public function addImageConcepts(array $data, array $concepts)
    {

        $data['concepts'] = [];

        foreach ($concepts as $concept_id => $value) {
            $data['concepts'][] = ['id' => $concept_id, 'value' => $value];
        }

        return $data;
    }

    /**
     * Adds Image Metadaya to Image Data
     *
     * @param array $data
     * @param array $metadata
     * @return array
     */
    public function addImageMetadata(array $data, array $metadata)
    {

        $data['metadata'] = [];

        foreach ($metadata as $meta_name => $meta_value) {
            $data['metadata'][$meta_name] = $meta_value;
        }

        return $data;
    }

    // mergeConcepts
    // deleteConcepts
    // overwriteConcepts
    //
    // update
}
