<?php

namespace T4webTranslate\Factory\Words\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\Words\InputFilter\Update;

class UpdateFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {
        return new Update(
            $serviceManager->get('T4webTranslate\Words\InputFilter\Element\WordKey')
        );
    }
}