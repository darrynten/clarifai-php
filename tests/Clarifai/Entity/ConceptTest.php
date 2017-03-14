<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Tests\Clarifai\Entity\EntityTest;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ConceptTest extends EntityTest
{
    use DataHelper;

    /**
     * @var Concept
     */
    private $concept;

    public function setUp()
    {
        $this->entity = new Concept();
        $this->concept = new Concept();
    }

    /**
     * @return array
     */
    public function setterGetterProvider()
    {
        return [
            ['Id', 'id'],
            ['CreatedAt', '2017 - 02 - 24T15:34:10.944942Z'],
            ['UpdatedAt', '2017 - 02 - 24T15:34:10.944942Z'],
            ['Name', 'name'],
            ['AppId', 'appId'],
            ['Value', true],
            ['Value', false],
            ['Language', 'en'],
        ];
    }

    public function testConstructor()
    {
        $id = 'id';
        $value = true;

        $this->assertEquals(
            ['id' => null],
            $this->concept->generateRawData()
        );

        $this->concept = new Concept($this->getFullConceptEntity($id, $value)->generateRawData());

        $this->assertEquals(
            $this->getFullConceptEntity($id, $value)->getId(),
            $this->concept->getId()
        );
        $this->assertEquals(
            $this->getFullConceptEntity($id, $value)->getName(),
            $this->concept->getName()
        );
        $this->assertEquals(
            $this->getFullConceptEntity($id, $value)->getAppId(),
            $this->concept->getAppId()
        );
        $this->assertEquals(
            $this->getFullConceptEntity($id, $value)->getValue(),
            $this->concept->getValue()
        );
        $this->assertEquals(
            $this->getFullConceptEntity($id, $value)->getCreatedAt(),
            $this->concept->getCreatedAt()
        );
        $this->assertEquals(
            $this->getFullConceptEntity($id, $value)->getUpdatedAt(),
            $this->concept->getUpdatedAt()
        );
        $this->assertEquals(
            $this->getFullConceptEntity($id, $value)->getLanguage(),
            $this->concept->getLanguage()
        );
        $this->assertEquals(
            $this->getFullConceptEntity($id, $value)->generateRawData(),
            $this->concept->getRawData()
        );
        $this->assertEquals(
            $this->getFullConceptEntity($id, $value)->generateRawData(),
            $this->concept->generateRawData()
        );
    }
}
