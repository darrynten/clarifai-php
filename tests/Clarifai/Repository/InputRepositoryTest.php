<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Repository\BaseRepository;
use DarrynTen\Clarifai\Repository\InputRepository;
use DarrynTen\Clarifai\Tests\Clarifai\Helpers\DataHelper;

class InputRepositoryTest extends \PHPUnit_Framework_TestCase
{
    use DataHelper;

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
        $input1 = $this->getFullInputEntity();
        $input2 = $this->getFullInputEntity()->setId('id2')->setImage('image2')->isEncoded();

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                ['inputs' => [$this->inputRepository->addNewImage($input1)]]
            )
            ->andReturn(['status' => $this->getStatusResult(), 'inputs' => [$input1->generateRawData()]]);

        $this->assertEquals(
            [$input1],
            $this->inputRepository->add($input1)
        );

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'POST',
                'inputs',
                [
                    'inputs' => [
                        $this->inputRepository->addNewImage($input1),
                        $this->inputRepository->addNewImage($input2),
                    ],
                ]
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
        $input1 = $this->getFullInputEntity();
        $input2 = $this->getFullInputEntity()->setId('id2')->setImage('image2')->isEncoded();

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs'
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
            $this->inputRepository->get()
        );
    }

    public function testGetById()
    {
        $input = $this->getFullInputEntity();

        $this->request->shouldReceive('request')
            ->once()
            ->with(
                'GET',
                'inputs/' . $input->getId()
            )
            ->andReturn(
                [
                    'status' => $this->getStatusResult(),
                    'input' => $input->generateRawData(),
                ]
            );

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
        $input = $this->getFullInputEntity();

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

        $input1 = $this->getFullInputEntity();
        $input1->setConcepts([]);

        $this->assertEquals(
            [],
            $input1->getConcepts()
        );

        $input2 = $this->getFullInputEntity()->setId('id2')->setImage('image2');

        $concept1 = $this->getFullConceptEntity('id1', true);
        $concept2 = $this->getFullConceptEntity('id2', false);
        $concept3 = $this->getFullConceptEntity('id3', true);

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
                [
                    'status' => $this->getStatusResult(),
                    'inputs' => [
                        $input1->setConcepts([$concept1, $concept2])->generateRawData(),
                        $input2->setConcepts($input2Concepts)->generateRawData(),
                    ],
                ]
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

        $input1 = $this->getFullInputEntity();
        $input1->setConcepts([]);

        $this->assertEquals(
            [],
            $input1->getConcepts()
        );

        $input2 = $this->getFullInputEntity()->setId('id2')->setImage('image2');

        $input2->setConcepts([]);

        $this->assertEquals(
            [],
            $input2->getConcepts()
        );

        $concept1 = $this->getFullConceptEntity('id1', true);
        $concept2 = $this->getFullConceptEntity('id2', false);
        $concept3 = $this->getFullConceptEntity('id3', true);

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
                [
                    'status' => $this->getStatusResult(),
                    'inputs' => [
                        $input1->setConcepts([])->generateRawData(),
                        $input2->setConcepts([$concept2])->generateRawData(),
                    ],
                ]
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
    public function testGetInputsFromResultException()
    {
        $this->inputRepository->getInputsFromResult([]);
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
            $input['data'] = [];
            $input['data'] = $this->inputRepository->addImageConcepts($input['data'], $concepts);
            $data['inputs'][] = $input;
        }
        $data['action'] = $action;

        return $data;
    }
}
