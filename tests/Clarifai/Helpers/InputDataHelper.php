<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Helpers;

use DarrynTen\Clarifai\Entity\Concept;

trait InputDataHelper
{
    /**
     * Returns data for Input construct
     *
     * @param Concept[]|null $concepts
     *
     * @return array
     */
    public function getInputConstructData(array $concepts = null)
    {
        $data = [
            'id' => 'f1a03eb89ad04b99b88431e3466c56ac',
            'created_at' => '2017 - 02 - 24T15:34:10.944942Z',
            'status' => [
                'code' => '300000',
                'description' => 'Ok',
            ],
            'data' => [
                'image' => [
                    'url' => 'https://samples.clarifai.com/metro-north.jpg',
                    'crop' => [0.2, 0.4, 0.3, 0.6],
                ],
                'metadata' => [
                    'first' => 'value1',
                    'second' => 'value2',
                ],

            ],

        ];

        if ($concepts) {
            $data['data']['concepts'] = $concepts;
        }

        return $data;
    }
}
