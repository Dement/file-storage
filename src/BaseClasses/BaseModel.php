<?php

namespace BaseClasses;

use Traits\ContainerAwareTrait;

class BaseModel {
    //TODO fix forms working wit our Cache super system

    use ContainerAwareTrait;

    /**
     * @param $field - field in which we must load relation entity
     * @param $class - class of repository, from which data loaded
     * @return BaseModel
     */
    protected function getRelation($field, $class)
    {
        if (is_null($field)) {
            return $field;
        } else {
            return $class->getById($field->getId());
        }
    }

    /**
     * Method returns a list of rules that need to test the model
     *
     * @return array
     */
    public function getCustomRules()
    {
        return [];
    }
}