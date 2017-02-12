<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

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
            'metaData' => 'metaData'
        ];
    }
}
