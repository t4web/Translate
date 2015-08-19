<?php

namespace T4webTranslate\Factory\Words\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webBase\Domain\Service\BaseFinder as Finder;

class FinderServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {

        return new Finder(
            $serviceManager->get('T4webTranslate\Words\Repository\DbRepository'),
            $serviceManager->get('T4webTranslate\Words\Criteria\CriteriaFactory')
        );
    }
}