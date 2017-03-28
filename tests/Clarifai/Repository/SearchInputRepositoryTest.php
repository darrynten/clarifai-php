<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Concept;
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

        $data['query']['ands'] = [];
        $data['query']['ands'] = $this->getOutputConceptsQuery($data['query']['ands'] ,[$concept]);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                $data
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

        $data['query']['ands'] = [];
        $data['query']['ands'] = $this->getInputConceptsQuery($data['query']['ands'] ,[$concept]);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                $data
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
        $input1 = $this->getFullInputEntity()->setId('id1');
        $input2 = $this->getFullInputEntity()->setId('id2');

        $data['query']['ands'] = [];
        $data['query']['ands'] = $this->getReverseImagesQuery($data['query']['ands'] ,[$input1]);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                $data
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
        $input1 = $this->getFullInputEntity()->setId('id1');
        $input2 = $this->getFullInputEntity()->setId('id2');

        $data['query']['ands'] = [];
        $data['query']['ands'] = $this->getImagesQuery($data['query']['ands'] ,[$input1]);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                $data
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

        $data['query']['ands'] = [];
        $data['query']['ands'] = $this->getMetadataQuery($data['query']['ands'] ,[$metadata]);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                $data
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

    public function testSearch()
    {
        $input = $this->getFullInputEntity();
        $metadata = ['first' => 'second'];
        $concept1 = $this->getFullConceptEntity('id1', true);
        $concept2 = $this->getFullConceptEntity('id2', true);

        $data['query']['ands'] = [];
        $data['query']['ands'] = $this->getInputConceptsQuery($data['query']['ands'] ,[$concept1]);
        $data['query']['ands'] = $this->getOutputConceptsQuery($data['query']['ands'] ,[$concept2]);
        $data['query']['ands'] = $this->getMetadataQuery($data['query']['ands'] ,[$metadata]);
        $data['query']['ands'] = $this->getImagesQuery($data['query']['ands'] ,[$input]);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'searches',
                $data
            )
            ->andReturn(
                $this->generateInputSearchResult(
                    [
                        $input->setConcepts([$concept1, $concept2])->setMetaData($metadata),
                    ]
                )
            );

        $this->assertEquals(
            [$input],
            $this->searchInputRepository->search(
                [
                    SearchInputRepository::INPUT_CONCEPTS => [$concept1],
                    SearchInputRepository::OUTPUT_CONCEPTS => [$concept2],
                    SearchInputRepository::IMAGES => [$input],
                    SearchInputRepository::METADATA => [$metadata],
                ]
            )
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

    /**
     * Returns data Query Part
     *
     * @param array $data
     * @param string $type
     *
     * @return array $data
     */
    public function setData($data, $type)
    {
        return [$type => ['data' => $data]];
    }

    /**
     * Generates Input Concept search query and adds it to existing data
     *
     * @param $data
     * @param Concept[] $concepts
     *
     * @return array $data
     */
    public function getInputConceptsQuery($data, $concepts)
    {
        foreach ($concepts as $concept) {
            $data[] = $this->setData(
                [
                    'concepts' => [
                        [
                            'name' => $concept->getName(),
                            'value' => $concept->getValue(),
                        ],
                    ],
                ],
                'input'
            );
        }

        return $data;
    }

    /**
     * Generates Output Concept search query and adds it to existing data
     *
     * @param $data
     * @param Concept[] $concepts
     *
     * @return array $data
     */
    public function getOutputConceptsQuery($data, $concepts)
    {
        foreach ($concepts as $concept) {
            $data[] = $this->setData(
                [
                    'concepts' => [
                        [
                            'name' => $concept->getName(),
                            'value' => $concept->getValue(),
                        ],
                    ],
                ],
                'output'
            );
        }

        return $data;
    }

    /**
     * Generates Metadata search query and adds it to existing data
     *
     * @param $data
     * @param array $metadata
     *
     * @return array $data
     */
    public function getMetadataQuery($data, $metadata)
    {
        foreach ($metadata as $searchMetadata) {
            $data[] = $this->setData(['metadata' => $searchMetadata], 'input');
        }

        return $data;
    }

    /**
     * Generates Image search query and adds it to existing data
     *
     * @param $data
     * @param Input[] $inputs
     *
     * @return array $data
     */
    public function getImagesQuery($data, $inputs)
    {
        foreach ($inputs as $input) {
            $data[] = $this->setData(['image' => ['url' => $input->getImage()]], 'input');
        }

        return $data;
    }

    /**
     * Generates Reverse Image search query and adds it to existing data
     *
     * @param $data
     * @param Input[] $inputs
     *
     * @return array $data
     */
    public function getReverseImagesQuery($data, $inputs)
    {
        foreach ($inputs as $input) {
            $data[] = ['output' => $this->setData(['image' => ['url' => $input->getImage()]], 'input')];
        }

        return $data;
    }

    public function getAndsQuery()
    {
        return ['query' => ['ands' => []]];
    }

}
