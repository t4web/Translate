<?php

namespace T4webTranslate\Factory\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use T4webTranslate\I18n\Translator\Translator;
use Zend\I18n\Translator\Translator as ZendTranslator;

class TranslatorServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {

        if ($serviceLocator->has('Config')) {

            $config = $serviceLocator->get('Config');

            if (array_key_exists('translator', $config)
                && ((is_array($config['translator']) && !empty($config['translator']))
                    || $config['translator'] instanceof \Traversable)
            ) {
                $i18nTranslator = Translator::factory($config['translator']);
                $i18nTranslator->setPluginManager($serviceLocator->get('TranslatorPluginManager'));

                if(isset($config['translator']['languages']) && !empty($config['translator']['languages'])) {
                    $i18nTranslator->setLanguages($config['translator']['languages']);
                }

                if(isset($config['translator']['entities']) && !empty($config['translator']['entities'])) {
                    $i18nTranslator->setEntities($config['translator']['entities']);
                }

                return $i18nTranslator;
            }
        }

        return new ZendTranslator();
    }
}