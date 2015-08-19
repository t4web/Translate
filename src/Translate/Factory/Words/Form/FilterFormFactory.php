<?php

namespace T4webTranslate\Factory\Words\Form;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\Words\Form\FilterForm;

class FilterFormFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceManager)
    {
        $finder = $serviceManager->get('T4webTranslate\Languages\Service\Finder');

        $form = new FilterForm($finder);
        $form->setInputFilter($serviceManager->get('T4webTranslate\Words\InputFilter\Filter'));

        return $form;
    }
}