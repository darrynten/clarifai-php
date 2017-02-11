<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\ClarifaiModels;
use PHPUnit_Framework_TestCase;

class ClarifaiModelsTest extends PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = [
            'model' => $this->getModelData(),
        ];

        $models = new ClarifaiModels($config, $data);

        $this->assertInstanceOf(ClarifaiModels::class, $models);
    }
}
