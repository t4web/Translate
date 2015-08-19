<?php

namespace T4webTranslate\Factory\Words\InputFilter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\Words\InputFilter\Filter;

class FilterFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {
        return new Filter(
            $serviceManager->get('T4webTranslate\Languages\Service\Finder')
        );
    }
}