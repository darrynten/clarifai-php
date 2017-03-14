<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\ModelVersion;
use DarrynTen\Clarifai\Tests\Clarifai\Entity\EntityTest;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class modelVersionTest extends EntityTest
{
    use DataHelper;

    /**
     * @var ModelVersion
     */
    private $modelVersion;

    public function setUp()
    {
        $this->modelVersion = new ModelVersion();
        $this->entity = new ModelVersion();
    }

    /**
     * @return array
     */
    public function setterGetterProvider()
    {
        return [
            ['Id', 'id'],
            ['CreatedAt', '2017 - 02 - 24T15:34:10.944942Z'],
            ['StatusCode', '1000'],
            ['StatusDescription', 'OK'],
        ];
    }

    public function testConstructor()
    {
        $this->assertEquals(
            ['id' => null],
            $this->modelVersion->generateRawData()
        );

        $this->modelVersion = new ModelVersion($this->getModelVersionEntity()->generateRawData());

        $this->assertEquals(
            $this->getModelVersionEntity()->getId(),
            $this->modelVersion->getId()
        );
        $this->assertEquals(
            $this->getModelVersionEntity()->getCreatedAt(),
            $this->modelVersion->getCreatedAt()
        );
        $this->assertEquals(
            $this->getModelVersionEntity()->getStatusCode(),
            $this->modelVersion->getStatusCode()
        );
        $this->assertEquals(
            $this->getModelVersionEntity()->getStatusDescription(),
            $this->modelVersion->getStatusDescription()
        );
        $this->assertEquals(
            $this->getModelVersionEntity()->generateRawData(),
            $this->modelVersion->generateRawData()
        );
    }
}
