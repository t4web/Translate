<?php

namespace T4webTranslate\Translate\InputFilter;

use T4webBase\InputFilter\InputFilter;
use T4webBase\InputFilter\Element\Id;
use T4webBase\InputFilter\Element\Text;

class Update extends InputFilter
{

    public function __construct()
    {

        // id
        $id = new Id('id');
        $id->setRequired(false);
        $this->add($id);

        // name
        $name = new Text('name', 1, 60);
        $name->setRequired(false);
        $this->add($name);

        // value
        $value = new Text('value', 1, 255);
        $value->setRequired(false);
        $this->add($value);
    }
}
