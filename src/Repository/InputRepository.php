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

use DarrynTen\Clarifai\Entity\Input;
use DarrynTen\Clarifai\Request\RequestHandler;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Single Clarifai Input
 *
 * @package Clarifai
 */
class InputRepository extends BaseRepository
{
    /**
     * InputRepository constructor.
     * @param RequestHandler $request
     */
    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
    }

    /**
     * add Input Method
     *
     * @param $input_data
     * @return object
     */
    public function add($input_data)
    {
        $data['inputs'] = [];

        if (is_array($input_data)) {

            foreach ($input_data as $image) {

                $data['inputs'][] = $this->addNewImage($image);
            }
        } else {
            $data['inputs'][] = $this->addNewImage($input_data);
        }

        return $this->getRequest()->request(
            'POST',
            'inputs',
            $data
        );

    }

    /**
     *  Adds new Image to Custom Input
     *
     * @param Input $image
     * @return array
     */
    public function addNewImage(Input $image)
    {
        $data = [];

        $data['data'] = [
            'image' => $this->generateImageAddress($image->getImage(), $image->getImageMethod()),
        ];

        if ($image->getId()) {
            $data = $this->addImageId($data, $image->getId());
        }

        if ($image->getCrop()) {
            $data['data']['image'] = $this->addImageCrop($data['data']['image'], $image->getCrop());
        }

        if ($image->getConcepts()) {
            $data['data'] = $this->addImageConcepts($data['data'], $image->getConcepts());
        }

        if ($image->getMetaData()) {
            $data['data'] = $this->addImageMetadata($data['data'], $image->getMetaData());
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

        if ($method == Input::IMG_BASE64) {

            return ['base64' => $image];

        } elseif ($method == Input::IMG_PATH) {

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

    /**
     * Gets All Inputs
     *
     * @return array
     * @throws \Exception
     */

    public function get()
    {
        $inputResult = $this->getRequest()->request(
            'GET',
            'inputs'
        );

        $input_array = [];

        if (property_exists($inputResult, 'inputs')) {
            foreach ($inputResult->inputs as $input) {
                $image = new Input($input);
                $input_array[] = $image;
            }
        } else {
            throw new \Exception('Inputs Not Found');
        }

        return $input_array;
    }

    /**
     * Gets Input By Id
     *
     * @param $id
     * @return Input
     * @throws \Exception
     */
    public function getById($id)
    {
        $inputResult = $this->getRequest()->request(
            'GET',
            sprintf('inputs/%s', $id)
        );

        if (property_exists($inputResult, 'input')) {
            $input = new Input($inputResult->input);
        } else {
            throw new \Exception('Input Not Found');
        }

        return $input;
    }

    /**
     * Gets Status of your Inputs
     *
     * @return \stdClass
     * @throws \Exception
     */

    public function getStatus()
    {
        $statusResult = $this->getRequest()->request(
            'GET',
            'inputs/status'
        );

        if (property_exists($statusResult, 'counts')) {
            $status = $statusResult->counts;
        } else {
            throw new \Exception('Status Not Found');
        }

        return $status;
    }

    // mergeConcepts
    // deleteConcepts
    // overwriteConcepts
    //
    // update
}
