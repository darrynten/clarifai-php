<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\BaseRepository;
use DarrynTen\Clarifai\Repository\Input;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class InputTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var Input
     */
    private $input;

    /**
     * @var \Mockery\MockInterface|\DarrynTen\Clarifai\Request\RequestHandler
     */
    private $request;

    public function setUp()
    {
        $this->request = $this->getRequestMock();
        $this->input = new Input($this->request, [], []);
    }

    public function testInstanceOfModel()
    {
        $this->assertInstanceOf(Input::class, $this->input);
        $this->assertInstanceOf(BaseRepository::class, $this->input);
    }

    public function testAdd()
    {
        $image_url = 'image';
        $image_path = __FILE__;
        $image_hash = 'hash';
        $crop = [0.1, 0.2, 0.5];
        $concepts = ['first' => true, 'second' => true];
        $metadata = ['first' => 'value1', 'second' => 'value2'];

        $image1 = [
                'image' => $image_url,
                'method' => 'url',
                'crop' => $crop,
                'id' => 'id1',
            ];
        $image2 = [
            'image' => $image_path,
            'method' => 'path',
            'concepts' => $concepts,
            'id' => 'id2',
        ];
        $image3 = [
            'image' => $image_hash,
            'method' => 'base64',
            'metadata' => $metadata,
            'id' => 'id3',
        ];

        $image4 = [
            'image' => $image_url,
        ];

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
                        ]
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->input->add($image1)
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
            $this->input->add($image2)
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
            $this->input->add($image3)
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
                        ]
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->input->add($image4)
        );
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function testGenerateImageAddressException()
    {
        $this->input->generateImageAddress('path', 'path');
    }

    public function testGenerateImageAddress()
    {
        $image_url = 'image';
        $image_path = __FILE__;
        $image_hash = 'hash';

        $this->assertEquals(
            ['url' => $image_url],
            $this->input->generateImageAddress($image_url, 'url')
        );

        $this->assertEquals(
            ['base64' => base64_encode(file_get_contents($image_path))],
            $this->input->generateImageAddress($image_path, 'path')
        );

        $this->assertEquals(
            ['base64' => $image_hash],
            $this->input->generateImageAddress($image_hash, 'base64')
        );

    }

    public function testAddImageId()
    {
        $id = 'image_id';

        $this->assertEquals(
            ['id' => $id],
            $this->input->addImageId([], $id)
        );
    }

    public function testAddImageCrop()
    {
        $crop = [0.1, 0.2, 0.5];

        $this->assertEquals(
            ['crop' => $crop],
            $this->input->addImageCrop([], $crop)
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
            $this->input->addImageConcepts([], $concepts)
        );
    }

    public function testAddImageMetadata()
    {

        $metadata = ['first' => 'value1', 'second' => 'value2'];

        $this->assertEquals(
            [
                'metadata' => ['first' => 'value1', 'second' => 'value2'],
            ],
            $this->input->addImageMetadata([], $metadata)
        );
    }

    public function testAddMultiple()
    {
        $image_url = 'image';
        $image_path = __FILE__;
        $image_hash = 'hash';
        $crop = [0.1, 0.2, 0.5];
        $concepts = ['first' => true, 'second' => true];
        $metadata = ['first' => 'value1', 'second' => 'value2'];
        $images = [
            [
                'image' => $image_url,
                'method' => 'url',
                'crop' => $crop,
                'id' => 'id1',
            ],
            [
                'image' => $image_path,
                'method' => 'path',
                'concepts' => $concepts,
                'id' => 'id2',
            ],
            [
                'image' => $image_hash,
                'method' => 'base64',
                'metadata' => $metadata,
                'id' => 'id3',
            ],

        ];

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
            $this->input->addMultiple($images)
        );
    }
}
