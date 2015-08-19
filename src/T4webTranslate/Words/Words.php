<?php

namespace T4webTranslate\Words;

use T4webBase\Domain\Entity;

class Words extends Entity
{
    protected $langId;
    protected $key;
    protected $translate;

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

    /**
     * @return mixed
     */
    public function getKey()
    {
        return htmlspecialchars_decode($this->key);
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getTranslate()
    {
        return htmlspecialchars_decode($this->translate);
    }

    /**
     * @param mixed $translate
     */
    public function setTranslate($translate)
    {
        $this->translate = $translate;
    }
}