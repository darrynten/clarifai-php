<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\BaseRepository;
use DarrynTen\Clarifai\Repository\Model;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ModelTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var Model
     */
    private $model;

    /**
     * @var \Mockery\MockInterface|\DarrynTen\Clarifai\Request\RequestHandler
     */
    private $request;

    public function setUp()
    {
        $this->request = $this->getRequestMock();
        $this->model = new Model($this->request, [], []);
    }

    public function testInstanceOfModel()
    {
        $this->assertInstanceOf(Model::class, $this->model);
        $this->assertInstanceOf(BaseRepository::class, $this->model);
    }

    public function testPredictFromUrl()
    {
        $url = 'url';
        $modelType = 'type';
        $lang = 'lang';
        $expectedData = 'data';

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                sprintf('models/%s/outputs', $modelType),
                [
                    'inputs' => [
                        [
                            'data' => [
                                'image' =>[
                                    'url' => 'url',
                                ],
                            ],
                        ],
                    ],
                    'model' => [
                        'output_info' => [
                            'output_config' =>[
                                'language' => 'lang',
                            ],
                        ],
                    ],
                ]
            )
            ->andReturn($expectedData);

        $this->assertEquals(
            $expectedData,
            $this->model->predictFromUrl($url, $modelType, $lang)
        );
    }
}
