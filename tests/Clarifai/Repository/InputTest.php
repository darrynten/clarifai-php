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
}
