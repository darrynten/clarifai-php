<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\Clarifai;
use DarrynTen\Clarifai\Repository;
use DarrynTen\Clarifai\Request\RequestHandler;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ClarifaiTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var Clarifai
     */
    private $clarifai;

    public function setUp()
    {
        $this->clarifai = new Clarifai('apiKey');
    }

    /**
     * @return array
     */
    public function repositoryProvider()
    {
        return [
            [Repository\InputRepository::class, 'getInputRepository'],
            [Repository\SearchInputRepository::class, 'getSearchInputRepository'],
            [Repository\ModelRepository::class, 'getModelRepository'],
            [Repository\SearchModelRepository::class, 'getSearchModelRepository'],
        ];
    }

    /**
     * @dataProvider repositoryProvider
     *
     * @param Repository\BaseRepository $expectedClass
     * @param string $call The Clarifai call
     */
    public function testExpectedRepositoryCallResult($expectedClass, $call)
    {
        $repository = $this->clarifai->{$call}();
        $this->assertInstanceOf(
            $expectedClass,
            $repository
        );
        $this->assertInstanceOf(
            Repository\BaseRepository::class,
            $repository
        );
    }

    public function testRequestGetterResult()
    {
        $this->assertInstanceOf(
            RequestHandler::class,
            $this->clarifai->getRequest()
        );
    }
}
