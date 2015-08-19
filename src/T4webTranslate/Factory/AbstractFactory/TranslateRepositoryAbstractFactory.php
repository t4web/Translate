<?php

namespace T4webTranslate\Factory\AbstractFactory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use T4webBase\Domain\Repository\DbRepository;

class TranslateRepositoryAbstractFactory implements AbstractFactoryInterface
{

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return preg_match('/^T4webTranslate\\\[A-Za-z]+\\\Repository\\\TranslateRepository$/', $requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $entityName = explode('\\', $requestedName)[1];

        $tableGateway = $serviceLocator->get("T4webTranslate\\{$entityName}\\Db\\Table");
        $DbMapper = $serviceLocator->get("T4webTranslate\\{$entityName}\\Mapper\\TranslateMapper");
        $queryBuilder = $serviceLocator->get('T4webBase\Db\QueryBuilder');
        $identityMap = clone $serviceLocator->get('T4webBase\Domain\Repository\IdentityMap');
        $identityMapOriginal = clone $identityMap;
        $eventManager = $serviceLocator->get('EventManager');
        $eventManager->addIdentifiers('T4webBase\Domain\Repository\DbRepository');

        return new DbRepository(
            $tableGateway,
            $DbMapper,
            $queryBuilder,
            $identityMap,
            $identityMapOriginal,
            $eventManager
        );
    }
}