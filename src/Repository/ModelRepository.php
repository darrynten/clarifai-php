<?php
/**
 * Clarifai Model
 *
 * @category Model
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Input;
use DarrynTen\Clarifai\Entity\Model;
use DarrynTen\Clarifai\Entity\ModelVersion;
use DarrynTen\Clarifai\Request\RequestHandler;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

/**
 * Single Clarifai ModelRepository
 *
 * @package Clarifai
 */
class ModelRepository extends BaseRepository
{
    /**
     * Model id of the General Clarifai model
     *
     * The General model contains a wide range of tags across many different topics. In most cases,
     * tags returned from the general model will sufficiently recognize what's inside your image.
     */
    const GENERAL = 'aaa03c23b3724a16a56b629203edc62c';

    /**
     * Model id of the Food Clarifai model
     *
     * The Food model analyzes images and videos and returns probability scores on the likelihood that the image
     * contains a recognized food ingredient and dish. The current model is designed to identify specific food
     * items and visible ingredients.
     */
    const FOOD = 'bd367be194cf45149e75f01d59f77ba7';

    /**
     * Model id of the Travel Clarifai model
     *
     * The Travel model analyzes images and returns probability scores on the likelihood that the image contains a
     * recognized travel related category. The current model is designed to identify specific features of residential,
     * hotel and travel related properties.
     */
    const TRAVEL = 'eee28c313d69466f836ab83287a54ed9';

    /**
     * Model id of the NSFW Clarifai model
     *
     * The NSFW (Not Safe For Work) model analyzes images and videos and returns probability scores on the
     * likelihood that the image contains pornography.
     */
    const NSFW = 'e9576d86d2004ed1a38ba0cf39ecb4b1';

    /**
     * Model id of the Wedding Clarifai model
     *
     * The Wedding model knows all about weddings including brides, grooms, dresses, flowers, etc.
     */
    const WEDDINGS = 'c386b7a870114f4a87477c0824499348';

    /**
     * Model id of the Color Clarifai model
     *
     * The Color model is used to retrieve the dominant colors present in your images.
     * Color predictions are returned in the hex format. A density value is also returned to let you know how much of
     * the color is present. In addition, colors are also mapped to their closest W3C counterparts.
     */
    const COLOR = 'eeed0b6733a644cea07cf4c60f87ebb7';

    /**
     * Model id of the Face Clarifai model
     *
     * The Face Detection model analyzes images, GIFs and videos and
     * returns probability scores on the likelihood that the media
     * contains human faces. If human faces are detected, the model will
     * also return the coordinate locations of those faces with a
     * bounding box.
     */
    const FACE = 'a403429f2ddf4b49b307e318f00e528b';

    /**
     * Model id of the Apparel Clarifai model
     *
     * The 'Apparel Model' model analyzes images and returns probability
     * scores on the likelihood that the media contains a recognized
     * clothing or accessory item.
     */
    const APPAREL = 'e0be3b9d6a454f0493ac3a30784001ff';

    /**
     * Model id of the Celebrity Clarifai model
     *
     * The Celebrity model analyzes images and returns probability scores
     * on the likelihood that the media contains the face(s) of a
     * recognized celebrity.
     */
    const CELEBRITY = 'e466caa0619f444ab97497640cefc4dc';

    /**
     * Constructor
     *
     * @param RequestHandler $request
     */
    public function __construct(RequestHandler $request)
    {
        parent::__construct($request);
    }

    /**
     * The actual predict call
     *
     * @param array $image
     * @param $modelType
     * @param string|null $language Language to return results in
     *
     * @return array
     */
    private function predict(array $image, $modelType, $language = null)
    {
        $data['inputs'] = [
            [
                'data' => [
                    'image' => $image,
                ],
            ],
        ];

        if ($language) {
            $data['model'] = [
                'output_info' => [
                    'output_config' => [
                        'language' => $language,
                    ],
                ],
            ];
        }

        return $this->getRequest()->request(
            'POST',
            $this->getRequestUrl(sprintf('models/%s/outputs', $modelType)),
            $data
        );
    }

    /**
     * Predict by url
     *
     * @param string $url Url of image
     * @param string $modelType Type of model to predict
     * @param string|null $language Language to return results in
     *
     * @return array
     */
    public function predictUrl($url, $modelType, $language = null)
    {
        return $this->predict(['url' => $url], $modelType, $language);
    }

    /**
     * Predict by image path
     *
     * @param string $path Path to image
     * @param string $modelType Type of model to predict
     * @param string|null $language Language to return results in
     *
     * @return array
     */
    public function predictPath($path, $modelType, $language = null)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }

        return $this->predict(
            [
                'base64' => base64_encode(file_get_contents($path)),
            ],
            $modelType,
            $language
        );
    }

    /**
     * Predict base64 encoded image
     *
     * @param string $hash base64 encoded image
     * @param string $modelType Type of model to predict
     * @param string|null $language Language to return results in
     *
     * @return array
     */
    public function predictEncoded($hash, $modelType, $language = null)
    {
        return $this->predict(
            [
                'base64' => $hash,
            ],
            $modelType,
            $language
        );
    }

    /**
     * Train the model
     *
     * @param string $id
     *
     * @return Model
     */
    public function train(string $id)
    {
        $modelResult = $this->getRequest()->request(
            'POST',
            $this->getRequestUrl(sprintf('models/%s/versions', $id))
        );

        return $this->getModelFromResult($modelResult);
    }

    /**
     * Create new Model
     *
     * @param Model $model
     *
     * @return Model
     *
     * @throws \Exception
     */
    public function create(Model $model)
    {
        $data['model'] = $this->createModelData($model);

        $modelResult = $this->getRequest()->request(
            'POST',
            $this->getRequestUrl('models'),
            $data
        );

        return $this->getModelFromResult($modelResult);
    }

    /**
     * Update the model
     *
     * @param Model $model
     *
     * @return Model[]
     */
    public function update(Model $model)
    {
        $data['models'] = [];
        $data['models'][] = $this->createModelData($model);
        $data['action'] = 'merge';

        $modelResult = $this->getRequest()->request(
            'PATCH',
            $this->getRequestUrl('models'),
            $data
        );

        return $this->getModelsFromResult($modelResult);
    }

    /**
     * Create Model data for Request
     *
     * @param Model $model
     *
     * @return array
     */
    public function createModelData(Model $model)
    {
        $data = [];

        if ($model->getId()) {
            $data['id'] = $model->getId();
        }
        if ($model->getName()) {
            $data['name'] = $model->getName();
        }
        if ($model->getConcepts()) {
            $data['output_info']['data'] = [];
            $data['output_info']['data'] = $this->addModelConcepts($data['output_info']['data'], $model->getConcepts());
        }
        $data['output_info']['output_config'] = $model->getOutputConfig();

        return $data;
    }

    /**
     * Gets All Models
     *
     * @return array
     *
     * @throws \Exception
     */
    public function get()
    {
        $modelResult = $this->getRequest()->request(
            'GET',
            $this->getRequestUrl('models')
        );

        return $this->getModelsFromResult($modelResult);
    }

    /**
     * Gets Model By Id
     *
     * @param $id
     *
     * @return Model
     *
     * @throws \Exception
     */
    public function getById($id)
    {
        $modelResult = $this->getRequest()->request(
            'GET',
            $this->getRequestUrl(sprintf('models/%s', $id))
        );

        return $this->getModelFromResult($modelResult);
    }

    /**
     * Gets Model Output Info By Id
     *
     * @param $id
     *
     * @return Model
     *
     * @throws \Exception
     */
    public function getOutputInfoById($id)
    {
        $modelResult = $this->getRequest()->request(
            'GET',
            $this->getRequestUrl(sprintf('models/%s/output_info', $id))
        );

        return $this->getModelFromResult($modelResult);
    }

    /**
     * Gets Model Versions
     *
     * @param $id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getModelVersions($id)
    {
        $modelResult = $this->getRequest()->request(
            'GET',
            $this->getRequestUrl(sprintf('models/%s/versions', $id))
        );

        $modelVersions = [];

        if (isset($modelResult['model_versions'])) {
            foreach ($modelResult['model_versions'] as $version) {
                $modelVersion = new ModelVersion($version);
                $modelVersions[] = $modelVersion;
            }
        } else {
            throw new \Exception('Model Versions Not Found');
        }

        return $modelVersions;
    }

    /**
     * Gets Model Version By Id
     *
     * @param string $modelId
     * @param string $versionId
     *
     * @return ModelVersion
     *
     * @throws \Exception
     */
    public function getModelVersionById($modelId, $versionId)
    {
        $modelResult = $this->getRequest()->request(
            'GET',
            $this->getRequestUrl(sprintf('models/%s/versions/%s', $modelId, $versionId))
        );

        if (isset($modelResult['model_version'])) {
            $modelVersion = new ModelVersion($modelResult['model_version']);
        } else {
            throw new \Exception('Model Versions Not Found');
        }

        return $modelVersion;
    }

    /**
     * Common Model's Concepts Update Method
     *
     * @param  array $modelsArray
     * @param $action
     *
     * @return array
     */
    public function updateModelConcepts(array $modelsArray, $action)
    {
        $data['models'] = [];

        foreach ($modelsArray as $modelId => $modelConcepts) {
            $model = [];
            $model['id'] = (string)$modelId;
            $model['output_info'] = [];
            $model['output_info']['data'] = [];
            $model['output_info']['data'] = $this->addModelConcepts($model['output_info']['data'], $modelConcepts);

            $data['models'][] = $model;
        }
        $data['action'] = $action;

        $updateResult = $this->getRequest()->request(
            'PATCH',
            $this->getRequestUrl('models'),
            $data
        );

        return $this->getModelsFromResult($updateResult);
    }

    /**
     * Merges Concepts of Model
     *
     * @param  array $modelsArray
     *
     * @return array
     */
    public function mergeModelConcepts(array $modelsArray)
    {
        return $this->updateModelConcepts($modelsArray, self::CONCEPTS_MERGE_ACTION);
    }

    /**
     * Deletes Concepts of Model
     *
     * @param  array $modelsArray
     *
     * @return array
     */
    public function deleteModelConcepts(array $modelsArray)
    {
        return $this->updateModelConcepts($modelsArray, self::CONCEPTS_REMOVE_ACTION);
    }


    /**
     * Overwrites Concepts of Model
     *
     * @param  array $modelsArray
     *
     * @return array
     */
    public function overwriteModelConcepts(array $modelsArray)
    {
        return $this->updateModelConcepts($modelsArray, self::CONCEPTS_OVERWRITE_ACTION);
    }

    /**
     * Parses Request Result and gets Models
     *
     * @param $modelResult
     *
     * @return Model[]
     *
     * @throws \Exception
     */
    public function getModelsFromResult($modelResult)
    {
        $modelsArray = [];

        if ($modelResult['models']) {
            foreach ($modelResult['models'] as $model) {
                $model = new Model($model);
                $modelsArray[] = $model;
            }
        } else {
            throw new \Exception('Models Not Found');
        }

        return $modelsArray;
    }

    /**
     * Parses Request Result and gets Model
     *
     * @param $modelResult
     *
     * @return Model
     *
     * @throws \Exception
     */
    public function getModelFromResult($modelResult)
    {
        if ($modelResult['model']) {
            $model = new Model($modelResult['model']);
        } else {
            throw new \Exception('Model Not Found');
        }

        return $model;
    }

    /**
     * Adds Model's Concepts to Model's Data
     *
     * @param array $data
     * @param array $concepts
     *
     * @return array
     */
    public function addModelConcepts(array $data, array $concepts)
    {
        $data['concepts'] = [];

        foreach ($concepts as $concept) {
            $data['concepts'][] = [
                'id' => $concept->getId(),
            ];
        }

        return $data;
    }

    /**
     * Delete Model By Id
     *
     * @param string $modelId
     *
     * @return array
     */
    public function deleteById(string $modelId)
    {
        $deleteResult = $this->getRequest()->request(
            'DELETE',
            $this->getRequestUrl(sprintf('models/%s', $modelId))
        );

        return $deleteResult['status'];
    }

    /**
     * Delete Model Version By Id
     *
     * @param string $modelId
     * @param string $versionId
     *
     * @return array
     */
    public function deleteVersionById(string $modelId, string $versionId)
    {
        $deleteResult = $this->getRequest()->request(
            'DELETE',
            $this->getRequestUrl(sprintf('models/%s/versions/%s', $modelId, $versionId))
        );

        return $deleteResult['status'];
    }

    /**
     * Deletes All Models
     *
     * @return array
     */
    public function deleteAll()
    {
        $deleteResult = $this->getRequest()->request(
            'DELETE',
            $this->getRequestUrl('models'),
            ['delete_all' => true]
        );

        return $deleteResult['status'];
    }

    /**
     * Get Model Training Inputs By Model Id
     *
     * @param string $modelId
     *
     * @return array
     */
    public function getTrainingInputsById(string $modelId)
    {
        $inputResult = $this->getRequest()->request(
            'GET',
            $this->getRequestUrl(sprintf('models/%s/inputs', $modelId))
        );

        return $this->getInputsFromResult($inputResult);
    }

    /**
     * Get Model Training Inputs By Model Id
     *
     * @param string $modelId
     * @param string $versionId
     *
     * @return array
     */
    public function getTrainingInputsByVersion(string $modelId, string $versionId)
    {
        $inputResult = $this->getRequest()->request(
            'GET',
            $this->getRequestUrl(sprintf('models/%s/versions/%s/inputs', $modelId, $versionId))
        );

        return $this->getInputsFromResult($inputResult);
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

        if (isset($inputResult['inputs'])) {
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
