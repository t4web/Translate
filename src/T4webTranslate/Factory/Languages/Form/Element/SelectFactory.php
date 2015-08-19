<?php

namespace T4webTranslate\Factory\Languages\Form\Element;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\Languages\Form\Element\Select;

class SelectFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {

        return new Select(
            $serviceManager->get('T4webTranslate\Languages\Service\Finder')
        );
    }
}