<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\Input;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class InputTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = $this->getInputData();

        $input = new Input($this->getRequestMock(), $config, $data);

        $this->assertInstanceOf(Input::class, $input);
    }
}
