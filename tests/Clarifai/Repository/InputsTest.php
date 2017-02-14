<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\Inputs;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;
use PHPUnit_Framework_TestCase;

class InputsTest extends PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = [
            'input' => $this->getInputData()
        ];

        $inputs = new Inputs($config, $data);

        $this->assertInstanceOf(Inputs::class, $inputs);
    }
}
