<?php

namespace T4webTranslate\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\View\Helper\EntityTr;

class EntityTrFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {
        return new EntityTr(
            $serviceManager->getServiceLocator()->get('MvcTranslator'),
            $serviceManager->getServiceLocator()
        );
    }
}