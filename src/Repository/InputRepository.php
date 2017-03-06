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
     * Action type for Input Concepts Update
     */
    const CONCEPTS_MERGE_ACTION = 'merge';

    /**
     * Action type for Input Concepts Update
     */
    const CONCEPTS_REMOVE_ACTION = 'remove';

    /**
     * InputRepository constructor.
     *
     * @param RequestHandler $request
     */
    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
    }

    /**
     * Add Input Method
     *
     * @param $input_data
     *
     * @return array
     *
     * @throws \Exception
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

        $inputResult = $this->getRequest()->request(
            'POST',
            'inputs',
            $data
        );

        return $this->getInputsFromResult($inputResult);
    }

    /**
     *  Adds new Image to Custom Input
     *
     * @param Input $image
     *
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
     *
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
     *
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
     *
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
     *
     * @return array
     */
    public function addImageConcepts(array $data, array $concepts)
    {
        $data['concepts'] = [];

        foreach ($concepts as $concept) {
            $data['concepts'][] = $concept->generateRawData();
        }

        return $data;
    }

    /**
     * Adds Image Metadaya to Image Data
     *
     * @param array $data
     * @param array $metadata
     *
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
     *
     * @throws \Exception
     */
    public function get()
    {
        $inputResult = $this->getRequest()->request(
            'GET',
            'inputs'
        );

        return $this->getInputsFromResult($inputResult);
    }

    /**
     * Gets Input By Id
     *
     * @param $id
     *
     * @return Input
     *
     * @throws \Exception
     */
    public function getById($id)
    {
        $inputResult = $this->getRequest()->request(
            'GET',
            sprintf('inputs/%s', $id)
        );

        if (isset($inputResult['input'])) {
            $input = new Input($inputResult['input']);
        } else {
            throw new \Exception('Input Not Found');
        }

        return $input;
    }

    /**
     * Gets Status of your Inputs
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getStatus()
    {
        $statusResult = $this->getRequest()->request(
            'GET',
            'inputs/status'
        );

        if (isset($statusResult['counts'])) {
            $status = $statusResult['counts'];
        } else {
            throw new \Exception('Status Not Found');
        }

        return $status;
    }

    /**
     * Deletes Input By Id
     *
     * @param $id
     *
     * @return array
     */
    public function deleteById($id)
    {
        $deleteResult = $this->getRequest()->request(
            'DELETE',
            sprintf('inputs/%s', $id)
        );

        return $deleteResult['status'];
    }

    /**
     * Deletes Inputs By Id Array
     *
     * @param array $ids
     *
     * @return array
     */
    public function deleteByIdArray(array $ids)
    {
        $data['ids'] = $ids;

        $deleteResult = $this->getRequest()->request(
            'DELETE',
            'inputs',
            $data
        );

        return $deleteResult['status'];
    }

    /**
     * Deletes All Inputs
     *
     * @return array
     */
    public function deleteAll()
    {
        $deleteResult = $this->getRequest()->request(
            'DELETE',
            'inputs',
            ['delete_all' => true]
        );

        return $deleteResult['status'];
    }

    /**
     * Common Input's Concepts Update Method
     *
     * @param  array $conceptsArray
     * @param $action
     *
     * @return array
     */
    public function updateInputConcepts(array $conceptsArray, $action)
    {
        $data['inputs'] = [];

        foreach ($conceptsArray as $inputId => $inputConcepts) {
            $input = [];
            $input['id'] = $inputId;
            $input['data'] = [];
            $input['data'] = $this->addImageConcepts($input['data'], $inputConcepts);

            $data['inputs'][] = $input;
        }
        $data['action'] = $action;


        $updateResult = $this->getRequest()->request(
            'PATCH',
            'inputs',
            $data
        );

        return $this->getInputsFromResult($updateResult);
    }

    /**
     * Merges Concepts of Inputs
     *
     * @param  array $conceptsArray
     *
     * @return array
     */
    public function mergeInputConcepts(array $conceptsArray)
    {
        return $this->updateInputConcepts($conceptsArray, self::CONCEPTS_MERGE_ACTION);
    }

    /**
     * Deletes Concepts of Inputs
     *
     * @param  array $conceptsArray
     *
     * @return array
     */
    public function deleteInputConcepts(array $conceptsArray)
    {
        return $this->updateInputConcepts($conceptsArray, self::CONCEPTS_REMOVE_ACTION);
    }

    /**
     * Parses Request Result and gets Inputs
     *
     * @param $inputResult
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getInputsFromResult($inputResult)
    {
        $input_array = [];

        if ($inputResult['inputs']) {
            foreach ($inputResult['inputs'] as $rawInput) {
                $input = new Input($rawInput);
                $input_array[] = $input;
            }
        } else {
            throw new \Exception('Inputs Not Found');
        }

        return $input_array;
    }
}
