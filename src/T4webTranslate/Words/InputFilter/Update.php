<?php

namespace T4webTranslate\Words\InputFilter;

use T4webBase\InputFilter\InputFilter;
use T4webBase\InputFilter\Element\Id;
use T4webBase\InputFilter\Element\Text;
use T4webTranslate\Words\InputFilter\Element\WordKey;

class Update extends InputFilter
{

    public function __construct(WordKey $wordKeyElement)
    {

        // id
        $id = new Id('id');
        $id->setRequired(true);
        $this->add($id);

        // langId
        $langId = new Id('langId');
        $langId->setRequired(true);
        $this->add($langId);

        // key
        $this->add($wordKeyElement);

        // translate
        $translate = new Text('translate', 1, 500);
        $translate->setRequired(true);
        $translate->getFilterChain()->attachByName('StringTrim');
        $this->add($translate);
    }
}
