<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Entity;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Entity\Model;
use DarrynTen\Clarifai\Entity\ModelVersion;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ModelTest extends EntityTest
{
    use DataHelper;

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
///**
// * @return array
// */
//public function modelProvider()
//{
//    return [
//        ['Id', 'id'],
//        ['CreatedAt', '2017 - 02 - 24T15:34:10.944942Z'],
//        ['Name', 'name'],
//        ['AppId', 'appId'],
//        ['ModelVersion', $this->getModelVersionEntity()],
//    ];
//}
//
///**
// * @dataProvider modelProvider
// *
// * @param string $method Entity method
// * @param array $mockData Data needed for target class creation
// */
//public function testGettersAndSetters($method, $mockData)
//{
//    $this->assertNull($this->model->{'get' . $method}());
//    $this->assertSame(
//        $this->model,
//        $this->model->{'set' . $method}($mockData)
//    );
//    $this->assertEquals(
//        $mockData,
//        $this->model->{'get' . $method}()
//    );
//}
//
//public function testConstructor()
//{
//    $this->assertEquals(
//        ['id' => null, 'output_info' => ['data' => []]],
//        $this->model->generateRawData()
//    );
//
//    $this->model = new Model($this->getModelEntity()->generateRawData());
//
//    $this->assertEquals(
//        $this->getModelEntity()->getId(),
//        $this->model->getId()
//    );
//    $this->assertEquals(
//        $this->getModelEntity()->getName(),
//        $this->model->getName()
//    );
//    $this->assertEquals(
//        $this->getModelEntity()->getAppId(),
//        $this->model->getAppId()
//    );
//    $this->assertEquals(
//        $this->getModelEntity()->getCreatedAt(),
//        $this->model->getCreatedAt()
//    );
//    $this->assertEquals(
//        $this->getModelEntity()->generateRawData(),
//        $this->model->getRawData()
//    );
//    $this->assertEquals(
//        $this->getModelEntity()->generateRawData(),
//        $this->model->generateRawData()
//    );
//    $this->assertEquals(
//        $this->getModelEntity()->getConcepts(),
//        $this->model->getConcepts()
//    );
//}
//
//public function testOutputConfig()
//{
//    $this->assertEquals(
//        null,
//        $this->model->isClosedEnvironment()
//    );
//    $this->assertEquals(
//        null,
//        $this->model->isConceptsMutuallyExclusive()
//    );
//    $this->model = new Model($this->getModelEntity()->generateRawData());
//    $this->assertEquals(
//        [
//            'closed_environment' => $this->model->isClosedEnvironment(),
//            'concepts_mutually_exclusive' => $this->model->isConceptsMutuallyExclusive(),
//        ],
//        $this->model->getOutputConfig()
//    );
//}
