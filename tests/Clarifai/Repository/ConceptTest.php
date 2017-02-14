<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\Concept;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ConceptTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = $this->getModelData();

        $concept = new Concept($this->getRequestMock(), $config, $data);

        $this->assertInstanceOf(Concept::class, $concept);
    }
}
