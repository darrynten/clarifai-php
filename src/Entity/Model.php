<?php

namespace DarrynTen\Clarifai\Entity;

/**
 * Single Clarifai Model
 *
 * @package Clarifai
 */
class Model
{
    /**
     * The ID of the model
     *
     * @var string $id
     */
    private $id;

    /**
     * The model name
     *
     * @var string $name
     */
    private $name;

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
     * outputInfo
     *
     * @var array $outputInfo
     */
    private $outputInfo;

    /**
     * modelVersion
     *
     * @var ModelVersion
     */
    private $modelVersion;

    /**
     * Output Config
     *
     * @var array $outputConfig
     */
    private $outputConfig = ['concepts_mutually_exclusive' => '', 'closed_environment' => ''];

    /**
     * Model constructor.
     *
     * @param array|null $model
     */
    public function __construct(array $model = null)
    {
        if ($model) {
            if (isset($model['id'])) {
                $this->setId($model['id']);
            }
            if (isset($model['name'])) {
                $this->setName($model['name']);
            }
            if (isset($model['created_at'])) {
                $this->setCreatedAt($model['created_at']);
            }
            if (isset($model['app_id'])) {
                $this->setAppId($model['app_id']);
            }
            if (isset($model['output_info'])) {
                $this->setOutputInfo($model['output_info']);
            }
            if (isset($model['model_version'])) {
                $this->setModelVersion(new ModelVersion($model['model_version']));
            }
            if (isset($model['output_config'])) {
                $this->setOutputConfig($model['output_info']['concepts_mutually_exclusive'], $model['output_info']['closed_environment']);
            }
//          TODO: Implement Concept Entity
//            if (property_exists($input->data, 'concepts')) {
//                $this->setConcepts($input->data->concepts);
//            }
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     *
     * @return $this
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     *
     * @return $this
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     *
     * @return $this
     */
    public function setAppId(string $appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * @return array
     */
    public function getOutputInfo()
    {
        return $this->outputInfo;
    }

    /**
     * @param array $outputInfo
     *
     * @return $this
     */
    public function setOutputInfo(array $outputInfo)
    {
        $this->outputInfo = $outputInfo;

        return $this;
    }

    /**
     * @return modelVersion
     */
    public function getModelVersion()
    {
        return $this->modelVersion;
    }

    /**
     * @param modelVersion $modelVersion
     *
     * @return $this
     */
    public function setModelVersion(ModelVersion $modelVersion)
    {
        $this->modelVersion = $modelVersion;

        return $this;
    }

    /**
     * @return array
     */
    public function getOutputConfig()
    {
        return $this->outputConfig;
    }

    /**
     * @param null $concepts_mutually_exclusive
     * @param null $closed_environment
     *
     * @return $this
     */
    public function setOutputConfig($concepts_mutually_exclusive = null, $closed_environment = null)
    {
        $this->outputConfig['concepts_mutually_exclusive'] = $concepts_mutually_exclusive;
        $this->outputConfig['description'] = $closed_environment;

        return $this;
    }
}
