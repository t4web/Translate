<?php

namespace T4webTranslate\Words\InputFilter;

use T4webBase\InputFilter\InputFilter;
use T4webBase\Domain\Service\BaseFinder;
use T4webBase\InputFilter\Element\Text;
use T4webBase\InputFilter\Element\InArray;

class Filter extends InputFilter
{

    /**
     * @var BaseFinder
     */
    protected $finder;

    public function __construct($finder)
    {
        $this->finder = $finder;

        // key
        $key = new Text('key', 1, 500);
        $key->setRequired(false);
        $this->add($key);

        // langId
        $langId = new InArray('langId', $this->getLanguages()->getValuesByAttribute('id'));
        $langId->setRequired(false);
        $this->add($langId);
    }

    private function getLanguages()
    {
        return $this->finder->findMany();
    }
}
