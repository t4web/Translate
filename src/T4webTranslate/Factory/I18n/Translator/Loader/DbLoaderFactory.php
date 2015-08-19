<?php

namespace T4webTranslate\Factory\I18n\Translator\Loader;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\I18n\Translator\Loader\DbLoader;

class DbLoaderFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $wordsServiceFinder = $serviceManager->getServiceLocator()->get('T4webTranslate\Words\Service\Finder');

        return new DbLoader($wordsServiceFinder);
    }
}