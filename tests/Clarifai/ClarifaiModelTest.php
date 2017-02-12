<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\ClarifaiModel;
use PHPUnit_Framework_TestCase;

class ClarifaiModelTest extends PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = $this->getModelData();

        $models = new ClarifaiModel($config, $data);

        $this->assertInstanceOf(ClarifaiModel::class, $models);
    }
}
