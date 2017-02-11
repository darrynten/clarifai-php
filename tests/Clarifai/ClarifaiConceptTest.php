<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\ClarifaiConcept;
use PHPUnit_Framework_TestCase;

class ClarifaiConceptTest extends PHPUnit_Framework_TestCase
{
    use DataHelper;

    public function testConstruct()
    {
        $config = [];
        $data = $this->getModelData();

        $concept = new ClarifaiConcept($config, $data);

        $this->assertInstanceOf(ClarifaiConcept::class, $concept);
    }
}
