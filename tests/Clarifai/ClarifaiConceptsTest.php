<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\ClarifaiConcepts;
use PHPUnit_Framework_TestCase;

class ClarifaiConceptsTest extends PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = [
            'concept' => $this->getModelData()
        ];

        $concept = new ClarifaiConcepts($config, $data);

        $this->assertInstanceOf(ClarifaiConcepts::class, $concept);
    }
}
