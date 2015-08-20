<?php

namespace T4webTranslate\Factory\AbstractFactory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use T4webBase\Module\ModuleConfig;
use T4webBase\Domain\Criteria\Factory;

class TranslateCriteriaAbstractFactory implements AbstractFactoryInterface
{

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return (bool)preg_match('/^T4webTranslate\\\[A-Za-z]+\\\Criteria\\\TranslateFactory$/', $requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $moduleName = explode('\\', $requestedName)[0];
        $entityName = explode('\\', $requestedName)[1];

        /** @var ModuleConfig $moduleConfig */
        $moduleConfig = $serviceLocator->get("T4webTranslate\\ModuleConfig");
        $criteries = $moduleConfig->getCriteries();
        $table = $moduleConfig->getDbTableName(strtolower("$moduleName-$entityName"));

        foreach ($criteries['Translate'] as $key => $criteria) {
            $criteries['Translate'][$key]['table'] = $table;
        }

        return new Factory('Translate', 'Translate', $moduleConfig->getDbDependencies(), $criteries);
    }
}