<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\Concepts;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;
use PHPUnit_Framework_TestCase;

class ConceptsTest extends PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = [
            'concept' => $this->getModelData()
        ];

        $concept = new Concepts($config, $data);

        $this->assertInstanceOf(Concepts::class, $concept);
    }
}
