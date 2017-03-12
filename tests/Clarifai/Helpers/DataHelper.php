<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Helpers;

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
}
