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
     * modelVersion
     *
     * @var ModelVersion
     */
    private $modelVersion;

    /**
     * @var bool $conceptsMutuallyExclusive
     */
    private $conceptsMutuallyExclusive;

    /**
     * @var bool $closedEnvironment
     */
    private $closedEnvironment;

    /**
     * The concepts associated with this input
     *
     * @var Concept[] $concepts
     */
    private $concepts = [];

    /**
     * The raw data
     *
     * @var array $rawData
     */
    private $rawData = [];

    /**
     * Model constructor.
     *
     * @param array|null $model
     */
    public function __construct(array $model = null)
    {
        if ($model) {
            $this->setRawData($model);
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
            if (isset($model['output_info'])
                && isset($model['output_info']['data'])
                && isset($model['output_info']['data']['concepts'])
            ) {
                $this->setRawConcepts($model['output_info']['data']['concepts']);
            }
            if (isset($model['output_info'])
                && isset($model['output_info']['output_config'])
                && isset($model['output_info']['output_config']['concepts_mutually_exclusive'])
            ) {
                $this->setConceptsMutuallyExclusive(
                    $model['output_info']['output_config']['concepts_mutually_exclusive']
                );
            }
            if (isset($model['output_info'])
                && isset($model['output_info']['output_config'])
                && isset($model['output_info']['output_config']['closed_environment'])
            ) {
                $this->setClosedEnvironment($model['output_info']['output_config']['closed_environment']);
            }

            if (isset($model['model_version'])) {
                $this->setModelVersion(new ModelVersion($model['model_version']));
            }
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
     * @return bool
     */
    public function isConceptsMutuallyExclusive()
    {
        return $this->conceptsMutuallyExclusive;
    }

    /**
     * @param bool $conceptsMutuallyExclusive
     *
     * @return $this
     */
    public function setConceptsMutuallyExclusive($conceptsMutuallyExclusive)
    {
        $this->conceptsMutuallyExclusive = $conceptsMutuallyExclusive;

        return $this;
    }

    /**
     * @return bool
     */
    public function isClosedEnvironment()
    {
        return $this->closedEnvironment;
    }

    /**
     * @param bool $closedEnvironment
     *
     * @return $this
     */
    public function setClosedEnvironment($closedEnvironment)
    {
        $this->closedEnvironment = $closedEnvironment;

        return $this;
    }

    /**
     * @return array
     */
    public function getOutputConfig()
    {
        return [
            'concepts_mutually_exclusive' => $this->isConceptsMutuallyExclusive(),
            'closed_environment' => $this->isClosedEnvironment(),
        ];
    }

    /**
     * @return Concept[]|[]
     */
    public function getConcepts()
    {
        return $this->concepts;
    }

    /**
     * @param Concept[] $concepts
     *
     * @return $this
     */
    public function setConcepts(array $concepts)
    {
        $this->concepts = $concepts;

        return $this;
    }

    /**
     * Sets concepts from Raw Data
     *
     * @param array $rawConcepts
     *
     * @return $this
     */
    public function setRawConcepts(array $rawConcepts)
    {
        $concepts = [];
        foreach ($rawConcepts as $rawConcept) {
            $concept = new Concept($rawConcept);
            $concepts[] = $concept;
        }
        $this->concepts = $concepts;

        return $this;
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @param array $rawData
     *
     * @return $this
     */
    public function setRawData(array $rawData)
    {
        $this->rawData = $rawData;

        return $this;
    }

    /**
     * Generates rawData from Model
     *
     * @return array
     */
    public function generateRawData()
    {
        $rawData = ['id' => $this->getId()];
        $rawData['output_info'] = [];
        $rawData['output_info']['data'] = [];
        if ($this->getName()) {
            $rawData['name'] = $this->getName();
        }
        if ($this->getAppId()) {
            $rawData['app_id'] = $this->getAppId();
        }
        if ($this->getCreatedAt()) {
            $rawData['created_at'] = $this->getCreatedAt();
        }
        if ($this->getConcepts()) {
            $rawData['output_info']['data']['concepts'] = [];
            foreach ($this->getConcepts() as $concept) {
                $rawData['output_info']['data']['concepts'][] = $concept->generateRawData();
            }
        }
        if ($this->isConceptsMutuallyExclusive() || $this->isClosedEnvironment()) {
            $rawData['output_info']['output_config'] = $this->getOutputConfig();
        }
        if ($this->getModelVersion()) {
            $rawData['model_version'] = $this->getModelVersion()->generateRawData();
        }

        return $rawData;
    }
}
