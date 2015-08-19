<?php

namespace T4webTranslate\Factory\AbstractFactory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\AbstractFactoryInterface;
use T4webBase\Domain\Service\Create;

class CreateServiceAbstractFactory implements AbstractFactoryInterface {

    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        return preg_match('/^T4webTranslate\\\[A-Za-z]+\\\Service\\\Create$/', $requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName) {
        $entityName = explode('\\', $requestedName)[1];

        return new Create(
            $serviceLocator->get('T4webTranslate\Translate\InputFilter\Create'),
            $serviceLocator->get('T4webTranslate\\'.$entityName.'\Repository\TranslateRepository'),
            $serviceLocator->get('T4webTranslate\Translate\Factory\EntityFactory')
        );
    }

}