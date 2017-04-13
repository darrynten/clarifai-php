<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\BaseRepository;
use DarrynTen\Clarifai\Repository\ModelRepository;
use DarrynTen\Clarifai\Repository\SearchModelRepository;
use DarrynTen\Clarifai\Tests\Clarifai\Entity\EntityTest;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class SearchModelRepositoryTest extends EntityTest
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
        $this->entity = new SearchModelRepository($this->request, [], []);
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
                    'model_query' => [
                        'name' => $model1->getName(),
                        'type' => 'concept',
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
            $this->searchModelRepository->searchByNameAndType($model1->getName(), 'concept')
        );
    }

    /**
     * BaseRepository's property Test
     *
     * @return array
     */
    public function setterGetterProvider()
    {
        return [
            ['Page', '2'],
            ['PerPage', '2'],
        ];
    }

    /**
     * BaseRepository's Method Test
     */
    public function testGetRequestPageInfo()
    {
        $perPage = 20;
        $page = 10;

        $this->assertEquals(
            '?page=&per_page=',
            $this->entity->getRequestPageInfo()
        );

        $this->assertEquals(
            '?page=' . $page . '&per_page=' . $perPage,
            $this->entity->setPerPage($perPage)->setPage($page)->getRequestPageInfo()
        );
    }

    /**
     * BaseRepository's Method Test
     */
    public function testGetRequestUrl()
    {
        $url = 'some_url';
        $perPage = 20;
        $page = 10;

        $this->assertEquals(
            $url,
            $this->entity->getRequestUrl($url)
        );

        $this->assertEquals(
            $url . '?page=' . $page . '&per_page=' . $perPage,
            $this->entity->setPerPage($perPage)->setPage($page)->getRequestUrl($url)
        );
    }
}
