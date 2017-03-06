<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Concept;

class ConceptTest extends \PHPUnit_Framework_TestCase
{
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
            ['Name', 'name'],
            ['AppId', 'appId'],
            ['Value', true],
            ['Value', false],
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

    public function testRawData()
    {
        $this->assertEquals(
            [],
            $this->concept->getRawData()
        );
        $this->assertEquals(
            ['id' => null, 'value' => null],
            $this->concept->generateRawData()
        );
        $id = 'id';
        $value = 1;
        $data = $this->getConceptConstructData($id, 'name', 'appId', $value);
        $this->concept = new Concept($data);
        $this->assertEquals(
            $data,
            $this->concept->getRawData()
        );
        $this->assertEquals(
            ['id' => $id, 'value' => $value],
            $this->concept->generateRawData()
        );
    }

    public function testConstructor()
    {
        $data = $this->getConceptConstructData('id', 'name', 'appId', 1);

        $this->concept = new Concept($data);

        $this->assertEquals(
            $data['id'],
            $this->concept->getId()
        );
        $this->assertEquals(
            $data['name'],
            $this->concept->getName()
        );
        $this->assertEquals(
            $data['app_id'],
            $this->concept->getAppId()
        );
        $this->assertEquals(
            $data['value'],
            $this->concept->getValue()
        );
        $this->assertEquals(
            $data,
            $this->concept->getRawData()
        );
    }

    /**
     * Gets RawData for Concept Constructor
     *
     * @param $id
     * @param $name
     * @param $appId
     * @param $value
     *
     * @return array
     */
    public function getConceptConstructData($id, $name, $appId, $value)
    {
        return [
            'id' => $id,
            'name' => $name,
            'app_id' => $appId,
            'value' => $value,
        ];
    }


}
