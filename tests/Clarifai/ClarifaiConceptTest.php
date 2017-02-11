<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use PHPUnit_Framework_TestCase;

use DarrynTen\Clarifai\Clarifai;
use DarrynTen\Clarifai\ClarifaiConcept;


class ClarifaiConceptTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = [];
        $data = [];

        $concept = new ClarifaiConcept($config, $data);

        $this->assertInstanceOf(ClarifaiConcept::class, $concept);
    }
}


