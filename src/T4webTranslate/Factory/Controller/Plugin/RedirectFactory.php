<?php

namespace T4webTranslate\Factory\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\Controller\Plugin\Redirect;

class RedirectFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceManager = $serviceLocator->getServiceLocator();

        return new Redirect(
            $serviceManager->get('ViewHelperManager')->get('url')
        );
    }
}