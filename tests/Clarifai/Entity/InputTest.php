<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Entity;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Entity\Input;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\ConceptDataHelper;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\InputDataHelper;

class InputTest extends EntityTest
{
    use InputDataHelper, ConceptDataHelper;

    /**
     * @var Input
     */
    protected $entity;

    public function setUp()
    {
        $this->entity = new Input();
    }

    /**
     * @return array
     */
    public function setterGetterProvider()
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

    public function testStatus()
    {
        $status = ['code' => '3000', 'description' => 'string'];
        $this->assertEquals(
            ['code' => '', 'description' => ''],
            $this->entity->getStatus()
        );
        $this->assertSame(
            $this->entity,
            $this->entity->setStatus($status['code'], $status['description'])
        );
        $this->assertEquals(
            $status,
            $this->entity->getStatus()
        );
    }

    public function testImageMethods()
    {
        $this->assertNull($this->entity->getImageMethod());
        $this->entity->isPath();
        $this->assertEquals(
            Input::IMG_PATH,
            $this->entity->getImageMethod()
        );
        $this->entity->isUrl();
        $this->assertEquals(
            Input::IMG_URL,
            $this->entity->getImageMethod()
        );
        $this->entity->isEncoded();
        $this->assertEquals(
            Input::IMG_BASE64,
            $this->entity->getImageMethod()
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

        $this->entity = new Input($data);

        $this->assertEquals(
            $data['id'],
            $this->entity->getId()
        );
        $this->assertEquals(
            $data['created_at'],
            $this->entity->getCreatedAt()
        );
        $this->assertEquals(
            $data['status'],
            $this->entity->getStatus()
        );
        $this->assertEquals(
            $data['data']['image']['url'],
            $this->entity->getImage()
        );
        $this->assertEquals(
            Input::IMG_URL,
            $this->entity->getImageMethod()
        );
        $this->assertEquals(
            $data['data']['image']['crop'],
            $this->entity->getCrop()
        );
        $this->assertEquals(
            $data['data']['metadata'],
            $this->entity->getMetaData()
        );
        $this->assertEquals(
            [new Concept($conceptData1), new Concept($conceptData2)],
            $this->entity->getConcepts()
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testWrongConstructImageMethodException()
    {
        $this->entity = new Input(
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
            $this->entity->getConcepts()
        );
        $concept1 = $this->getConceptEntity('id1', true);
        $concept2 = $this->getConceptEntity('id2', false);
        $this->assertSame(
            $this->entity,
            $this->entity->setConcepts([$concept1, $concept2])
        );
        $this->assertEquals(
            [$concept1, $concept2],
            $this->entity->getConcepts()
        );
    }

    public function testSetRawConcepts()
    {
        $this->assertEquals(
            [],
            $this->entity->getConcepts()
        );
        $data1 = $this->getConceptConstructData('id1', 'name1', 'appId1', true);
        $data2 = $this->getConceptConstructData('id2', 'name2', 'appId2', false);

        $this->assertSame(
            $this->entity,
            $this->entity->setRawConcepts([$data1, $data2])
        );
        $this->assertEquals(
            [new Concept($data1), new Concept($data2)],
            $this->entity->getConcepts()
        );
    }

    public function testSetRawData()
    {
        $this->assertEquals(
            [],
            $this->entity->getRawData()
        );
        $data = $this->getInputFullConstructData('id', 'image', Input::IMG_URL);
        $this->assertSame(
            $this->entity,
            $this->entity->setRawData($data)
        );
        $this->assertEquals(
            $data,
            $this->entity->getRawData()
        );
    }
}
