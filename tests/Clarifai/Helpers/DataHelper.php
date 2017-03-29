<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Helpers;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Entity\Input;
use DarrynTen\Clarifai\Entity\Model;
use DarrynTen\Clarifai\Entity\ModelVersion;

trait DataHelper
{
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

    /**
     * Get One Full Input Entity
     *
     * @return Input
     */
    public function getFullInputEntity()
    {
        $input = new Input();
        $input->setId('id')
            ->setImage('image')
            ->isUrl()
            ->setStatusCode('30001')
            ->setStatusDescription('Download pending')
            ->setCreatedAt('2017 - 02 - 24T15:34:10.944942Z')
            ->setCrop([0.2, 0.4, 0.3, 0.6])
            ->setConcepts(
                [
                    $this->getFullConceptEntity('id1', true),
                    $this->getFullConceptEntity('id2', false),
                ]
            )
            ->setMetaData(['meta1' => 'value1', 'meta2' => 'value2']);

        return $input;
    }
}
