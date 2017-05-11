<?php

namespace DarrynTen\Clarifai\Entity;

/**
 * Single Clarifai Input
 *
 * @package Clarifai
 */
class Input
{
    /**
     * Uses Image publicly accessible URL
     */
    const IMG_URL = 'url';

    /**
     * Uses Image local path
     */
    const IMG_PATH = 'path';

    /**
     * Uses Base64 Encoded Image
     */
    const IMG_BASE64 = 'base64';

    /**
     * The ID of the input
     *
     * @var string $id
     */
    private $id;

    /**
     * The date the input was created at
     *
     * @var string $createdAt
     */
    private $createdAt;

    /**
     * The image URL
     *
     * @var string $image
     */
    private $image;

    /**
     * The image URL
     *
     * @var string $image
     */
    private $imageMethod;

    /**
     * The concepts associated with this input
     *
     * @var Concept[] $concepts
     */
    private $concepts = [];

    /**
     * Image crop
     *
     * @var array $crop
     */
    private $crop;

    /**
     * The metadata
     *
     * @var array $metaData
     */
    private $metaData;

    /**
     * The status code
     *
     * @var string $statusCode
     */
    private $statusCode;

    /**
     * The status description
     *
     * @var string $statusDescription
     */
    private $statusDescription;

    /**
     * The raw data
     *
     * @var array $rawData
     */
    private $rawData = [];

    /**
     * Input constructor.
     *
     * @param array|null $rawData
     *
     * @throws \Exception
     */
    public function __construct(array $rawData = null)
    {
        if ($rawData) {
            if (!isset($rawData['data']['image']['url']) && !isset($rawData['data']['image']['base64'])) {
                throw new \Exception('Couldn\'t identify image method');
            }

            if (isset($rawData['data']['image']['url'])) {
                $this->setImage($rawData['data']['image']['url'])->isUrl();
            }

            if (isset($rawData['data']['image']['base64'])) {
                $this->setImage($rawData['data']['image']['base64'])->isEncoded();
            }

            if (isset($rawData['id'])) {
                $this->setId($rawData['id']);
            }

            if (isset($rawData['created_at'])) {
                $this->setCreatedAt($rawData['created_at']);
            }

            if (isset($rawData['status']) && isset($rawData['status']['code'])) {
                $this->setStatusCode($rawData['status']['code']);
            }

            if (isset($rawData['status']) && isset($rawData['status']['description'])) {
                $this->setStatusDescription($rawData['status']['description']);
            }

            if (isset($rawData['data']['image']['crop'])) {
                $this->setCrop($rawData['data']['image']['crop']);
            }

            if (isset($rawData['data']['concepts'])) {
                $this->setRawConcepts($rawData['data']['concepts']);
            }

            if (isset($rawData['data']['metadata'])) {
                $this->setMetaData($rawData['data']['metadata']);
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
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return $this
     */
    public function setImage(string $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageMethod()
    {
        return $this->imageMethod;
    }

    /**
     * @param string $imageMethod
     *
     * @return $this
     */
    public function setImageMethod(string $imageMethod)
    {
        $this->imageMethod = $imageMethod;

        return $this;
    }

    /**
     * @return $this
     */
    public function isUrl()
    {
        $this->imageMethod = self::IMG_URL;

        return $this;
    }

    /**
     * @return $this
     */
    public function isPath()
    {
        $this->imageMethod = self::IMG_PATH;

        return $this;
    }

    /**
     * @return $this
     */
    public function isEncoded()
    {
        $this->imageMethod = self::IMG_BASE64;

        return $this;
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
    public function getCrop()
    {
        return $this->crop;
    }

    /**
     * @param array $crop
     *
     * @return $this
     */
    public function setCrop(array $crop)
    {
        $this->crop = $crop;

        return $this;
    }

    /**
     * @return array
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * @param array $metaData
     *
     * @return $this
     */
    public function setMetaData(array $metaData)
    {
        $this->metaData = $metaData;

        return $this;
    }

    /**
     * @return string
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
     * Generates rawData from Input
     *
     * @return array
     */
    public function generateRawData()
    {
        $rawData = ['id' => $this->getId()];
        $rawData['data'] = [];
        $rawData['data']['image'] = [];
        if ($this->getCreatedAt()) {
            $rawData['created_at'] = $this->getCreatedAt();
        }
        if ($this->getStatusDescription() || $this->getStatusCode()) {
            $rawData['status'] = $this->getStatus();
        }
        if ($this->getImage()) {
            $rawData['data']['image'][$this->getImageMethod()] = $this->getImage();
        }
        if ($this->getCrop()) {
            $rawData['data']['image']['crop'] = $this->getCrop();
        }
        if ($this->getConcepts()) {
            $rawData['data']['concepts'] = [];
            foreach ($this->getConcepts() as $concept) {
                $rawData['data']['concepts'][] = $concept->generateRawData();
            }
        }
        if ($this->getMetaData()) {
            $rawData['data']['metadata'] = $this->getMetaData();
        }

        return $rawData;
    }
}
