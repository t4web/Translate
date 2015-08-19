<?php

namespace T4webTranslate\Languages\Form\Element;

use Zend\Form\Element\Select as ZendSelect;
use T4webBase\Domain\Service\BaseFinder;

class Select extends ZendSelect
{

    ///**
    // * @var BaseFinder
    // */
    //protected $languagesServiceFinder;
    //
    public function __construct($serviceFinder = null)
    {
        parent::__construct('select');
        //$this->languagesServiceFinder = $serviceFinder;
        $this->setValueOptions(['id'=> 2]);
    }
    //
    ///**
    // * @return array
    // */
    //public function getValueOptions()
    //{
    //    return ['id' => 28];
    //}
}
