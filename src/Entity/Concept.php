<?php

namespace DarrynTen\Clarifai\Entity;

/**
 * Single Clarifai Concept
 *
 * @package Clarifai
 */
class Concept
{
    /**
     * The ID of the Concept
     *
     * @var string $id
     */
    private $id;

    /**
     * The Concept name
     *
     * @var string $name
     */
    private $name;

    /**
     * The date the Concept was created at
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
     * The value
     *
     * @var string|null $value
     */
    private $value = null;

    /**
     * The raw data
     *
     * @var array $rawData
     */
    private $rawData = [];

    /**
     * Model constructor.
     *
     * @param array|null $rawData
     */
    public function __construct(array $rawData = null)
    {
        if ($rawData) {
            $this->setRawData($rawData);
            if (isset($rawData['id'])) {
                $this->setId($rawData['id']);
            }
            if (isset($Concept['name'])) {
                $this->setName($rawData['name']);
            }
            if (isset($rawData['created_at'])) {
                $this->setCreatedAt($rawData['created_at']);
            }
            if (isset($rawData['app_id'])) {
                $this->setAppId($rawData['app_id']);
            }
            if (isset($rawData['value'])) {
                $this->setValue($rawData['value']);
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
     * @return null|string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param null|string $value
     * 
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

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
}
