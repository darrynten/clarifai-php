<?php
/**
 * Clarifai SearchModel
 *
 * @category Model
 * @package  Clarifai
 * @author   Darryn Ten <darrynten@github.com>
 * @license  MIT <https://github.com/darrynten/clarifai-php/blob/master/LICENSE>
 * @link     https://github.com/darrynten/clarifai-php
 */

namespace DarrynTen\Clarifai\Repository;

use DarrynTen\Clarifai\Entity\Model;

/**
 * Clarifai Search Model Repository
 *
 * @package Clarifai
 */
class SearchModelRepository extends ModelRepository
{
    /**
     * Searches Models by It's name and type
     *
     * @param string $name
     * @param string $type
     *
     * @return Model[] array
     */
    public function searchByNameAndType($name = null, $type = null)
    {
        $searchResult = $this->getRequest()->request(
            'POST',
            'models/searches',
            [
                "model_query" => [
                    "name" => $name,
                    "type" => $type,
                ],

            ]
        );

        return $this->getModelsFromResult($searchResult);
    }
}
