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

use DarrynTen\Clarifai\Request\RequestHandler;

/**
 * Single Clarifai Model
 *
 * @package Clarifai
 */
class Model extends BaseRepository
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
     * @param array|string $inputs The inputs
     * @param string|null $language Language to return results in
     *
     * @return void
     */
    public function predict($inputs, $language = null)
    {
        //
    }

    /**
     * The actual predict call
     *
     * @param array|string $inputs The inputs
     * @param string|null $language Language to return results in
     *
     * @return object
     */
    public function predictFromUrl($url, $modelType, $language = null)
    {
        $data['inputs'] = [
            [
                'data' => [
                    'image' => ['url' => $url]
                ]
            ]
        ];

        if ($language) {
            $data['model'] = [
                'output_info' => [
                    'output_config' => [
                        'language' => $language
                    ]
                ]
            ];
        }

        return $this->getRequest()->request(
            'POST',
            sprintf('models/%s/outputs', $modelType),
            $data
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

    // mergeConcepts
    // deleteConcepts
    // overwriteConcepts
    //
    // getVersion
    // getVersions
    // getOutputInfo
    // getInputs
}
