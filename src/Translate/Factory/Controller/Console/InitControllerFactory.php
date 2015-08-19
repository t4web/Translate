<?php

namespace T4webTranslate\Factory\Controller\Console;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use T4webTranslate\Controller\Console\InitController;

class InitControllerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceManager = $serviceLocator->getServiceLocator();

        return new InitController(
            $serviceManager->get('Zend\Db\Adapter\Adapter')
        );
    }
}