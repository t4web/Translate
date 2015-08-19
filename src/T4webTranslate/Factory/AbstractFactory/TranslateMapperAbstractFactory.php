<?php

namespace T4webTranslate\Factory\AbstractFactory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use T4webBase\Domain\Mapper\DbMapper;

class TranslateMapperAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return (bool) preg_match('/^T4webTranslate\\\[A-Za-z]+\\\Mapper\\\TranslateMapper$/', $requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $entityName = explode('\\', $requestedName)[1];

        $moduleConfig = $serviceLocator->get("T4webTranslate\\ModuleConfig");

        return new DbMapper(
            $moduleConfig->getDbTableColumnsAsAttributesMap(strtolower("translate-$entityName")),
            $serviceLocator->get("T4webTranslate\\Translate\\Factory\\EntityFactory")
        );
    }

}