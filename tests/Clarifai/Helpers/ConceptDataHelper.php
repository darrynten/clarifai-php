<?php

namespace DarrynTen\Clarifai\Tests\Clarifai\Helpers;

use DarrynTen\Clarifai\Entity\Concept;

trait ConceptDataHelper
{
    /**
     * Gets RawData for Concept Constructor
     *
     * @param $id
     * @param $name
     * @param $appId
     * @param $value
     *
     * @return array
     */
    public function getConceptConstructData($id, $name, $appId, $value)
    {
        return [
            'id' => $id,
            'name' => $name,
            'app_id' => $appId,
            'value' => $value,
        ];
    }

    /**
     * Gets RawData for Concept Constructor
     *
     * @param $id
     * @param $name
     * @param $appId
     * @param $value
     *
     * @return Concept
     */
    public function getConceptEntity($id, $value, $name = null, $appId = null)
    {
        $concept = new Concept($this->getConceptConstructData($id, $name, $appId, $value));

        return $concept;
    }
}
