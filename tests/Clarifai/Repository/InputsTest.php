<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\Inputs;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class InputsTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = [
            'input' => $this->getInputData()
        ];

        $inputs = new Inputs($this->getRequestMock(), $config, $data);

        $this->assertInstanceOf(Inputs::class, $inputs);
    }
}
