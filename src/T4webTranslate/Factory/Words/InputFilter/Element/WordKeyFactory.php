<?php

namespace T4webTranslate\Factory\Words\InputFilter\Element;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\Words\InputFilter\Element\WordKey;

class WordKeyFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $name = 'key';

        $options = [
            'adapter' => $serviceManager->get('Zend\Db\Adapter\Adapter'),
            'table' => 'words',
            'field' => $name
        ];

        $min = 1;
        $max = 500;

        return new WordKey($options, $name, $min, $max);
    }
}