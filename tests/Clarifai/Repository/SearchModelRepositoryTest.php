<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\BaseRepository;
use DarrynTen\Clarifai\Repository\ModelRepository;
use DarrynTen\Clarifai\Repository\SearchModelRepository;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class SearchModelRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var SearchModelRepository
     */
    private $searchModelRepository;

    /**
     * @var \Mockery\MockInterface|\DarrynTen\Clarifai\Request\RequestHandler
     */
    private $request;

    public function setUp()
    {
        $this->request = $this->getRequestMock();
        $this->searchModelRepository = new SearchModelRepository($this->request, [], []);
    }

    public function testInstanceOfModel()
    {
        $this->assertInstanceOf(ModelRepository::class, $this->searchModelRepository);
        $this->assertInstanceOf(SearchModelRepository::class, $this->searchModelRepository);
        $this->assertInstanceOf(BaseRepository::class, $this->searchModelRepository);
    }

    public function testSearchByNameAndType()
    {
        $model1 = $this->getModelEntity();
        $model1->setRawData($model1->generateRawData());

        $model2 = $this->getModelEntity()->setId('id2');
        $model2->setRawData($model2->generateRawData());

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'models/searches',
                [
                    "model_query" => [
                        "name" => $model1->getName(),
                        "type" => 'concept',
                    ],

                ]
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'models' => [$model1->generateRawData(), $model2->generateRawData(),],
                ]
            );

        $this->assertEquals(
            [$model1, $model2],
            $this->searchModelRepository->searchByNameAndType($model1->getName(),'concept')
        );
    }
}
