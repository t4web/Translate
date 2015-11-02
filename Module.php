<?php

namespace T4webTranslate;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\TranslatorPluginProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\Console\Adapter\AdapterInterface;
use Zend\Console\Console;
use Zend\Console\Request as ConsoleRequest;
use T4webTranslate\I18n\Translator\Languages;
use T4webBase\Domain\Service\BaseFinder;
use Zend\Validator\AbstractValidator;
use Zend\Mvc\Router\RouteMatch;
use Zend\Http\Header\Referer;
use Zend\ServiceManager\Config;
use Zend\Json\Json;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ConsoleUsageProviderInterface,
    ServiceProviderInterface, ControllerProviderInterface, TranslatorPluginProviderInterface, ViewHelperProviderInterface
{

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $serviceManager = $e->getApplication()->getServiceManager();

        if ($e->getRequest() instanceof ConsoleRequest) {
            return;
        }

        $listener = $serviceManager->get('T4webTranslate\Listener\ContentListener');
        $listener->attach($eventManager);

        $this->initTranslator($e);

        // onPreRoute set translate locale
        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_ROUTE, [$this, 'onPreRoute'], 100);
        $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_ROUTE, [$this, 'setJsTranslates'], -100);

        $this->initUrlViewHelper($e);
    }

    /**
     * Translator initialization
     * set languages
     * set default locale from languages
     *
     * @param MvcEvent $e
     */
    public function initTranslator(MvcEvent $e)
    {

        /** @var \T4webTranslate\I18n\Translator\Translator $translator */
        $translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');

        /** @var BaseFinder $finder */
        $finder = $e->getApplication()->getServiceManager()->get('T4webTranslate\Languages\Service\Finder');
        $languages = $finder->findMany();

        $languages = new Languages($languages->toArray());
        $translator->setLanguages($languages);

        /** @var \Zend\Uri\Http $uri */
        $uri = $e->getRequest()->getUri();
        if (0 === strpos($uri->getPath(), '/admin')) {
            $translator->setLocale('ru_UA');
            // admin default locale use in create
            $translator->setFallbackLocale('ru_UA');
            return;
        }

        $locale = $languages->getByAttributeValue('default', 1, 'locale');

        if(!empty($locale)) {
            $translator->setLocale($locale);
        }

        // translate form error messages
        AbstractValidator::setDefaultTranslator($translator);
    }

    /**
     * Replace the URL
     * remove language code from url & set locale by code
     *
     * @param MvcEvent $e
     */
    public function onPreRoute(MvcEvent $e)
    {

        if ($e->getRequest() instanceof ConsoleRequest) {
            return;
        }

        /** @var \Zend\Uri\Http $uri */
        $uri = $e->getRequest()->getUri();

        /** @var \Zend\I18n\Translator\Translator $translator */
        $translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');

        if (0 === strpos($uri->getPath(), '/admin')) {
            if($e->getRequest()->isXmlHttpRequest()) {
                /** @var Referer $referer */
                $referer = $e->getRequest()->getHeader('Referer');
                $query = $referer->uri()->getQuery();

                if(!empty($query)) {
                    parse_str($query, $params);

                    if(isset($params['lang_id'])) {
                        $langId = $params['lang_id'];

                        $translator->setLocale($translator->getLanguages()->getLocaleById($langId));
                    }
                }
            }

            if ($e->getRequest()->getQuery()->get('lang_id')) {
                $langId = $e->getRequest()->getQuery()->get('lang_id');

                /** @var BaseFinder $finder */
                $languagesFinder = $e->getApplication()->getServiceManager()->get('T4webTranslate\Languages\Service\Finder');
                $language = $languagesFinder->find(['T4webTranslate' => ['Languages' => ['Id' => $langId]]]);
                $translator->setLocale($language->getLocale());
            }

            return;
        }

        $codes = $translator->getLanguages()->getCodes();
        $uriPath = $uri->getPath();

        if($e->getRequest()->isXmlHttpRequest()) {
            /** @var Referer $referer */
            $referer = $e->getRequest()->getHeader('Referer');
            $uriPath = $referer->uri()->getPath();
        }

        // get code from uri
        preg_match('/' . implode('|', $codes) . '/', $uriPath, $code);
        if (!empty($code) && isset($code[0])) {
            $translator->setLocale($translator->getLanguages()->getLocaleByCode($code[0]));
        }

        // remove code from uri
        $codes = array_map(
            function ($key) {
                return '/' . $key;
            },
            $codes
        );

        $path = str_replace($codes, '/', $uri->getPath());
        $path = str_replace(['//'], '/', $path);

        $uri->setPath($path);

        $e->getRequest()->setUri($uri);
    }

    /**
     * (For translate JS)
     * Append JavaScript to html body
     * Set locale & translate messages
     *
     * @param MvcEvent $e
     */
    public function setJsTranslates(MvcEvent $e)
    {

        if ($e->getRequest() instanceof ConsoleRequest) {
            return;
        }

        /** @var \T4webTranslate\I18n\Translator\Translator $translator */
        $translator = $e->getApplication()->getServiceManager()->get('MvcTranslator');

        $code = $translator->getLanguages()->getCodeByLocale($translator->getLocale());
        $messages = [$code => $translator->getAllMessages()->getArrayCopy()];

        $inlineScript = $e->getApplication()->getServiceManager()->get('ViewHelperManager')->get('inlineScript');
        $inlineScript->appendScript(
            "
                var locale = \"{$code}\"; \n\r
                var translates = " . Json::encode($messages) .
            ";", 'text/javascript'
        );
    }

    private function initUrlViewHelper(MvcEvent $e)
    {
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();
        $viewHelperManager = $serviceManager->get('ViewHelperManager');

        $viewHelperManager->setInvokableClass('url', 'T4webTranslate\View\Helper\Url');

        $viewHelperManager->setFactory(
            'url',
            function ($sm) use ($serviceManager) {
                $translator = $serviceManager->get('MvcTranslator');
                $helper = new \T4webTranslate\View\Helper\Url($translator);
                $router = Console::isConsole() ? 'HttpRouter' : 'Router';
                $helper->setRouter($serviceManager->get($router));

                $match = $serviceManager->get('application')
                    ->getMvcEvent()
                    ->getRouteMatch();

                if ($match instanceof RouteMatch) {
                    $helper->setRouteMatch($match);
                }

                return $helper;
            }
        );
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    // Autoload all classes from namespace 'Translate' from '/module/Translate/src/Translate'
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ]
            ]
        ];
    }

    /**
     * Returns an array or a string containing usage information for this module's Console commands.
     * The method is called with active Zend\Console\Adapter\AdapterInterface that can be used to directly access
     * Console and send output.
     *
     * If the result is a string it will be shown directly in the console window.
     * If the result is an array, its contents will be formatted to console window width. The array must
     * have the following format:
     *
     *     return array(
     *                'Usage information line that should be shown as-is',
     *                'Another line of usage info',
     *
     *                '--parameter'        =>   'A short description of that parameter',
     *                '-another-parameter' =>   'A short description of another parameter',
     *                ...
     *            )
     *
     * @param AdapterInterface $console
     * @return array|string|null
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            'translate init' => 'Initialize module',
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|Config
     */
    public function getServiceConfig()
    {
        return [
            'allow_override' => true,
            'factories' => [
                // Words
                'T4webTranslate\Words\Service\Finder' => 'T4webTranslate\Factory\Words\Service\FinderServiceFactory',
                'T4webTranslate\Words\Service\Create' => 'T4webTranslate\Factory\Words\Service\CreateServiceFactory',
                'T4webTranslate\Words\Service\Update' => 'T4webTranslate\Factory\Words\Service\UpdateServiceFactory',
                'T4webTranslate\Words\Service\Delete' => 'T4webTranslate\Factory\Words\Service\DeleteServiceFactory',
                'T4webTranslate\Words\InputFilter\Element\WordKey' => 'T4webTranslate\Factory\Words\InputFilter\Element\WordKeyFactory',
                'T4webTranslate\Words\InputFilter\Create' => 'T4webTranslate\Factory\Words\InputFilter\CreateFactory',
                'T4webTranslate\Words\InputFilter\Update' => 'T4webTranslate\Factory\Words\InputFilter\UpdateFactory',
                'T4webTranslate\Words\InputFilter\Filter' => 'T4webTranslate\Factory\Words\InputFilter\FilterFactory',
                'T4webTranslate\Words\Form\EntityForm' => 'T4webTranslate\Factory\Words\Form\EntityFormFactory',
                'T4webTranslate\Words\Form\FilterForm' => 'T4webTranslate\Factory\Words\Form\FilterFormFactory',
                // Languages
                'T4webTranslate\Languages\Service\Finder' => 'T4webTranslate\Factory\Languages\Service\FinderServiceFactory',
                'Zend\I18n\Translator\TranslatorInterface' => 'T4webTranslate\Factory\Service\TranslatorServiceFactory',
            ],
            'abstract_factories' => [
                'T4webTranslate\Factory\AbstractFactory\TranslateCriteriaAbstractFactory',
                'T4webTranslate\Factory\AbstractFactory\TranslateRepositoryAbstractFactory',
                'T4webTranslate\Factory\AbstractFactory\TranslateMapperAbstractFactory',
                'T4webTranslate\Factory\AbstractFactory\CreateServiceAbstractFactory',
                //'T4webTranslate\Factory\AbstractFactory\DeleteServiceAbstractFactory',
                'T4webTranslate\Factory\AbstractFactory\FinderServiceAbstractFactory',
                'T4webTranslate\Factory\AbstractFactory\UpdateServiceAbstractFactory',
            ],
            'invokables' => [
                'T4webTranslate\T4webTranslate\InputFilter\Update' => 'T4webTranslate\T4webTranslate\InputFilter\Update',
            ],
            'aliases' => [
                'translator' => 'MvcTranslator',
            ],
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to seed
     * such an object.
     *
     * @return array|Config
     */
    public function getControllerConfig()
    {
        return [
            'factories' => [
                'T4webTranslate\Controller\Console\Init' => 'T4webTranslate\Factory\Controller\Console\InitControllerFactory',
            ],
            'invokables' => [
                'T4webTranslate\Controller\Admin\Lists' => 'T4webTranslate\Controller\Admin\ListsController',
                'T4webTranslate\Controller\Admin\Word' => 'T4webTranslate\Controller\Admin\WordController',
                'T4webTranslate\Controller\Admin\AjaxWord' => 'T4webTranslate\Controller\Admin\AjaxWordController',
            ],
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getTranslatorPluginConfig()
    {
        return [
            'factories' => [
                'T4webTranslate\I18n\Translator\Loader\DbLoader' => 'T4webTranslate\Factory\I18n\Translator\Loader\DbLoaderFactory',
            ],
        ];
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return [
            'factories' => [
                'languages' => 'T4webTranslate\Factory\View\Helper\LanguagesFactory',
                'entityTr' => 'T4webTranslate\Factory\View\Helper\EntityTrFactory',
            ],
        ];
    }

    public function getControllerPluginConfig()
    {
        return [
            'factories' => [
                'redirect' => 'T4webTranslate\Factory\Controller\Plugin\RedirectFactory',
            ],
        ];
    }
}