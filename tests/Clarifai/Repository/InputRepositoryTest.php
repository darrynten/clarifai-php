<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Entity\Input;
use DarrynTen\Clarifai\Repository\BaseRepository;
use DarrynTen\Clarifai\Repository\InputRepository;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\ConceptDataHelper;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\InputDataHelper;

class InputRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper, InputDataHelper, ConceptDataHelper;

    /**
     * @var InputRepository
     */
    private $inputRepository;

    /**
     * @var \Mockery\MockInterface|\DarrynTen\Clarifai\Request\RequestHandler
     */
    private $request;

    public function setUp()
    {
        $this->request = $this->getRequestMock();
        $this->inputRepository = new InputRepository($this->request, [], []);
    }

    public function testInstanceOfModel()
    {
        $this->assertInstanceOf(InputRepository::class, $this->inputRepository);
        $this->assertInstanceOf(BaseRepository::class, $this->inputRepository);
    }

    public function testAdd()
    {
        $input1 = $this->getFullInputMock('id1', 'image1', Input::IMG_URL);
        $input2 = $this->getFullInputMock('id2', 'image2', Input::IMG_BASE64);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                $this->getAddRequest($input1)
            )
            ->andReturn($this->getInputResponse($input1));

        $this->assertEquals(
            [$input1],
            $this->inputRepository->add($input1)
        );

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                $this->getAddRequest([$input1, $input2])
            )
            ->andReturn($this->getInputResponse([$input1, $input2]));

        $this->assertEquals(
            [$input1, $input2],
            $this->inputRepository->add([$input1, $input2])
        );
    }

    /**
     * @expectedException \Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException
     */
    public function testGenerateImageAddressException()
    {
        $this->inputRepository->generateImageAddress('path', 'path');
    }

    public function testGenerateImageAddress()
    {
        $image_url = 'image';
        $image_path = __FILE__;
        $image_hash = 'hash';

        $this->assertEquals(
            ['url' => $image_url],
            $this->inputRepository->generateImageAddress($image_url, 'url')
        );

        $this->assertEquals(
            ['base64' => base64_encode(file_get_contents($image_path))],
            $this->inputRepository->generateImageAddress($image_path, 'path')
        );

        $this->assertEquals(
            ['base64' => $image_hash],
            $this->inputRepository->generateImageAddress($image_hash, 'base64')
        );

    }

    public function testAddImageId()
    {
        $id = 'image_id';

        $this->assertEquals(
            ['id' => $id],
            $this->inputRepository->addImageId([], $id)
        );
    }

    public function testAddImageCrop()
    {
        $crop = [0.1, 0.2, 0.5];

        $this->assertEquals(
            ['crop' => $crop],
            $this->inputRepository->addImageCrop([], $crop)
        );
    }

    public function testAddImageConcepts()
    {
        $concept1 = new Concept();
        $concept1->setId('first')->setValue(true);
        $concept2 = new Concept();
        $concept2->setId('second')->setValue(false);
        $concepts = [$concept1, $concept2];

        $this->assertEquals(
            [
                'concepts' => [
                    ['id' => 'first', 'value' => true],
                    ['id' => 'second', 'value' => false],
                ],
            ],
            $this->inputRepository->addImageConcepts([], $concepts)
        );
    }

    public function testAddImageMetadata()
    {

        $metadata = ['first' => 'value1', 'second' => 'value2'];

        $this->assertEquals(
            [
                'metadata' => ['first' => 'value1', 'second' => 'value2'],
            ],
            $this->inputRepository->addImageMetadata([], $metadata)
        );
    }

    public function testGet()
    {
        $input1 = $this->getFullInputMock('id1', 'image1', Input::IMG_URL);
        $input2 = $this->getFullInputMock('id2', 'image2', Input::IMG_BASE64);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs'
            )
            ->andReturn($this->getInputResponse([$input1, $input2]));

        $this->assertEquals(
            [$input1, $input2],
            $this->inputRepository->get()
        );
    }

    public function testGetById()
    {
        $input = $input1 = $this->getFullInputMock('id1', 'image1', Input::IMG_URL);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs/' . $input->getId()
            )
            ->andReturn($this->getOneInputResponse($input));

        $this->assertEquals(
            $input,
            $this->inputRepository->getById($input->getId())
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testGetByIdException()
    {
        $input = $input1 = $this->getFullInputMock('id1', 'image1', Input::IMG_URL);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs/' . $input->getId()
            )
            ->andReturn('data');

        $this->inputRepository->getById($input->getId());
    }

    public function testGetStatus()
    {
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs/status'
            )
            ->andReturn(
                [
                    'status' => [
                        'code' => '1000',
                        'description' => 'Ok',
                    ],
                    'counts' =>
                        [
                            'processed' => 0,
                            'to_process' => 0,
                            'errors' => 0,
                        ],
                ]
            );

        $this->assertEquals(
            [
                'processed' => 0,
                'to_process' => 0,
                'errors' => 0,
            ],
            $this->inputRepository->getStatus()
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testGetStatusException()
    {
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs/status'
            )
            ->andReturn('data');

        $this->inputRepository->getStatus();
    }

    public function testDeleteById()
    {
        $id = '123';
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'DELETE',
                sprintf('inputs/%s', $id)
            )
            ->andReturn(
                [
                    'status' => [
                        'code' => '1000',
                        'description' => 'Ok',
                    ],
                ]
            );

        $this->assertEquals(
            [
                'code' => '1000',
                'description' => 'Ok',
            ],
            $this->inputRepository->deleteById($id)
        );
    }

    public function testDeleteByIdArray()
    {
        $ids = ['123', '321'];
        $data['ids'] = $ids;
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'DELETE',
                'inputs',
                $data
            )
            ->andReturn(
                [
                    'status' => [
                        'code' => '1000',
                        'description' => 'Ok',
                    ],
                ]
            );

        $this->assertEquals(
            [
                'code' => '1000',
                'description' => 'Ok',
            ],
            $this->inputRepository->deleteByIdArray($ids)
        );
    }

    public function testDeleteAll()
    {
        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'DELETE',
                'inputs',
                ['delete_all' => true]
            )
            ->andReturn(
                [
                    'status' => [
                        'code' => '1000',
                        'description' => 'Ok',
                    ],
                ]
            );

        $this->assertEquals(
            [
                'code' => '1000',
                'description' => 'Ok',
            ],
            $this->inputRepository->deleteAll()
        );
    }

    public function testMergeInputConcepts()
    {

        $input1 = $this->getFullInputMock('id1', 'image1', Input::IMG_URL);
        $input1->setConcepts([]);

        $this->assertEquals(
            [],
            $input1->getConcepts()
        );

        $input2 = $this->getFullInputMock('id2', 'image2', Input::IMG_URL);

        $concept1 = $this->getConceptEntity('id1', true);
        $concept2 = $this->getConceptEntity('id2', false);
        $concept3 = $this->getConceptEntity('id3', true);

        $input2Concepts = $input2->getConcepts();
        $input2Concepts[] = $concept3;

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'PATCH',
                'inputs',
                $this->getUpdateConceptRequest(
                    [
                        $input1->getId() => [$concept1, $concept2],
                        $input2->getId() => [$concept3],
                    ],
                    InputRepository::CONCEPTS_MERGE_ACTION
                )

            )
            ->andReturn(
                $this->getUpdateConceptResponse(
                    [
                        $input1->setConcepts([$concept1, $concept2]),
                        $input2->setConcepts($input2Concepts),
                    ]
                )
            );

        $this->assertEquals(
            [$input1, $input2],
            $this->inputRepository->mergeInputConcepts(
                [
                    $input1->getId() => [$concept1, $concept2],
                    $input2->getId() => [$concept3],
                ]
            )
        );
    }

    public function testDeleteInputConcepts()
    {

        $input1 = $this->getFullInputMock('id1', 'image1', Input::IMG_URL);
        $input1->setConcepts([]);

        $this->assertEquals(
            [],
            $input1->getConcepts()
        );

        $input2 = $this->getFullInputMock('id2', 'image2', Input::IMG_URL);

        $input2->setConcepts([]);

        $this->assertEquals(
            [],
            $input2->getConcepts()
        );

        $concept1 = $this->getConceptEntity('id1', true);
        $concept2 = $this->getConceptEntity('id2', false);
        $concept3 = $this->getConceptEntity('id3', true);

        $input1->setConcepts([$concept1, $concept3]);
        $input2->setConcepts([$concept2, $concept3]);

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'PATCH',
                'inputs',
                $this->getUpdateConceptRequest(
                    [
                        $input1->getId() => [$concept1, $concept3],
                        $input2->getId() => [$concept3],
                    ],
                    InputRepository::CONCEPTS_REMOVE_ACTION
                )

            )
            ->andReturn(
                $this->getUpdateConceptResponse(
                    [
                        $input1->setConcepts([]),
                        $input2->setConcepts([$concept2]),
                    ]
                )
            );

        $this->assertEquals(
            [$input1, $input2],
            $this->inputRepository->deleteInputConcepts(
                [
                    $input1->getId() => [$concept1, $concept3],
                    $input2->getId() => [$concept3],
                ]
            )
        );
    }

    /**
     * @expectedException \Exception
     */
    public function testGetInputsFromResultException(){
        $this->inputRepository->getInputsFromResult([]);
    }

    /**
     * Gets Input response
     *
     * @param array|Input $inputs
     *
     * @return array
     */
    public function getInputResponse($inputs)
    {
        $data = [
            'status' => [
                'code' => '1000',
                'description' => 'Ok',
            ],

        ];

        if (is_array($inputs)) {
            foreach ($inputs as $input) {
                $data['inputs'][] = $this->getInputConstructData(
                    $input->getId(),
                    $input->getImage(),
                    $input->getImageMethod(),
                    [
                        'concepts' => $this->getConceptsRawData($input->getConcepts()),
                        'crop' => $input->getCrop(),
                        'created_at' => $input->getCreatedAt(),
                        'metadata' => $input->getMetaData(),
                        'status' => $input->getStatus(),
                    ]
                );
            }
        } else {
            $data['inputs'][] = $this->getInputConstructData(
                $inputs->getId(),
                $inputs->getImage(),
                $inputs->getImageMethod(),
                [
                    'concepts' => $this->getConceptsRawData($inputs->getConcepts()),
                    'crop' => $inputs->getCrop(),
                    'created_at' => $inputs->getCreatedAt(),
                    'metadata' => $inputs->getMetaData(),
                    'status' => $inputs->getStatus(),
                ]
            );
        }

        return $data;
    }

    /**
     * Output data for GetById Response
     *
     * @param Input $input
     *
     * @return array
     */
    public function getOneInputResponse(Input $input)
    {
        $data = [
            'status' => [
                'code' => '1000',
                'description' => 'Ok',
            ],
        ];

        $data['input'] = $this->getInputConstructData(
            $input->getId(),
            $input->getImage(),
            $input->getImageMethod(),
            [
                'concepts' => $this->getConceptsRawData($input->getConcepts()),
                'crop' => $input->getCrop(),
                'metadata' => $input->getMetaData(),
                'status' => $input->getStatus(),
                'created_at' => $input->getCreatedAt(),
            ]
        );

        return $data;
    }

    /**
     * Output data for Add Request
     *
     * @param array|Input $inputs
     *
     * @return array $data
     */
    public function getAddRequest($inputs)
    {
        $data['inputs'] = [];

        if (is_array($inputs)) {
            foreach ($inputs as $input) {
                $data['inputs'][] = $this->getInputConstructData(
                    $input->getId(),
                    $input->getImage(),
                    $input->getImageMethod(),
                    [
                        'concepts' => $this->getConceptsRawData($input->getConcepts()),
                        'crop' => $input->getCrop(),
                        'metadata' => $input->getMetaData(),
                    ]
                );
            }
        } else {
            $data['inputs'][] = $this->getInputConstructData(
                $inputs->getId(),
                $inputs->getImage(),
                $inputs->getImageMethod(),
                [
                    'concepts' => $this->getConceptsRawData($inputs->getConcepts()),
                    'crop' => $inputs->getCrop(),
                    'metadata' => $inputs->getMetaData(),
                ]
            );
        }

        return $data;
    }

    /**
     * Get Update Concept Request
     *
     * @param $inputs
     * @param $action
     *
     * @return mixed
     */

    public function getUpdateConceptRequest($inputs, $action)
    {
        $data['inputs'] = [];

        foreach ($inputs as $inputId => $concepts) {
            $input['id'] = $inputId;
            $input['data']['concepts'] = $this->getConceptsRawData($concepts);

            $data['inputs'][] = $input;
        }
        $data['action'] = $action;

        return $data;
    }

    /**
     * Output data for GetById Response
     *
     * @param array $inputs
     *
     * @return array
     */
    public function getUpdateConceptResponse($inputs)
    {
        $data = [
            'status' => [
                'code' => '1000',
                'description' => 'Ok',
            ],

        ];

        foreach ($inputs as $input) {
            $data['inputs'][] = $this->getInputConstructData(
                $input->getId(),
                $input->getImage(),
                $input->getImageMethod(),
                [
                    'concepts' => $this->getConceptsRawData($input->getConcepts()),
                    'crop' => $input->getCrop(),
                    'created_at' => $input->getCreatedAt(),
                    'metadata' => $input->getMetaData(),
                    'status' => $input->getStatus(),
                ]
            );
        }


        return $data;
    }
}
