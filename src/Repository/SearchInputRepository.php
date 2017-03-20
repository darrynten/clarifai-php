<?php
/**
 * Clarifai SearchInput
 *
 * @category Input
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Concept;
use DarrynTen\Clarifai\Entity\Input;

/**
 * Single Clarifai Input
 *
 * @package Clarifai
 */
class SearchInputRepository extends InputRepository
{
    /**
     * Searches Inputs by predicted Concepts
     *
     * @param Concept $concept
     *
     * @return Input[] array
     */
    public function searchByPredictedConcepts($concept)
    {
        $searchResult = $this->getRequest()->request(
            'POST',
            'searches',
            [
                'query' => [
                    'ands' => [
                        [
                            'output' => [
                                'data' => $this->addImageConcepts([], [$concept]),
                            ],
                        ],
                    ],
                ],
            ]
        );

        return $this->getInputsFromSearchResult($searchResult);
    }

    /**
     * Searches Inputs by predicted Concepts
     *
     * @param Concept $concept
     *
     * @return Input[] array
     */
    public function searchByUserSuppliedConcepts($concept)
    {
        $searchResult = $this->getRequest()->request(
            'POST',
            'searches',
            [
                'query' => [
                    'ands' => [
                        [
                            'input' => [
                                'data' => $this->addImageConcepts([], [$concept]),
                            ],
                        ],
                    ],
                ],
            ]
        );

        return $this->getInputsFromSearchResult($searchResult);
    }

    /**
     * Searches Inputs custom Metadata
     *
     * @param array $metadata
     *
     * @return Input[] array
     */
    public function searchByCustomMetadata($metadata)
    {
        $searchResult = $this->getRequest()->request(
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
        );

        return $this->getInputsFromSearchResult($searchResult);
    }

    /**
     * Searches Inputs custom Metadata
     *
     * @param string $url
     *
     * @return Input[] array
     */
    public function searchByReverseImage($url)
    {
        $searchResult = $this->getRequest()->request(
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
                                            'url' => $url,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        return $this->getInputsFromSearchResult($searchResult);
    }

    /**
     * Searches Inputs custom Metadata
     *
     * @param string $url
     *
     * @return Input[] array
     */
    public function searchByMatchUrl($url)
    {
        $searchResult = $this->getRequest()->request(
            'POST',
            'searches',
            [
                'query' => [
                    'ands' => [
                        [
                            'input' => [
                                'data' => [
                                    'image' => [
                                        'url' => $url,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        );

        return $this->getInputsFromSearchResult($searchResult);
    }

    /**
     * Parses Search Request Result and gets Inputs
     *
     * @param $searchResult
     *
     * @return array
     *
     * @throws \Exception
     */
    public function getInputsFromSearchResult($searchResult)
    {
        $input_array = [];

        if (isset($searchResult['hits'])) {
            foreach ($searchResult['hits'] as $hit) {
                if (isset($hit['input'])) {
                    $input = new Input($hit['input']);
                    $input_array[] = $input;
                } else {
                    throw new \Exception('Inputs Not Found');
                }
            }
        } else {
            throw new \Exception('Hits Not Found');
        }

        return $input_array;
    }
}
