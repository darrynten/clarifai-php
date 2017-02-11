<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use PHPUnit_Framework_TestCase;

use DarrynTen\Clarifai\Clarifai;
use DarrynTen\Clarifai\ClarifaiInput;


class ClarifaiInputTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = [];
        $data = [];

        $input = new ClarifaiInput($config, $data);

        $this->assertInstanceOf(ClarifaiInput::class, $input);
    }
}


