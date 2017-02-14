<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\Models;
use PHPUnit_Framework_TestCase;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ModelsTest extends PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = [
            'model' => $this->getModelData(),
        ];

        $models = new Models($config, $data);

        $this->assertInstanceOf(Models::class, $models);
    }
}
