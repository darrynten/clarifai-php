<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Helpers;

use DarrynTen\Clarifai\Entity\Input;

trait InputDataHelper
{
    /**
     * Returns data for Input construct
     *
     * @param string $id
     * @param string $method
     * @param string $image
     * @param array $options
     *
     * @return array
     */
    public function getInputConstructData(string $id, string $image, string $method, array $options)
    {
        $data = ['id' => $id];
        if (isset($options['created_at'])) {
            $data['created_at'] = $options['created_at'];
        }
        if (isset($options['status'])) {
            $data['status'] = $options['status'];
        }
        if ($method == Input::IMG_BASE64) {
            $data['data']['image']['base64'] = $image;
        } elseif ($method == Input::IMG_PATH) {
            $data['data']['image']['path'] = $image;
        } elseif ($method == Input::IMG_URL) {
            $data['data']['image']['url'] = $image;
        }
        if (isset($options['crop'])) {
            $data['data']['image']['crop'] = $options['crop'];
        }
        if (isset($options['metadata'])) {
            $data['data']['metadata'] = $options['metadata'];
        }
        if (isset($options['concepts'])) {
            $data['data']['concepts'] = $options['concepts'];
        }

        return $data;
    }

    /**
     * Returns Full Data for Input construct
     *
     * @param string $id
     * @param string $method
     * @param string $image
     *
     * @return array
     */
    public function getInputFullConstructData(string $id, string $image, string $method)
    {
        $data = [
            'id' => $id,
            'created_at' => '2017 - 02 - 24T15:34:10.944942Z',
            'status' => [
                'code' => '300000',
                'description' => 'Ok',
            ],
            'data' => [
                'image' => [
                    'crop' => [0.2, 0.4, 0.3, 0.6],
                ],
                'metadata' => [
                    'first' => 'value1',
                    'second' => 'value2',
                ],
                'concepts' => [
                    [
                        'id' => 'id1',
                        'name' => 'name1',
                        'app_id' => 'appId1',
                        'value' => true,
                    ],
                    [
                        'id' => 'id2',
                        'name' => 'name2',
                        'app_id' => 'appId2',
                        'value' => false,
                    ],
                ],
            ],
        ];
        if ($method == Input::IMG_BASE64) {
            $data['data']['image']['base64'] = $image;
        } elseif ($method == Input::IMG_PATH) {
            $data['data']['image']['path'] = $image;
        } elseif ($method == Input::IMG_URL) {
            $data['data']['image']['url'] = $image;
        }

        return $data;
    }

    /**
     * Get One Input Entity
     *
     * @param string $id
     * @param string $image
     * @param string $method
     * @param array $options
     *
     * @return Input
     */
    public function getInputMock(string $id, string $image, string $method, array $options)
    {
        $input = new Input();
        $input->setId('1234567')
            ->setImage('http://www.chicco.com.ua/thumbs/online_games_chicco_rus_130X80_130x130.jpg')
            ->isUrl()
            ->setStatus('30001', 'Download pending')
            ->setCreatedAt('2017 - 02 - 24T15:34:10.944942Z');

        return $input;
    }

    /**
     * Get One Full Input Entity
     *
     * @param string $id
     * @param string $image
     * @param string $method
     *
     * @return Input
     */
    public function getFullInputMock(string $id, string $image, string $method)
    {
        $input = new Input();
        $input->setId($id)
            ->setImage($image)
            ->setImageMethod($method)
            ->setStatus('30001', 'Download pending')
            ->setCreatedAt('2017 - 02 - 24T15:34:10.944942Z')
            ->setCrop([0.2, 0.4, 0.3, 0.6])
            ->setConcepts(
                [
                    $this->getConceptEntity('id1', true),
                    $this->getConceptEntity('id2', false),
                ]
            )
            ->setMetaData(['meta1' => 'value1', 'meta2' => 'value2']);

        return $input;
    }
}
