<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use PHPUnit_Framework_TestCase;

use DarrynTen\Clarifai\Clarifai;
use DarrynTen\Clarifai\ClarifaiModels;


class ClarifaiModelsTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = [];
        $data = [
          'model' => [
            'name' => 'model'
          ]
        ];

        $models = new ClarifaiModels($config, $data);

        $this->assertInstanceOf(ClarifaiModels::class, $models);
    }
}


