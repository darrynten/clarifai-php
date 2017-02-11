<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use PHPUnit_Framework_TestCase;

use DarrynTen\Clarifai\Clarifai;
use DarrynTen\Clarifai\ClarifaiInputs;


class ClarifaiInputsTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = [];
        $data = [
          'input' => [
            'name' => 'input'
          ]
        ];

        $inputs = new ClarifaiInputs($config, $data);

        $this->assertInstanceOf(ClarifaiInputs::class, $inputs);
    }
}


