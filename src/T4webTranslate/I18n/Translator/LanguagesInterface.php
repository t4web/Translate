<?php

namespace T4webTranslate\I18n\Translator;

interface LanguagesInterface
{

    /**
     * return array
     */
    public function getCodes();
    public function getAll();

    public function getByAttributeValue($attribute, $value, $field);

    public function getLocaleByCode($code);

    public function getCodeByLocale($locale);

}