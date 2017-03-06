<?php

namespace DarrynTen\Clarifai\Tests\Clarifai;

use DarrynTen\Clarifai\Clarifai;
use DarrynTen\Clarifai\Repository;
use DarrynTen\Clarifai\Request\RequestHandler;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ClarifayTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var Clarifai
     */
    private $clarifai;

    public function setUp()
    {
        $this->clarifai = new Clarifai('clientId', 'clientSecret');
    }

    /**
     * @return array
     */
    public function repositoryProvider()
    {
        return [
            [Repository\InputRepository::class, 'getInputRepository', $this->getInputData()],
            [Repository\InputsRepository::class, 'getInputsRepository', []],
            [Repository\ModelRepository::class, 'getModelRepository', $this->getModelData()],
            [Repository\Models::class, 'getModels', []],
        ];
    }

    /**
     * @dataProvider repositoryProvider
     *
     * @param Repository\BaseRepository $expectedClass
     * @param string $call The Clarifai call
     * @param array $mockData Data needed for target class creation
     */
    public function testExpectedRepositoryCallResult($expectedClass, $call, $mockData)
    {
        $repository = $this->clarifai->{$call}([], $mockData);
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
