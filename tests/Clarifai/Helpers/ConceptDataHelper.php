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
    public function getConceptConstructData($id, $name, $appId, bool $value)
    {
        $data = [
            'id' => $id,
            'value' => $value,
        ];
        if ($name) {
            $data['name'] = $name;
        }
        if ($appId) {
            $data['app_id'] = $appId;
        }

        return $data;
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
    public function getConceptEntity($id, bool $value, $name = null, $appId = null)
    {
        $concept = new Concept($this->getConceptConstructData($id, $name, $appId, $value));

        return $concept;
    }

    /**
     * Gets Raw Data from Concept Entities
     *
     * @param Concept[] $concepts
     *
     * @return array
     */
    public function getConceptsRawData($concepts)
    {
        $data = [];

        foreach ($concepts as $concept) {
            $rawConcept = [];
            if ($concept->getId()) {
                $rawConcept['id'] = $concept->getId();
            }

            $rawConcept['value'] = $concept->getValue();

            if ($concept->getName()) {
                $rawConcept['name'] = $concept->getName();
            }
            if ($concept->getAppId()) {
                $rawConcept['app_id'] = $concept->getAppId();
            }
            $data[] = $rawConcept;
        }

        return $data;
    }

    /**
     * Gets Full One Concept Entity
     *
     * @return Concept
     */
    public function getConceptMock()
    {
        $concept = new Concept();
        $concept->setId('id')
            ->setName('name')
            ->setAppId('appId')
            ->setValue(1)
            ->setCreatedAt('createdAt')
            ->setUpdatedAt('updatedAt')
            ->setLanguage('en');

        return $concept;
    }
}
