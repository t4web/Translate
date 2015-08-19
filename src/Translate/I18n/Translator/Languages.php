<?php

namespace T4webTranslate\I18n\Translator;

class Languages implements LanguagesInterface
{

    protected $languages = [];

    public function __construct($data)
    {
        $this->languages = $data;
    }

    /**
     * return array
     */
    public function getCodes()
    {

        $codes = [];
        foreach($this->languages as $language) {
            if(isset($language['code']) && !empty($language['code'])) {
                $codes[] = $language['code'];
            }
        }

        return $codes;
    }

    public function getByAttributeValue($attribute, $value, $field)
    {
        $result = array_search($value, array_column($this->languages, $attribute, $field));

        return $result;
    }

    public function getLocaleByCode($code)
    {
        $locale = array_search($code, array_column($this->languages, 'code', 'locale'));

        return $locale;
    }

    public function getCodeByLocale($locale)
    {
        $code = array_search($locale, array_column($this->languages, 'locale', 'code'));

        return $code;
    }

    public function getLocaleById($id)
    {
        $locale = array_search($id, array_column($this->languages, 'id', 'locale'));

        return $locale;
    }

    public function getAll()
    {
        return $this->languages;
    }
}