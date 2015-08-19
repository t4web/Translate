<?php

namespace T4webTranslate\Factory\Words\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webBase\Domain\Service\Create;

class CreateServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $eventManager = $serviceManager->get('EventManager');
        $eventManager->addIdentifiers('T4webTranslate\Words\Service\Create');

        return new Create(
            $serviceManager->get('T4webTranslate\Words\InputFilter\Create'),
            $serviceManager->get('T4webTranslate\Words\Repository\DbRepository'),
            $serviceManager->get('T4webTranslate\Words\Factory\EntityFactory'),
            $eventManager
        );
    }
}