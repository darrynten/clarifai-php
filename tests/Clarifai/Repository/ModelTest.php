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

    /**
     * Set Request mock for predict calls
     *
     * @param array $expectedImageData
     * @param string $modelType
     * @param string $lang
     * @param string $expectedData
     */
    private function setRequestMock(array $expectedImageData, string $modelType, string $lang, string $expectedData)
    {
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                sprintf('models/%s/outputs', $modelType),
                [
                    'inputs' => [
                        [
                            'data' => [
                                'image' => $expectedImageData,
                            ],
                        ],
                    ],
                    'model' => [
                        'output_info' => [
                            'output_config' =>[
                                'language' => $lang,
                            ],
                        ],
                    ],
                ]
            )
            ->andReturn($expectedData);
    }

    public function testPredictUrl()
    {
        $url = 'url';
        $modelType = 'type';
        $lang = 'lang';
        $expectedData = 'data';

        $this->setRequestMock(['url' => $url], $modelType, $lang, $expectedData);

        $this->assertEquals(
            $expectedData,
            $this->model->predictUrl($url, $modelType, $lang)
        );
    }

    public function testPredictPath()
    {
        $file = __FILE__;
        $modelType = 'type';
        $lang = 'lang';
        $expectedData = 'data';

        $this->setRequestMock(
            [
                'base64' => base64_encode(file_get_contents($file))
            ],
            $modelType,
            $lang,
            $expectedData
        );

        $this->assertEquals(
            $expectedData,
            $this->model->predictPath($file, $modelType, $lang)
        );
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function testPredictPathException()
    {
        $this->model->predictPath('path', 'model', 'ru');
    }

    public function testPredictEncoded()
    {
        $hash = 'hash';
        $modelType = 'type';
        $lang = 'lang';
        $expectedData = 'data';

        $this->setRequestMock(
            [
                'base64' => $hash
            ],
            $modelType,
            $lang,
            $expectedData
        );

        $this->assertEquals(
            $expectedData,
            $this->model->predictEncoded($hash, $modelType, $lang)
        );
    }
}
