<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\Model;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = $this->getModelData();

        $models = new Model($this->getRequestMock(), $config, $data);

        $this->assertInstanceOf(Model::class, $models);
    }
}
