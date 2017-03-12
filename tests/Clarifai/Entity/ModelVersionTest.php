<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\ModelVersion;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class modelVersionTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var ModelVersion
     */
    private $modelVersion;

    public function setUp()
    {
        $this->modelVersion = new ModelVersion();
    }

    /**
     * @return array
     */
    public function modelVersionProvider()
    {
        return [
            ['Id', 'id'],
            ['CreatedAt', '2017 - 02 - 24T15:34:10.944942Z'],
            ['StatusCode', '1000'],
            ['StatusDescription', 'OK'],
        ];
    }

    /**
     * @dataProvider modelVersionProvider
     *
     * @param string $method Entity method
     * @param array $mockData Data needed for target class creation
     */
    public function testGettersAndSetters($method, $mockData)
    {
        $this->assertNull($this->modelVersion->{'get' . $method}());
        $this->assertSame(
            $this->modelVersion,
            $this->modelVersion->{'set' . $method}($mockData)
        );
        $this->assertEquals(
            $mockData,
            $this->modelVersion->{'get' . $method}()
        );
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
