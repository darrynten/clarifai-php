<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Entity\Input;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\ConceptDataHelper;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\InputDataHelper;

class InputTest extends \PHPUnit_Framework_TestCase
{
    use InputDataHelper, ConceptDataHelper;

    /**
     * @var Input
     */
    private $input;

    public function setUp()
    {
        $this->input = new Input();
    }

    /**
     * @return array
     */
    public function inputProvider()
    {
        return [
            ['Id', 'f1a03eb89ad04b99b88431e3466c56ac'],
            ['CreatedAt', '2017 - 02 - 24T15:34:10.944942Z'],
            ['Image', 'https://samples.clarifai.com/metro-north.jpg'],
            ['ImageMethod', Input::IMG_BASE64],
            ['ImageMethod', Input::IMG_URL],
            ['ImageMethod', Input::IMG_PATH],
            ['Crop', [0.2, 0.4, 0.3, 0.6]],
            ['MetaData', ['first' => 'value1', 'second' => 'value2']],

        ];
    }

    /**
     * @dataProvider inputProvider
     *
     * @param string $method Entity method
     * @param array $mockData Data needed for target class creation
     */
    public function testGettersAndSetters($method, $mockData)
    {
        $this->assertNull($this->input->{'get' . $method}());
        $this->assertSame(
            $this->input,
            $this->input->{'set' . $method}($mockData)
        );
        $this->assertEquals(
            $mockData,
            $this->input->{'get' . $method}()
        );
    }

    public function testStatus()
    {
        $status = ['code' => '3000', 'description' => 'string'];
        $this->assertEquals(
            ['code' => '', 'description' => ''],
            $this->input->getStatus()
        );
        $this->assertSame(
            $this->input,
            $this->input->setStatus($status['code'], $status['description'])
        );
        $this->assertEquals(
            $status,
            $this->input->getStatus()
        );
    }

    public function testImageMethods()
    {
        $this->assertNull($this->input->getImageMethod());
        $this->input->isPath();
        $this->assertEquals(
            Input::IMG_PATH,
            $this->input->getImageMethod()
        );
        $this->input->isUrl();
        $this->assertEquals(
            Input::IMG_URL,
            $this->input->getImageMethod()
        );
        $this->input->isEncoded();
        $this->assertEquals(
            Input::IMG_BASE64,
            $this->input->getImageMethod()
        );
    }

    public function testConstructor()
    {
        $conceptData1 = $this->getConceptConstructData('id1', 'name1', 'appId1', true);
        $conceptData2 = $this->getConceptConstructData('id2', 'name2', 'appId2', false);

        $data = $this->getInputFullConstructData(
            'id',
            'image',
            Input::IMG_URL
        );
        $data['data']['concepts'] = [$conceptData1, $conceptData2];

        $this->input = new Input($data);

        $this->assertEquals(
            $data['id'],
            $this->input->getId()
        );
        $this->assertEquals(
            $data['created_at'],
            $this->input->getCreatedAt()
        );
        $this->assertEquals(
            $data['status'],
            $this->input->getStatus()
        );
        $this->assertEquals(
            $data['data']['image']['url'],
            $this->input->getImage()
        );
        $this->assertEquals(
            Input::IMG_URL,
            $this->input->getImageMethod()
        );
        $this->assertEquals(
            $data['data']['image']['crop'],
            $this->input->getCrop()
        );
        $this->assertEquals(
            $data['data']['metadata'],
            $this->input->getMetaData()
        );
        $this->assertEquals(
            [new Concept($conceptData1), new Concept($conceptData2)],
            $this->input->getConcepts()
        );

    }

    /**
     * @expectedException \Exception
     */
    public function testWrongConstructImageMethodException()
    {
        $this->input = new Input(
            [
                'id' => 'f1a03eb89ad04b99b88431e3466c56ac',
                'created_at' => '2017 - 02 - 24T15:34:10.944942Z',
                'status' => [
                    'code' => '300000',
                    'description' => 'Ok',
                ],
                'data' => [
                    'image' => [
                        'wrong_url' => 'https://samples.clarifai.com/metro-north.jpg',
                    ],
                ],

            ]
        );
    }

    public function testSetConcepts()
    {
        $this->assertEquals(
            [],
            $this->input->getConcepts()
        );
        $concept1 = $this->getConceptEntity('id1', true);
        $concept2 = $this->getConceptEntity('id2', false);
        $this->assertSame(
            $this->input,
            $this->input->setConcepts([$concept1, $concept2])
        );
        $this->assertEquals(
            [$concept1, $concept2],
            $this->input->getConcepts()
        );

    }

    public function testSetRawConcepts()
    {
        $this->assertEquals(
            [],
            $this->input->getConcepts()
        );
        $data1 = $this->getConceptConstructData('id1', 'name1', 'appId1', true);
        $data2 = $this->getConceptConstructData('id2', 'name2', 'appId2', false);

        $this->assertSame(
            $this->input,
            $this->input->setRawConcepts([$data1, $data2])
        );
        $this->assertEquals(
            [new Concept($data1), new Concept($data2)],
            $this->input->getConcepts()
        );
    }

    public function testSetRawData()
    {
        $this->assertEquals(
            [],
            $this->input->getRawData()
        );
        $data = $this->getInputFullConstructData('id', 'image', Input::IMG_URL);
        $this->assertSame(
            $this->input,
            $this->input->setRawData($data)
        );
        $this->assertEquals(
            $data,
            $this->input->getRawData()
        );

    }
}
