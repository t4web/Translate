<?php

namespace T4webTranslate\Factory\Words\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webBase\Domain\Service\Update;

class UpdateServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $eventManager = $serviceManager->get('EventManager');
        $eventManager->addIdentifiers('T4webTranslate\Words\Service\Update');

        return new Update(
            $serviceManager->get('T4webTranslate\Words\InputFilter\Update'),
            $serviceManager->get('T4webTranslate\Words\Repository\DbRepository'),
            $serviceManager->get('T4webTranslate\Words\Criteria\CriteriaFactory'),
            $eventManager
        );
    }
}