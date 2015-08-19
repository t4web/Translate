<?php

namespace T4webTranslate\Factory\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\View\Helper\Languages;

class LanguagesFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $request = $serviceManager->getServiceLocator()->get('Request');
        $router = $serviceManager->getServiceLocator()->get('Router');
        $routeMatch = $router->match($request);

        return new Languages(
            $serviceManager->getServiceLocator()->get('T4webTranslate\Languages\Service\Finder'),
            $serviceManager->getServiceLocator()->get('MvcTranslator'),
            $routeMatch
        );
    }
}