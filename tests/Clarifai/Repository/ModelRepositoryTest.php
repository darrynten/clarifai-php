<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Repository\BaseRepository;
use DarrynTen\Clarifai\Repository\ModelRepository;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class ModelRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

    /**
     * @var ModelRepository
     */
    private $modelRepository;

    /**
     * @var \Mockery\MockInterface|\DarrynTen\Clarifai\Request\RequestHandler
     */
    private $request;

    public function setUp()
    {
        $this->request = $this->getRequestMock();
        $this->modelRepository = new ModelRepository($this->request, [], []);
    }

    public function testInstanceOfModel()
    {
        $this->assertInstanceOf(ModelRepository::class, $this->modelRepository);
        $this->assertInstanceOf(BaseRepository::class, $this->modelRepository);
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
                            'output_config' => [
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
            $this->modelRepository->predictUrl($url, $modelType, $lang)
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
                'base64' => base64_encode(file_get_contents($file)),
            ],
            $modelType,
            $lang,
            $expectedData
        );

        $this->assertEquals(
            $expectedData,
            $this->modelRepository->predictPath($file, $modelType, $lang)
        );
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function testPredictPathException()
    {
        $this->modelRepository->predictPath('path', 'model', 'ru');
    }

    public function testPredictEncoded()
    {
        $hash = 'hash';
        $modelType = 'type';
        $lang = 'lang';
        $expectedData = 'data';

        $this->setRequestMock(
            [
                'base64' => $hash,
            ],
            $modelType,
            $lang,
            $expectedData
        );

        $this->assertEquals(
            $expectedData,
            $this->modelRepository->predictEncoded($hash, $modelType, $lang)
        );
    }

    public function testCreate()
    {
        $model = $this->getModelEntity();
        $model->setRawData($model->generateRawData());

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'models',
                ['model' => $this->modelRepository->createModelData($model)]
            )
            ->andReturn(['status' => $this->getStatusResult(), 'model' => $model->generateRawData()]);

        $this->assertEquals(
            $model,
            $this->modelRepository->create($model)
        );
    }

    public function testMergeModelConcepts()
    {
        $model = $this->getModelEntity();
        $model->setRawData($model->generateRawData());
        $concept1 = $this->getFullConceptEntity('id1');
        $concept2 = $this->getFullConceptEntity('id2');

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'PATCH',
                'models',
                [
                    'models' => [
                        [
                            'id' => $model->getId(),
                            'output_info' => [
                                'data' => [
                                    'concepts' => [
                                        ['id' => $concept1->getId()],
                                        ['id' => $concept2->getId()],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'action' => ModelRepository::CONCEPTS_MERGE_ACTION,
                ]
            )
            ->andReturn(
                ['status' => $this->getStatusResult(), 'models' => [$model->generateRawData()]]
            );

        $this->assertEquals(
            [$model],
            $this->modelRepository->mergeModelConcepts([$model->getId() => [$concept1, $concept2]])
        );
    }

    public function testDeleteModelConcepts()
    {
        $model = $this->getModelEntity();
        $model->setRawData($model->generateRawData());
        $concept1 = $this->getFullConceptEntity('id1');
        $concept2 = $this->getFullConceptEntity('id2');

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'PATCH',
                'models',
                [
                    'models' => [
                        [
                            'id' => $model->getId(),
                            'output_info' => [
                                'data' => [
                                    'concepts' => [
                                        ['id' => $concept1->getId()],
                                        ['id' => $concept2->getId()],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'action' => ModelRepository::CONCEPTS_REMOVE_ACTION,
                ]
            )
            ->andReturn(
                ['status' => $this->getStatusResult(), 'models' => [$model->generateRawData()]]
            );

        $this->assertEquals(
            [$model],
            $this->modelRepository->deleteModelConcepts([$model->getId() => [$concept1, $concept2]])
        );
    }

    public function testOverwriteModelConcepts()
    {
        $model = $this->getModelEntity();
        $model->setRawData($model->generateRawData());
        $concept1 = $this->getFullConceptEntity('id1');
        $concept2 = $this->getFullConceptEntity('id2');

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'PATCH',
                'models',
                [
                    'models' => [
                        [
                            'id' => $model->getId(),
                            'output_info' => [
                                'data' => [
                                    'concepts' => [
                                        ['id' => $concept1->getId()],
                                        ['id' => $concept2->getId()],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'action' => ModelRepository::CONCEPTS_OVERWRITE_ACTION,
                ]
            )
            ->andReturn(
                ['status' => $this->getStatusResult(), 'models' => [$model->generateRawData()]]
            );

        $this->assertEquals(
            [$model],
            $this->modelRepository->overwriteModelConcepts([$model->getId() => [$concept1, $concept2]])
        );
    }

    public function testUpdate()
    {
        $name = 'app2';
        $conceptsMutuallyExclusive = true;
        $closedEnvironment = 0;
        $model = $this->getModelEntity()->setName($name)
            ->setConceptsMutuallyExclusive($conceptsMutuallyExclusive)
            ->setClosedEnvironment($closedEnvironment)
            ->setConcepts([]);
        $model->setRawData($model->generateRawData());

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'PATCH',
                'models',
                [
                    'models' => [
                        [
                            'id' => $model->getId(),
                            'name' => $model->getName(),
                            'output_info' => [
                                'output_config' => [
                                    'concepts_mutually_exclusive' => $model->isConceptsMutuallyExclusive(),
                                    'closed_environment' => $model->isClosedEnvironment(),
                                ],
                            ],
                        ],
                    ],
                    'action' => 'merge',
                ]
            )
            ->andReturn(
                ['status' => $this->getStatusResult(), 'models' => [$model->generateRawData()]]
            );

        $this->assertEquals(
            [$model],
            $this->modelRepository->update($model)
        );
        $this->assertEquals(
            $name,
            $model->getName()
        );
        $this->assertEquals(
            $conceptsMutuallyExclusive,
            $model->isConceptsMutuallyExclusive()
        );
        $this->assertEquals(
            $closedEnvironment,
            $model->isClosedEnvironment()
        );
    }

    public function testGet()
    {
        $model1 = $this->getModelEntity();
        $model1->setRawData($model1->generateRawData());

        $model2 = $this->getModelEntity()->setId('id2');
        $model2->setRawData($model2->generateRawData());

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'models'
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'models' => [$model1->generateRawData(), $model2->generateRawData(),],
                ]
            );

        $this->assertEquals(
            [$model1, $model2],
            $this->modelRepository->get()
        );
    }

    public function testGetById()
    {
        $model = $this->getModelEntity();
        $model->setRawData($model->generateRawData());

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                sprintf('models/%s', $model->getId())
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'model' => $model->generateRawData(),
                ]
            );

        $this->assertEquals(
            $model,
            $this->modelRepository->getById($model->getId())
        );
    }

    public function testGetOutputInfoById()
    {
        $model = $this->getModelEntity();
        $model->setRawData($model->generateRawData());

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                sprintf('models/%s/output_info', $model->getId())
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'model' => $model->generateRawData(),
                ]
            );

        $this->assertEquals(
            $model,
            $this->modelRepository->getOutputInfoById($model->getId())
        );
    }

    public function testGetModelVersions()
    {
        $id = 'model_id';
        $version1 = $this->getModelVersionEntity();
        $version2 = $this->getModelVersionEntity()->setId('id2');

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                sprintf('models/%s/versions', $id)
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'model_versions' => [
                        $version1->generateRawData(),
                        $version2->generateRawData(),
                    ],
                ]
            );

        $this->assertEquals(
            [$version1, $version2],
            $this->modelRepository->getModelVersions($id)
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testGetModelVersionsException()
    {
        $id = 'model_id';
        $version1 = $this->getModelVersionEntity();
        $version2 = $this->getModelVersionEntity()->setId('id2');

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                sprintf('models/%s/versions', $id)
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'wrong_data' => [
                        $version1->generateRawData(),
                        $version2->generateRawData(),
                    ],
                ]
            );

        $this->assertEquals(
            [$version1, $version2],
            $this->modelRepository->getModelVersions($id)
        );
    }

    public function testGetModelVersionById()
    {
        $id = 'model_id';
        $version = $this->getModelVersionEntity();

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                sprintf('models/%s/versions/%s', $id, $version->getId())
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'model_version' =>
                        $version->generateRawData(),

                ]
            );

        $this->assertEquals(
            $version,
            $this->modelRepository->getModelVersionById($id, $version->getId())
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testGetModelVersionByIdException()
    {
        $id = 'model_id';
        $version = $this->getModelVersionEntity();

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                sprintf('models/%s/versions/%s', $id, $version->getId())
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'wrong_data' =>
                        $version->generateRawData(),

                ]
            );

        $this->assertEquals(
            $version,
            $this->modelRepository->getModelVersionById($id, $version->getId())
        );
    }

    public function testGetTrainingInputsById()
    {
        $modelId = 'model_id';
        $input1 = $this->getFullInputEntity();
        $input2 = $this->getFullInputEntity()->setId('id2');

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                sprintf('models/%s/inputs', $modelId)
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'inputs' => [
                        $input1->generateRawData(),
                        $input2->generateRawData(),
                    ],
                ]
            );

        $this->assertEquals(
            [$input1, $input2],
            $this->modelRepository->getTrainingInputsById($modelId)
        );
    }

    public function testGetTrainingInputsByVersion()
    {
        $modelId = 'model_id';
        $versionId = 'versionId';
        $input1 = $this->getFullInputEntity();
        $input2 = $this->getFullInputEntity()->setId('id2');

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                sprintf('models/%s/versions/%s/inputs', $modelId, $versionId)
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'inputs' => [
                        $input1->generateRawData(),
                        $input2->generateRawData(),
                    ],
                ]
            );

        $this->assertEquals(
            [$input1, $input2],
            $this->modelRepository->getTrainingInputsByVersion($modelId, $versionId)
        );
    }

    public function testDeleteById()
    {
        $modelId = 'model_id';

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'DELETE',
                sprintf('models/%s', $modelId)
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                ]
            );

        $this->assertEquals(
            $this->getStatusResult(),
            $this->modelRepository->deleteById($modelId)
        );
    }

    public function testDeleteVersionById()
    {
        $modelId = 'model_id';
        $versionId = 'version_id';

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'DELETE',
                sprintf('models/%s/versions/%s', $modelId, $versionId)
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                ]
            );

        $this->assertEquals(
            $this->getStatusResult(),
            $this->modelRepository->deleteVersionById($modelId, $versionId)
        );
    }

    public function testDeleteAll()
    {
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'DELETE',
                'models',
                ['delete_all' => true]
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                ]
            );

        $this->assertEquals(
            $this->getStatusResult(),
            $this->modelRepository->deleteAll()
        );
    }

    public function testTrain()
    {
        $model = $this->getModelEntity();
        $model->setRawData($model->generateRawData());

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                sprintf('models/%s/versions', $model->getId())
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'model' => $model->generateRawData(),
                ]
            );

        $this->assertEquals(
            $model,
            $this->modelRepository->train($model->getId())
        );
    }
}
