<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use PHPUnit_Framework_TestCase;

use DarrynTen\Clarifai\Clarifai;
use DarrynTen\Clarifai\ClarifaiConcepts;


class ClarifaiConceptsTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $config = [];
        $data = [
          'concept' => [
            'name' => 'concept'
          ]
        ];

        $concept = new ClarifaiConcepts($config, $data);

        $this->assertInstanceOf(ClarifaiConcepts::class, $concept);
    }
}


