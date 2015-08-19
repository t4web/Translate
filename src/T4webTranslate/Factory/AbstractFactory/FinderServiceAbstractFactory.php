<?php

namespace T4webTranslate\Factory\AbstractFactory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use T4webBase\Domain\Service\BaseFinder;

class FinderServiceAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return preg_match('/^T4webTranslate\\\[A-Za-z]+\\\Service\\\Finder$/', $requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $entityName = explode('\\', $requestedName)[1];

        return new BaseFinder(
            $serviceLocator->get('T4webTranslate\\'.$entityName.'\Repository\TranslateRepository'),
            $serviceLocator->get('T4webTranslate\\'.$entityName.'\Criteria\TranslateFactory')
        );
    }

}