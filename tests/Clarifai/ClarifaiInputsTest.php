<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\ClarifaiInputs;
use PHPUnit_Framework_TestCase;

class ClarifaiInputsTest extends PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = [
            'input' => $this->getInputData()
        ];

        $inputs = new ClarifaiInputs($config, $data);

        $this->assertInstanceOf(ClarifaiInputs::class, $inputs);
    }
}
