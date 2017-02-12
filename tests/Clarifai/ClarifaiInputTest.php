<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\ClarifaiInput;
use PHPUnit_Framework_TestCase;

class ClarifaiInputTest extends PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = $this->getInputData();

        $input = new ClarifaiInput($config, $data);

        $this->assertInstanceOf(ClarifaiInput::class, $input);
    }
}
