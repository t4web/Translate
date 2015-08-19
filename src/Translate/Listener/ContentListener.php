<?php

namespace T4webTranslate\Listener;

use Zend\EventManager\ListenerAggregateInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\EventManager\EventInterface as Event;
use Zend\EventManager\EventManagerInterface;
use T4webBase\Domain\EntityInterface;
use T4webBase\Domain\Collection;

class ContentListener implements ListenerAggregateInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = [];

    /**
     * Attach to an event manager
     *
     * @param  EventManagerInterface $events
     * @param  int                   $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $config = $this->getServiceLocator()->get("Config");

        foreach($config['content'] as $moduleName => $modules) {
            foreach($modules as $entityName => $entities) {
                foreach($entities['events'] as $event => $id) {
                    $callback = $this->prepareEventFunction($event);

                    if(empty($callback) || !method_exists($this, $callback)) {
                        continue;
                    }

                    $this->listeners[] = $events->getSharedManager()
                        ->attach($id, $event,

                            function ($e) use ($callback, $moduleName, $entityName) {
                                $this->$callback($e, $moduleName, $entityName);
                            },
                            $priority
                        );
                }
            }
        }
    }

    /**
     * Detach all our listeners from the event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function postSelect(Event $e, $moduleName, $name)
    {
        $entity = $e->getParam('entity');
        $entityName = ucfirst($name);

        /** @var \Zend\Mvc\I18n\Translator $translator */
        $translator = $this->getServiceLocator()->get('MvcTranslator');

        $finder = $this->getServiceLocator()->get("T4webTranslate\\{$entityName}\\Service\\Finder");

        if($entity instanceof Collection) {

            if(!$entity->getIds()) return;

            /** @var Collection $translateCollection */
            $translateCollection = $finder->findMany(
                [
                    'Translate' => [
                        'Translate' => ['EntityIds' => $entity->getIds()],
                        'Languages' => ['Locale' => $translator->getLocale()]
                    ]
                ]
            );

            if(!$translateCollection->count()) return;

            foreach($translateCollection as $translate) {
                if($entity->offsetExists($translate->getEntityId())) {
                    $data = $this->prepareData($translate->extract(), $moduleName, $name);

                    $originEntity = $entity->offsetGet($translate->getEntityId());
                    $originEntity->populate($data);
                }
            }

            $e->setParam('entity', $entity);

            return;
        }

        /** @var EntityInterface $translateEntity */
        $translateEntity = $finder->find(
            [
                'Translate' => [
                    'Translate' => ['EntityId' => $entity->getId()],
                    'Languages' => ['Locale' => $translator->getLocale()]
                ]
            ]
        );

        if ($translateEntity) {
            $data = $this->prepareData($translateEntity->extract(), $moduleName, $name);
            $entity->populate($data);
        }

        $e->setParam('entity', $entity);
    }

    public function preUpdate(Event $e, $moduleName, $name)
    {
        /** @var EntityInterface $entity */
        $entity = $e->getParam('entity');
        $entityName = ucfirst($name);

        /** @var \Zend\Mvc\I18n\Translator $translator */
        $translator = $this->getServiceLocator()->get('MvcTranslator');

        $defaultLocale = 'ru_UA';

        $finder = $this->getServiceLocator()->get("T4webTranslate\\{$entityName}\\Service\\Finder");

        /** @var EntityInterface $defaultEntity */
        $defaultEntity = $finder->find(
            [
                'Translate' => [
                    'Translate' => ['EntityId' => $entity->getId()],
                    'Languages' => ['Locale' => $translator->getLocale()]
                ]
            ]
        );

        $update = $this->getServiceLocator()->get("T4webTranslate\\{$entityName}\\Service\\Update");
        $dataEntity = $entity->extract();
        unset($dataEntity['id']);

        $data = $defaultEntity->populate($dataEntity)->extract();
        $update->update($defaultEntity->getId(), $data);

        if ($translator->getLocale() != $defaultLocale) {
            /** @var EntityInterface $translateEntity */
            $defaultEntity = $finder->find(
                [
                    'Translate' => [
                        'Translate' => ['EntityId' => $entity->getId()],
                        'Languages' => ['Locale' => $defaultLocale]
                    ]
                ]
            );
            $dataEntity = $defaultEntity->extract();
            unset($dataEntity['id']);

            if (($defaultEntity)) {
                $entity->populate($dataEntity);
            }

            $e->setParam('entity', $entity);
        }
    }

    public function createEntity(Event $e, $moduleName, $name)
    {
        /** @var EntityInterface $entity */
        $entity = $e->getParam('entity');
        $entityName = ucfirst($name);

        /** @var \T4webTranslate\I18n\Translator\Translator $translator */
        $translator = $this->getServiceLocator()->get('MvcTranslator');

        $createService = $this->getServiceLocator()->get("T4webTranslate\\{$entityName}\\Service\\Create");

        $data = $entity->extract();
        unset($data['id']);

        $data['entityId'] = $entity->getId();
        foreach($translator->getLanguages()->getAll() as $language) {
            $data['langId'] = $language['id'];

            // $translator->getFallbackLocale() - admin default locale
            if($language['locale'] != $translator->getFallbackLocale()) {
                $data = [
                    'entityId' => $entity->getId(),
                    'langId' => $language['id'],
                ];
            }
            $createService->create($data);
        }

    }

    public function createParameterValues(Event $e, $moduleName, $name)
    {
        /** @var Collection $collection */
        $collection = $e->getParam('filterValue');
        $entityName = ucfirst($name);

        /** @var \T4webTranslate\I18n\Translator\Translator $translator */
        $translator = $this->getServiceLocator()->get('MvcTranslator');

        $createService = $this->getServiceLocator()->get("T4webTranslate\\{$entityName}\\Service\\Create");

        foreach($collection as $entity) {
            $data = $entity->extract();
            unset($data['id']);

            $data['entityId'] = $entity->getId();
            foreach($translator->getLanguages()->getAll() as $language) {
                $data['langId'] = $language['id'];

                // $translator->getFallbackLocale() - admin default locale
                if($language['locale'] != $translator->getFallbackLocale()) {
                    $data = [
                        'entityId' => $entity->getId(),
                        'langId' => $language['id'],
                    ];
                }
                $createService->create($data);
            }
        }
    }

    /**
     * @param $name
     * @return string
     */
    private function prepareEventFunction($name)
    {

        switch($name) {
            case 'select:post':
                return 'postSelect';
            case 'create:post':
                return 'createEntity';
            case 'update:pre':
                return 'preUpdate';
            case 'params-values-create:post':
                return 'createParameterValues';
        }

    }

    /**
     * @param $data
     * @param $moduleName
     * @param $name
     * @return array
     */
    private function prepareData($data, $moduleName, $name)
    {
        $config = $this->getServiceLocator()->get("Config");
        $fields = $config['content'][$moduleName][$name]['fields'];

        $rawArray = array_fill_keys($fields, null);
        $result = array_intersect_key($data, $rawArray);

        $result = array_diff($result, ['']);

        return $result;
    }
}
