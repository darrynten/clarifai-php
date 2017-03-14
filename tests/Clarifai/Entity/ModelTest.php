<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Entity;

use DarrynTen\Clarifai\Entity\Model;
use DarrynTen\Clarifai\Entity\ModelVersion;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ModelTest extends EntityTest
{
    use DataHelper;

    /**
     * @var Model
     */
    protected $model;

    public function setUp()
    {
        $this->entity = new Model();
        $this->model = new Model();
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
            ['Concepts', ['data' => 'value'], true],
        ];
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
