<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Input;
use DarrynTen\Clarifai\Repository\BaseRepository;
use DarrynTen\Clarifai\Repository\InputRepository;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class InputRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var InputRepository
     */
    private $inputRepository;

    /**
     * @var \Mockery\MockInterface|\DarrynTen\Clarifai\Request\RequestHandler
     */
    private $request;

    public function setUp()
    {
        $this->request = $this->getRequestMock();
        $this->inputRepository = new InputRepository($this->request, [], []);
    }

    public function testInstanceOfModel()
    {
        $this->assertInstanceOf(InputRepository::class, $this->inputRepository);
        $this->assertInstanceOf(BaseRepository::class, $this->inputRepository);
    }

    public function testAdd()
    {
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                $this->getAddRequest()
            )
            ->andReturn($this->getInputResponse());

        $this->assertEquals(
            $this->getInputCollectionMock(),
            $this->inputRepository->add($this->getInputCollectionMock())
        );
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function testGenerateImageAddressException()
    {
        $this->inputRepository->generateImageAddress('path', 'path');
    }

    public function testGenerateImageAddress()
    {
        $image_url = 'image';
        $image_path = __FILE__;
        $image_hash = 'hash';

        $this->assertEquals(
            ['url' => $image_url],
            $this->inputRepository->generateImageAddress($image_url, 'url')
        );

        $this->assertEquals(
            ['base64' => base64_encode(file_get_contents($image_path))],
            $this->inputRepository->generateImageAddress($image_path, 'path')
        );

        $this->assertEquals(
            ['base64' => $image_hash],
            $this->inputRepository->generateImageAddress($image_hash, 'base64')
        );

    }

    public function testAddImageId()
    {
        $id = 'image_id';

        $this->assertEquals(
            ['id' => $id],
            $this->inputRepository->addImageId([], $id)
        );
    }

    public function testAddImageCrop()
    {
        $crop = [0.1, 0.2, 0.5];

        $this->assertEquals(
            ['crop' => $crop],
            $this->inputRepository->addImageCrop([], $crop)
        );
    }

    public function testAddImageConcepts()
    {

        $concepts = ['first' => true, 'second' => false];

        $this->assertEquals(
            [
                'concepts' => [
                    ['id' => 'first', 'value' => true],
                    ['id' => 'second', 'value' => false],
                ],
            ],
            $this->inputRepository->addImageConcepts([], $concepts)
        );
    }

    public function testAddImageMetadata()
    {

        $metadata = ['first' => 'value1', 'second' => 'value2'];

        $this->assertEquals(
            [
                'metadata' => ['first' => 'value1', 'second' => 'value2'],
            ],
            $this->inputRepository->addImageMetadata([], $metadata)
        );
    }

    public function testGet()
    {
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs'
            )
            ->andReturn($this->getInputResponse());

        $this->assertEquals(
            $this->getInputCollectionMock(),
            $this->inputRepository->get()
        );
    }

    public function testGetById()
    {
        $input = $this->getOneInputMock();

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs/' . $input->getId()
            )
            ->andReturn($this->getOneInputResponse($input));

        $this->assertEquals(
            $input,
            $this->inputRepository->getById($input->getId())
        );
    }

    public function testGetStatus()
    {
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs/status'
            )
            ->andReturn(
                [
                    'status' => [
                        'code' => '1000',
                        'description' => 'Ok',
                    ],
                    'counts' =>
                        [
                            'processed' => 0,
                            'to_process' => 0,
                            'errors' => 0,
                        ],
                ]
            );

        $this->assertEquals(
            [
                'processed' => 0,
                'to_process' => 0,
                'errors' => 0,
            ],
            $this->inputRepository->getStatus()
        );
    }

    public function testDeleteById()
    {
        $id = '123';
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'DELETE',
                sprintf('inputs/%s', $id)
            )
            ->andReturn(
                [
                    'status' => [
                        'code' => '1000',
                        'description' => 'Ok',
                    ],
                ]
            );

        $this->assertEquals(
            [
                'code' => '1000',
                'description' => 'Ok',
            ],
            $this->inputRepository->deleteById($id)
        );
    }

    public function testDeleteByIdArray()
    {
        $ids = ['123', '321'];
        $data['ids'] = $ids;
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'DELETE',
                'inputs',
                $data
            )
            ->andReturn(
                [
                    'status' => [
                        'code' => '1000',
                        'description' => 'Ok',
                    ],
                ]
            );

        $this->assertEquals(
            [
                'code' => '1000',
                'description' => 'Ok',
            ],
            $this->inputRepository->deleteByIdArray($ids)
        );
    }

    public function deleteAll()
    {
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'DELETE',
                'inputs',
                ['delete_all' => true]
            )
            ->andReturn(
                [
                    'status' => [
                        'code' => '1000',
                        'description' => 'Ok',
                    ],
                ]
            );

        $this->assertEquals(
            [
                'code' => '1000',
                'description' => 'Ok',
            ],
            $this->inputRepository->deleteAll()
        );
    }

    public function getInputResponse()
    {
        return [
            'status' => [
                'code' => '1000',
                'description' => 'Ok',
            ],
            'inputs' => [
                [
                    'id' => 'f1a03eb89ad04b99b88431e3466c56ac',
                    'created_at' => '2017 - 02 - 24T15:34:10.944953Z',
                    'data' => [
                        'image' => [
                            'url' => 'http://www.chicco.com.ua/thumbs/d04efbc9ae8b518fcbe3997fe5fbbf46c1e4b90f_130x130.jpeg',
                        ],
                        'metadata' => [
                            'first' => 'value1',
                            'second' => 'value2',
                        ],
                    ],
                    'status' => [
                        'code' => '30001',
                        'description' => 'Download pending',
                    ],
                ],
                [
                    'id' => '1234567',
                    'created_at' => '2017 - 02 - 24T15:34:10.944942Z',
                    'data' => [
                        'image' => [
                            'url' => 'http://www.chicco.com.ua/thumbs/online_games_chicco_rus_130X80_130x130.jpg',
                            'crop' => ['0.2', '0.4', '0.3', '0.6'],
                        ],
                    ],
                    'status' => [
                        'code' => '30001',
                        'description' => 'Download pending',
                    ],
                ],
                [
                    'id' => '3',
                    'created_at' => '2017 - 02 - 24T15:36:10.944942Z',
                    'data' => [
                        'image' => [
                            'base64' => 'hash',
                        ],
                    ],
                    'status' => [
                        'code' => '30001',
                        'description' => 'Download pending',
                    ],
                ],
            ],
        ];
    }

    /**
     * Output data for Add Request
     *
     * @return array
     */
    public function getAddRequest()
    {
        return [
            'inputs' => [
                [
                    'id' => 'f1a03eb89ad04b99b88431e3466c56ac',
                    'data' => [
                        'image' => [
                            'url' => 'http://www.chicco.com.ua/thumbs/d04efbc9ae8b518fcbe3997fe5fbbf46c1e4b90f_130x130.jpeg',
                        ],
                        'metadata' => [
                            'first' => 'value1',
                            'second' => 'value2',
                        ],
                    ],
                ],
                [
                    'id' => '1234567',
                    'data' => [
                        'image' => [
                            'url' => 'http://www.chicco.com.ua/thumbs/online_games_chicco_rus_130X80_130x130.jpg',
                            'crop' => ['0.2', '0.4', '0.3', '0.6'],
                        ],
                    ],
                ],
                [
                    'id' => '3',
                    'data' => [
                        'image' => [
                            'base64' => 'hash',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Array of Test Inputs
     *
     * @return array
     */
    public function getInputCollectionMock()
    {

        $input1 = new Input();
        $input1->setId('f1a03eb89ad04b99b88431e3466c56ac')
            ->setImage('http://www.chicco.com.ua/thumbs/d04efbc9ae8b518fcbe3997fe5fbbf46c1e4b90f_130x130.jpeg')
            ->isUrl()
            ->setMetaData(['first' => 'value1', 'second' => 'value2'])
            ->setStatus('30001', 'Download pending')
            ->setCreatedAt('2017 - 02 - 24T15:34:10.944953Z');

        $input2 = new Input();
        $input2->setId('1234567')
            ->setImage('http://www.chicco.com.ua/thumbs/online_games_chicco_rus_130X80_130x130.jpg')
            ->isUrl()
            ->setCrop([0.2, 0.4, 0.3, 0.6])
            ->setStatus('30001', 'Download pending')
            ->setCreatedAt('2017 - 02 - 24T15:34:10.944942Z');

        $input3 = new Input();
        $input3->setId('3')
            ->setImage('hash')
            ->isEncoded()
            ->setStatus('30001', 'Download pending')
            ->setCreatedAt('2017 - 02 - 24T15:36:10.944942Z');

        return [$input1, $input2, $input3];
    }

    /**
     * Output data for GetById Response
     *
     * @param Input $input
     *
     * @return array
     */
    public function getOneInputResponse(Input $input)
    {
        return [
            'status' => [
                'code' => '1000',
                'description' => 'Ok',
            ],
            'input' => [
                'id' => $input->getId(),
                'created_at' => $input->getCreatedAt(),
                'data' => [
                    'image' => [
                        'url' => $input->getImage(),
                    ],
                ],
                'status' => $input->getStatus(),

            ],
        ];
    }

    /**
     * One Input Entity
     *
     * @return Input
     */
    public function getOneInputMock()
    {
        $input = new Input();
        $input->setId('1234567')
            ->setImage('http://www.chicco.com.ua/thumbs/online_games_chicco_rus_130X80_130x130.jpg')
            ->isUrl()
            ->setStatus('30001', 'Download pending')
            ->setCreatedAt('2017 - 02 - 24T15:34:10.944942Z');

        return $input;
    }
}
