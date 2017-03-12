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
     * Status
     *
     * @var array $status
     */
    private $status = ['code' => '', 'description' => ''];

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
            if (isset($modelVersion['status'])) {
                $this->setStatus($modelVersion['status']['code'], $modelVersion['status']['description']);
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
