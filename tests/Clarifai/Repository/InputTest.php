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
        $url = 'url';
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
                                'image' => ['url' => $url],
                            ],
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->input->addUrl($url)
        );
    }

    public function testAddPath()
    {
        $file = __FILE__;
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
                                    'base64' => base64_encode(file_get_contents($file)),
                                ],
                            ],
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->input->addPath($file)
        );
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function testAddPathException()
    {
        $this->input->addPath('path');
    }

    public function testAddEncoded()
    {
        $hash = 'hash';
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
                                    'base64' => $hash,
                                ],
                            ],
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->input->addEncoded($hash)
        );
    }

    public function testAddMultipleIdsByUrl()
    {
        $image1 = ['image' => 'path1', 'id' => '1'];
        $image2 = ['image' => 'path2', 'id' => '2'];
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
                                'image' => ['url' => $image1['image']],
                            ],
                            'id' => $image1['id'],
                        ],
                        [
                            'data' => [
                                'image' => ['url' => $image2['image']],
                            ],
                            'id' => $image2['id'],
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->input->addMultipleIdsByUrl([$image1, $image2])
        );
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function testAddMultipleIdsByPathException()
    {
        $this->input->addMultipleIdsByPath([['image' => 'path', 'id' => '1']]);
    }

    public function testAddMultipleIdsByPath()
    {
        $image1 = ['image' => __FILE__, 'id' => '1'];
        $image2 = ['image' => __FILE__, 'id' => '2'];

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
                                'image' => ['base64' => base64_encode(file_get_contents($image1['image']))],
                            ],
                            'id' => $image1['id'],
                        ],
                        [
                            'data' => [
                                'image' => ['base64' => base64_encode(file_get_contents($image2['image']))],
                            ],
                            'id' => $image2['id'],
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->input->addMultipleIdsByPath([$image1, $image2])
        );
    }

    public function testAddMultipleIdsByEncoded()
    {
        $image1 = ['image' => 'hash1', 'id' => '1'];
        $image2 = ['image' => 'hash2', 'id' => '2'];
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
                                'image' => ['base64' => $image1['image']],
                            ],
                            'id' => $image1['id'],
                        ],
                        [
                            'data' => [
                                'image' => ['base64' => $image2['image']],
                            ],
                            'id' => $image2['id'],
                        ],
                    ],

                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->input->addMultipleIdsByEncoded([$image1, $image2])
        );
    }
}
