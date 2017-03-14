<?php

namespace DarrynTen\Clarifai\Entity;

class ModelVersion
{
    /**
     * The ID of the modelVersion
     *
     * @var string $id
     */
    private $id;

    /**
     * The date the modelVersion was created at
     *
     * @var string $createdAt
     */
    private $createdAt;

    /**
     * The modelVersion status code
     *
     * @var string $statusCode
     */
    private $statusCode;

    /**
     * The modelVersion status description
     *
     * @var string $statusDescription
     */
    private $statusDescription;

    /**
     * ModelVersion constructor.
     *
     * @param array|null $modelVersion
     */
    public function __construct(array $modelVersion = null)
    {
        if ($modelVersion) {
            if (isset($modelVersion['id'])) {
                $this->setId($modelVersion['id']);
            }
            if (isset($modelVersion['created_at'])) {
                $this->setCreatedAt($modelVersion['created_at']);
            }
            if (isset($modelVersion['status']) && isset($modelVersion['status']['code'])) {
                $this->setStatusCode($modelVersion['status']['code']);
            }
            if (isset($modelVersion['status']) && isset($modelVersion['status']['description'])) {
                $this->setStatusDescription($modelVersion['status']['description']);
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
     * @return array
     */
    public function getStatus()
    {
        return ['code' => $this->getStatusCode(), 'description' => $this->getStatusDescription()];
    }

    /**
     * @return string
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     *
     * @return $this
     */
    public function setStatusCode(string $statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatusDescription()
    {
        return $this->statusDescription;
    }

    /**
     * @param string $statusDescription
     *
     * @return $this
     */
    public function setStatusDescription(string $statusDescription)
    {
        $this->statusDescription = $statusDescription;

        return $this;
    }

    /**
     * Generates rawData from modelVersion
     *
     * @return array
     */
    public function generateRawData()
    {
        $rawData = ['id' => $this->getId()];
        if ($this->getCreatedAt()) {
            $rawData['created_at'] = $this->getCreatedAt();
        }
        if ($this->getStatusDescription() || $this->getStatusCode()) {
            $rawData['status'] = $this->getStatus();
        }

        return $rawData;
    }
}
