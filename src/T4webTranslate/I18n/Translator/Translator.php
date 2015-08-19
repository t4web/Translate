<?php

namespace T4webTranslate\I18n\Translator;

class Translator extends \Zend\I18n\Translator\Translator
{

    /**
     * @var LanguagesInterface
     */
    protected $languages = [];

    /**
     * @return LanguagesInterface
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @param $languages
     */
    public function setLanguages(LanguagesInterface $languages)
    {
        $this->languages = $languages;
    }

}