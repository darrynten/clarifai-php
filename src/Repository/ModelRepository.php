<?php
/**
 * Clarifai Model
 *
 * @category Model
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

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
     * The ID of the model
     *
     * @var string $modelId
     */
    private $modelId;

    /**
     * The name of the model
     *
     * @var string $modelName
     */
    private $modelName;

    /**
     * The date the model was created at
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
     * The output info
     *
     * @var string $outputInfo
     */
    private $outputInfo;

    /**
     * The model version
     *
     * @var string $modelVersion
     */
    private $modelVersion;

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
     * @param array $config The config for the model
     * @param array $data The data for the model
     */
    public function __construct(RequestHandler $request, $config = null, $data = null)
    {
        parent::__construct($request);
        if (!empty($data)) {
            $this->modelId = $data['id'];
            $this->modelName = $data['name'];
            $this->createdAt = $data['createdAt'];
            $this->appId = $data['appId'];
            $this->value = $data['value'] || null;
        }

        $this->config = $config;
        $this->rawData = $data;
    }

    /**
     * The actual predict call
     *
     * @param array $image
     * @param $modelType
     * @param string|null $language Language to return results in
     *
     * @return object
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
            sprintf('models/%s/outputs', $modelType),
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
     * @return object
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
     * @return object
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
     * @return object
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

    // This comes later, after predict

    /**
     * Train the model
     *
     * @return void
     */
    public function train()
    {
        //
    }

    /**
     * Update the model
     *
     * @return void
     */
    public function update()
    {
        //
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

        $inputResult = $this->getRequest()->request(
            'POST',
            'models',
            $data
        );

        if ($inputResult['model']) {
            $model = new Model($inputResult['model']);
        } else {
            throw new \Exception('Model Not Found');
        }

        return $model;
    }

    /**
     * Create new Model
     *
     * @param Model $model
     *
     * @return array
     */
    public function createModelData(Model $model)
    {
        $data = [];

        if($model->getId()){
            $data['id'] = $model->getId();
        }
        if($model->getName()){
            $data['name'] = $model->getName();
        }

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
        $inputResult = $this->getRequest()->request(
            'GET',
            'models'
        );

        $modelsArray = [];

        if ($inputResult['models']) {
            foreach ($inputResult['models'] as $model) {
                $model = new Model($model);
                $modelsArray[] = $model;
            }
        } else {
            throw new \Exception('Models Not Found');
        }

        return $modelsArray;
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
        $inputResult = $this->getRequest()->request(
            'GET',
            sprintf('models/%s', $id)
        );

        if ($inputResult['model']) {
            $model = new Model($inputResult['model']);
        } else {
            throw new \Exception('Model Not Found');
        }

        return $model;
    }

    /**
     * Gets Model Output Info By Id
     *
     * @param $id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getOutputInfoById($id)
    {
        $inputResult = $this->getRequest()->request(
            'GET',
            sprintf('models/%s/output_info', $id)
        );

        //TODO: Figure out why it returns full Model Entity instead of only [output_info] part

        return $inputResult;
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
        $inputResult = $this->getRequest()->request(
            'GET',
            sprintf('models/%s/versions', $id)
        );

        $modelVersions = [];

        if ($inputResult['model_versions']) {
            foreach ($inputResult['model_versions'] as $version) {
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
     * @param string $model_id
     * @param string $version_id
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getModelVersionById($model_id, $version_id)
    {
        $inputResult = $this->getRequest()->request(
            'GET',
            sprintf('models/%s/versions/%s', $model_id, $version_id)
        );

        if ($inputResult['model_version']) {
            $modelVersion = new ModelVersion($inputResult['model_version']);
        } else {
            throw new \Exception('Model Versions Not Found');
        }

        return $modelVersion;
    }


    // mergeConcepts
    // deleteConcepts
    // overwriteConcepts
    //
    // getVersion
    // getVersions
    // getOutputInfo
    // getInputs
}
