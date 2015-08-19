<?php

namespace T4webTranslate\Translate;

use T4webBase\Domain\Entity;

class Translate extends Entity
{
    protected $entityId;
    protected $langId;

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * @param mixed $entityId
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;
    }

    /**
     * @return mixed
     */
    public function getLangId()
    {
        return $this->langId;
    }

    /**
     * @param mixed $langId
     */
    public function setLangId($langId)
    {
        $this->langId = $langId;
    }

    public function populate(array $array = array()) {
        $state = get_object_vars($this);

        $stateIntersect = array_intersect_key($array, $state);

        foreach ($stateIntersect as $key => $value) {
            $this->$key = $value;
        }

        $data = array_diff($array, $stateIntersect);
        foreach($data as $key => $value) {
            $this->$key = $value;
        }

        return $this;
    }

    public function __call($method, array $args) {
        $property = lcfirst(substr($method, 3));

        if (strpos($method, 'get') !== false ) {
            return isset($this->{$property}) ? $this->{$property} : null;
        }

        throw new \Exception('Invalid method "' . $method . '"');
    }
}