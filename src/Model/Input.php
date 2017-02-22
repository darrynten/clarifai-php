<?php

namespace DarrynTen\Clarifai\Model;

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
     *  @var array $concepts
     */
    private $concepts;

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
     * Input constructor.
     * @param $image
     * @param string $imageMethod
     */
    public function __construct(string $image, string $imageMethod = self::IMG_URL)
    {
        $this->image = $image;
        $this->imageMethod = $imageMethod;
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
     * @return $this
     */
    public function setImageMethod(string $imageMethod)
    {
        $this->imageMethod = $imageMethod;

        return $this;
    }

    /**
     * @return array
     */
    public function getConcepts()
    {
        return $this->concepts;
    }

    /**
     * @param array $concepts
     * @return $this
     */
    public function setConcepts(array $concepts)
    {
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
     * @return $this
     */
    public function setMetaData(array $metaData)
    {
        $this->metaData = $metaData;

        return $this;
    }


}
