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
        $image_url = 'image';
        $image_path = __FILE__;
        $image_hash = 'hash';
        $crop = [0.1, 0.2, 0.5];
        $concepts = ['first' => true, 'second' => true];
        $metadata = ['first' => 'value1', 'second' => 'value2'];

        $image1 = new Input($image_url, Input::IMG_URL);
        $image1->setId('id1')->setCrop($crop);

        $image2 = new Input($image_path, Input::IMG_PATH);
        $image2->setId('id2')->setConcepts($concepts);

        $image3 = new Input($image_hash, Input::IMG_BASE64);
        $image3->setId('id3')->setMetaData($metadata);

        $image4 = new Input($image_url);

        $expectedData = 'data';

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                [
                    'inputs' => [
                        [
                            'data' => [
                                'image' => [
                                    'url' => $image_url,
                                    'crop' => $crop,
                                ],
                            ],
                            'id' => 'id1',
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->inputRepository->add($image1)
        );

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                [
                    'inputs' => [
                        [
                            'data' => [
                                'image' => ['base64' => base64_encode(file_get_contents($image_path))],
                                'concepts' => [
                                    ['id' => 'first', 'value' => true],
                                    ['id' => 'second', 'value' => true],
                                ],
                            ],
                            'id' => 'id2',
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->inputRepository->add($image2)
        );

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                [
                    'inputs' => [
                        [
                            'data' => [
                                'image' => ['base64' => $image_hash],
                                'metadata' => ['first' => 'value1', 'second' => 'value2'],
                            ],
                            'id' => 'id3',
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->inputRepository->add($image3)
        );

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                [
                    'inputs' => [
                        [
                            'data' => [
                                'image' => [
                                    'url' => $image_url,
                                ],
                            ],
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->inputRepository->add($image4)
        );

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                [
                    'inputs' => [
                        [
                            'data' => [
                                'image' => [
                                    'url' => $image_url,
                                    'crop' => $crop,
                                ],
                            ],
                            'id' => 'id1',
                        ],
                        [
                            'data' => [
                                'image' => ['base64' => base64_encode(file_get_contents($image_path))],
                                'concepts' => [
                                    ['id' => 'first', 'value' => true],
                                    ['id' => 'second', 'value' => true],
                                ],
                            ],
                            'id' => 'id2',
                        ],
                        [
                            'data' => [
                                'image' => ['base64' => $image_hash],
                                'metadata' => ['first' => 'value1', 'second' => 'value2'],
                            ],
                            'id' => 'id3',
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->inputRepository->add([$image1, $image2, $image3])
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
}
