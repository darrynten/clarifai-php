<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Helpers;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Entity\Model;
use DarrynTen\Clarifai\Entity\ModelVersion;

trait DataHelper
{
    /**
     * Get mock data for model.
     *
     * @return array
     */
    public function getModelData()
    {
        return [
            'id' => 'id',
            'createdAt' => 'createdAt',
            'appId' => 'appId',
            'value' => 'value',
            'name' => 'name',
        ];
    }

    /**
     * Get mock data for input.
     *
     * @return array
     */
    public function getInputData()
    {
        return [
            'id' => 'id',
            'createdAt' => 'createdAt',
            'imageUrl' => 'imageUrl',
            'score' => 'score',
            'metaData' => 'metaData',
        ];
    }

    /**
     * @return \Mockery\MockInterface|\DarrynTen\Clarifai\Request\RequestHandler
     */
    public function getRequestMock()
    {
        return \Mockery::mock('DarrynTen\Clarifai\Request\RequestHandler');
    }

    /**
     * Returns ModelVersion Entity
     *
     * @return ModelVersion
     */
    public function getModelVersionEntity()
    {
        $modelVersion = new ModelVersion();
        $modelVersion->setId('id')
            ->setCreatedAt('createdAt')
            ->setStatusCode(1000)
            ->setStatusDescription('OK');

        return $modelVersion;
    }

    /**
     * Returns Model Entity
     *
     * @return Model
     */
    public function getModelEntity()
    {
        $model = new Model();
        $model->setId('id')
            ->setCreatedAt('createdAt')
            ->setAppId('AppId')
            ->setName('Name')
            ->setModelVersion($this->getModelVersionEntity())
            ->setClosedEnvironment(0)
            ->setConceptsMutuallyExclusive(1)
            ->setConcepts(
                [
                    $this->getFullConceptEntity('id1'),
                    $this->getFullConceptEntity('id2'),
                    $this->getFullConceptEntity('id3'),
                ]
            );

        return $model;
    }

    /**
     * Gets Full One Concept Entity
     *
     * @param string $id
     * @param string $value
     *
     * @return Concept
     */
    public function getFullConceptEntity($id, $value = null)
    {
        $concept = new Concept();
        $concept->setId($id)
            ->setName($id)
            ->setAppId('appId')
            ->setValue($value)
            ->setCreatedAt('createdAt')
            ->setUpdatedAt('updatedAt')
            ->setLanguage('en')
            ->setRawData($concept->generateRawData());

        return $concept;
    }

    /**
     * Gets Example of Status Part of the Request Result
     *
     * @return array
     */
    public function getStatusResult()
    {
        return ['code' => '10000', 'description' => 'OK'];
    }
}
