<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Entity;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Entity\Model;
use DarrynTen\Clarifai\Entity\ModelVersion;

class ModelTest extends EntityTest
{
    /**
     * @var Model
     */
    protected $entity;

    public function setUp()
    {
        $this->entity = new Model();
    }

    /**
     * @return array
     */
    public function setterGetterProvider()
    {
        return [
            ['Id', 'f1a03eb89ad04b99b88431e3466c56ac'],
            ['Name', 'Awesome Model'],
            ['CreatedAt', 'date'],
            ['AppId', 'Application Id'],
            ['ModelVersion', new ModelVersion()],
            ['RawData', ['data' => 'value'], true],
            ['Concepts', ['data' => 'value'], true]
        ];
    }

    public function testIsMutuallyExclusive()
    {
        $this->entity->setConceptsMutuallyExclusive(true);
        $this->assertTrue($this->entity->isConceptsMutuallyExclusive());
    }

    public function testIsClosedEnvironment()
    {
        $this->entity->setClosedEnvironment(true);
        $this->assertTrue($this->entity->isClosedEnvironment());
    }

    public function testOutputConfig()
    {
        $this->entity->setClosedEnvironment(true)
            ->setConceptsMutuallyExclusive(true);
        $this->assertEquals(
            [
                'concepts_mutually_exclusive' => true,
                'closed_environment' => true,
            ],
            $this->entity->getOutputConfig()
        );
    }

    public function testRawConcepts()
    {
        $rawConceptsData = [
            [
                'id' => 'first id',
            ],
            [
                'id' => 'second id',
            ],
        ];

        $this->assertEmpty($this->entity->getConcepts());
        $this->entity->setRawConcepts($rawConceptsData);
        $this->assertInternalType('array', $this->entity->getConcepts());
        $this->assertCount(2, $this->entity->getConcepts());
        $this->assertInstanceOf(Concept::class, $this->entity->getConcepts()[0]);
        $this->assertEquals(
            $rawConceptsData[0]['id'],
            $this->entity->getConcepts()[0]->getId()
        );
    }

    //@todo Constructor test
}
