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
     * @var array $concepts
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
     * Status
     *
     * @var array $status
     */
    private $status = ['code' => '', 'description' => ''];

    /**
     * Input constructor.
     *
     * @param array|null $input
     *
     * @throws \Exception
     */
    public function __construct(array $input = null)
    {
        if ($input) {
            if (isset($input['id'])) {
                $this->setId($input['id']);
            }
            if (isset($input['created_at'])) {
                $this->setCreatedAt($input['created_at']);
            }
            if (isset($input['status'])) {
                $this->setStatus($input['status']['code'], $input['status']['description']);;
            }
            if (isset($input['data']['image']['url'])) {
                $this->setImage($input['data']['image']['url'])->isUrl();
            } elseif (isset($input['data']['image']['base64'])) {
                $this->setImage($input['data']['image']['base64'])->isEncoded();
            } else {
                throw new \Exception('Couldn\'t indetify image method');
            }
            if (isset($input['data']['image']['crop'])) {
                $this->setCrop($input['data']['image']['crop']);
            }
//          TODO: Implement Concept Entity
//            if (property_exists($input->data, 'concepts')) {
//                $this->setConcepts($input->data->concepts);
//            }
            if (isset($input['data']['metadata'])) {
                $this->setMetaData($input['data']['metadata']);
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
     * @return array
     */
    public function getConcepts()
    {
        return $this->concepts;
    }

    /**
     * @param array $concepts
     *
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
        return $this->status;
    }

    /**
     * @param null $code
     * @param null $description
     *
     * @return $this
     */
    public function setStatus($code = null, $description = null)
    {
        $this->status['code'] = $code;
        $this->status['description'] = $description;

        return $this;
    }


}
