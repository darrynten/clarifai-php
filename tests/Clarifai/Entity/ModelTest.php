<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Model;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var Model
     */
    private $model;

    public function setUp()
    {
        $this->model = new Model();
    }

    /**
     * @return array
     */
    public function modelProvider()
    {
        return [
            ['Id', 'id'],
            ['CreatedAt', '2017 - 02 - 24T15:34:10.944942Z'],
            ['Name', 'name'],
            ['AppId', 'appId'],
            ['ModelVersion', $this->getModelVersionEntity()],
        ];
    }

    /**
     * @dataProvider modelProvider
     *
     * @param string $method Entity method
     * @param array $mockData Data needed for target class creation
     */
    public function testGettersAndSetters($method, $mockData)
    {
        $this->assertNull($this->model->{'get' . $method}());
        $this->assertSame(
            $this->model,
            $this->model->{'set' . $method}($mockData)
        );
        $this->assertEquals(
            $mockData,
            $this->model->{'get' . $method}()
        );
    }

    public function testConstructor()
    {
        $this->assertEquals(
            ['id' => null, 'output_info' => ['data' => []]],
            $this->model->generateRawData()
        );

        $this->model = new Model($this->getModelEntity()->generateRawData());

        $this->assertEquals(
            $this->getModelEntity()->getId(),
            $this->model->getId()
        );
        $this->assertEquals(
            $this->getModelEntity()->getName(),
            $this->model->getName()
        );
        $this->assertEquals(
            $this->getModelEntity()->getAppId(),
            $this->model->getAppId()
        );
        $this->assertEquals(
            $this->getModelEntity()->getCreatedAt(),
            $this->model->getCreatedAt()
        );
        $this->assertEquals(
            $this->getModelEntity()->generateRawData(),
            $this->model->getRawData()
        );
        $this->assertEquals(
            $this->getModelEntity()->generateRawData(),
            $this->model->generateRawData()
        );
        $this->assertEquals(
            $this->getModelEntity()->getConcepts(),
            $this->model->getConcepts()
        );
    }

    public function testOutputConfig()
    {
        $this->assertEquals(
            null,
            $this->model->isClosedEnvironment()
        );
        $this->assertEquals(
            null,
            $this->model->isConceptsMutuallyExclusive()
        );
        $this->model = new Model($this->getModelEntity()->generateRawData());
        $this->assertEquals(
            [
                'closed_environment' => $this->model->isClosedEnvironment(),
                'concepts_mutually_exclusive' => $this->model->isConceptsMutuallyExclusive(),
            ],
            $this->model->getOutputConfig()
        );
    }
}
