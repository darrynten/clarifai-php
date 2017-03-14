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
     * The date the Concept was created at
     *
     * @var string $updatedAt
     */
    private $updatedAt;

    /**
     * The app ID
     *
     * @var string $appId
     */
    private $appId;

    /**
     * The value
     *
     * @var bool $value
     */
    private $value;

    /**
     * The Concept language
     *
     * @var string $name
     */
    private $language;

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
            if (isset($rawData['name'])) {
                $this->setName($rawData['name']);
            }
            if (isset($rawData['app_id'])) {
                $this->setAppId($rawData['app_id']);
            }
            if (isset($rawData['value'])) {
                $this->setValue($rawData['value']);
            }
            if (isset($rawData['created_at'])) {
                $this->setCreatedAt($rawData['created_at']);
            }
            if (isset($rawData['updated_at'])) {
                $this->setUpdatedAt($rawData['updated_at']);
            }
            if (isset($rawData['language'])) {
                $this->setLanguage($rawData['language']);
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
     * @return null|bool
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param bool $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     *
     * @return $this
     */
    public function setLanguage(string $language)
    {
        $this->language = $language;

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
     * Generates rawData from Concept
     *
     * @return array
     */
    public function generateRawData()
    {
        $rawData = ['id' => $this->getId()];
        if ($this->getValue()) {
            $rawData['value'] = $this->getValue();
        }
        if ($this->getName()) {
            $rawData['name'] = $this->getName();
        }
        if ($this->getAppId()) {
            $rawData['app_id'] = $this->getAppId();
        }
        if ($this->getLanguage()) {
            $rawData['language'] = $this->getLanguage();
        }
        if ($this->getCreatedAt()) {
            $rawData['created_at'] = $this->getCreatedAt();
        }
        if ($this->getUpdatedAt()) {
            $rawData['updated_at'] = $this->getUpdatedAt();
        }

        return $rawData;
    }
}
