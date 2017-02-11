<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use PHPUnit_Framework_TestCase;

use DarrynTen\Clarifai\Clarifai;
use DarrynTen\Clarifai\ClarifaiModel;


class ClarifaiModelTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = [];
        $data = [];

        $models = new ClarifaiModel($config, $data);

        $this->assertInstanceOf(ClarifaiModel::class, $models);
    }
}


