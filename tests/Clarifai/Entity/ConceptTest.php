<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\ConceptDataHelper;

class ConceptTest extends \PHPUnit_Framework_TestCase
{
    use ConceptDataHelper;

    /**
     * @var Concept
     */
    private $concept;

    public function setUp()
    {
        $this->concept = new Concept();
    }

    /**
     * @return array
     */
    public function conceptProvider()
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

    /**
     * @dataProvider conceptProvider
     *
     * @param string $method Entity method
     * @param array $mockData Data needed for target class creation
     */
    public function testGettersAndSetters($method, $mockData)
    {
        $this->assertNull($this->concept->{'get' . $method}());
        $this->assertSame(
            $this->concept,
            $this->concept->{'set' . $method}($mockData)
        );
        $this->assertEquals(
            $mockData,
            $this->concept->{'get' . $method}()
        );
    }

    public function testConstructor()
    {
        $this->assertEquals(
            ['id' => null],
            $this->concept->generateRawData()
        );

        $this->concept = new Concept($this->getConceptMock()->generateRawData());

        $this->assertEquals(
            $this->getConceptMock()->getId(),
            $this->concept->getId()
        );
        $this->assertEquals(
            $this->getConceptMock()->getName(),
            $this->concept->getName()
        );
        $this->assertEquals(
            $this->getConceptMock()->getAppId(),
            $this->concept->getAppId()
        );
        $this->assertEquals(
            $this->getConceptMock()->getValue(),
            $this->concept->getValue()
        );
        $this->assertEquals(
            $this->getConceptMock()->getCreatedAt(),
            $this->concept->getCreatedAt()
        );
        $this->assertEquals(
            $this->getConceptMock()->getUpdatedAt(),
            $this->concept->getUpdatedAt()
        );
        $this->assertEquals(
            $this->getConceptMock()->getLanguage(),
            $this->concept->getLanguage()
        );
        $this->assertEquals(
            $this->getConceptMock()->generateRawData(),
            $this->concept->getRawData()
        );
        $this->assertEquals(
            $this->getConceptMock()->generateRawData(),
            $this->concept->generateRawData()
        );
    }
}
