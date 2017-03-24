<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Input;
use DarrynTen\Clarifai\Repository\BaseRepository;
use DarrynTen\Clarifai\Repository\InputRepository;
use DarrynTen\Clarifai\Repository\SearchInputRepository;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class SearchInputRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var SearchInputRepository
     */
    private $searchInputRepository;

    /**
     * @var \Mockery\MockInterface|\DarrynTen\Clarifai\Request\RequestHandler
     */
    private $request;

    public function setUp()
    {
        $this->request = $this->getRequestMock();
        $this->searchInputRepository = new SearchInputRepository($this->request, [], []);
    }

    public function testInstanceOfModel()
    {
        $this->assertInstanceOf(InputRepository::class, $this->searchInputRepository);
        $this->assertInstanceOf(SearchInputRepository::class, $this->searchInputRepository);
        $this->assertInstanceOf(BaseRepository::class, $this->searchInputRepository);
    }

    public function testSearchByPredictedConcepts()
    {
        $input = $this->getFullInputEntity();
        $concept = $this->getFullConceptEntity('id1', true);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                [
                    'query' => [
                        'ands' => [
                            [
                                'output' => [
                                    'data' => [
                                        'concepts' => [
                                            [
                                                'name' => $concept->getName(),
                                                'value' => $concept->getValue(),
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            )
            ->andReturn($this->generateInputSearchResult([$input->setConcepts([$concept])]));

        $this->assertEquals(
            [$input],
            $this->searchInputRepository->searchByPredictedConcepts([$concept])
        );
    }

    public function testSearchByUserSuppliedConcepts()
    {
        $input1 = $this->getFullInputEntity();
        $input2 = $this->getFullInputEntity()->setId('id2')->setImage('image2');

        $concept = $this->getFullConceptEntity('id1', true);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                [
                    'query' => [
                        'ands' => [
                            [
                                'input' => [
                                    'data' => [
                                        'concepts' => [
                                            [
                                                'name' => $concept->getName(),
                                                'value' => $concept->getValue(),
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            )
            ->andReturn(
                $this->generateInputSearchResult(
                    [
                        $input1->setConcepts([$concept]),
                        $input2->setConcepts([$concept]),
                    ]
                )
            );

        $this->assertEquals(
            [$input1, $input2],
            $this->searchInputRepository->searchByUserSuppliedConcepts([$concept])
        );
    }

    public function testSearchByReverseImage()
    {
        $input1 = $this->getFullInputEntity()->setId('id1')->setImage('image');
        $input2 = $this->getFullInputEntity()->setId('id2')->setImage('image');

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                [
                    'query' => [
                        'ands' => [
                            [
                                'output' => [
                                    'input' => [
                                        'data' => [
                                            'image' => [
                                                'url' => $input1->getImage(),
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            )
            ->andReturn(
                $this->generateInputSearchResult(
                    [
                        $input1,
                        $input2,
                    ]
                )
            );

        $this->assertEquals(
            [$input1, $input2],
            $this->searchInputRepository->searchByReverseImage([$input1])
        );
    }

    public function testSearchByMatchUrl()
    {
        $input1 = $this->getFullInputEntity()->setId('id1')->setImage('image');
        $input2 = $this->getFullInputEntity()->setId('id2')->setImage('image');

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                [
                    'query' => [
                        'ands' => [
                            [
                                'input' => [
                                    'data' => [
                                        'image' => [
                                            'url' => $input1->getImage(),
                                        ],
                                    ],
                                ],

                            ],
                        ],
                    ],
                ]
            )
            ->andReturn(
                $this->generateInputSearchResult(
                    [
                        $input1,
                        $input2,
                    ]
                )
            );

        $this->assertEquals(
            [$input1, $input2],
            $this->searchInputRepository->searchByMatchUrl([$input1])
        );
    }

    public function testSearchByCustomMetadata()
    {
        $metadata = ['first' => 'second'];

        $input1 = $this->getFullInputEntity()->setId('id1')->setMetaData($metadata);
        $input2 = $this->getFullInputEntity()->setId('id2')->setMetaData($metadata);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                [
                    'query' => [
                        'ands' => [
                            [
                                'input' => [
                                    'data' => [
                                        'metadata' => $metadata,
                                    ],
                                ],

                            ],
                        ],
                    ],
                ]
            )
            ->andReturn(
                $this->generateInputSearchResult(
                    [
                        $input1,
                        $input2,
                    ]
                )
            );

        $this->assertEquals(
            [$input1, $input2],
            $this->searchInputRepository->searchByCustomMetadata([$metadata])
        );
    }

    /**
     * Generates Input Search Result
     *
     * @param Input[] $inputs
     *
     * @return array
     */
    public function generateInputSearchResult($inputs)
    {
        $data = [];
        $data['status'] = $this->getStatusResult();
        $data['id'] = 'random_id';
        $data['hits'] = [];
        foreach ($inputs as $input) {
            $data['hits'][] = [
                'score' => '824723',
                'input' => $input->generateRawData(),
            ];
        }

        return $data;
    }
}
