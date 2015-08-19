<?php

namespace T4webTranslate\View\Helper;

use Zend\View\Helper\AbstractHelper;
use T4webBase\Domain\EntityInterface;

class EntityTr extends AbstractHelper
{
    protected $translator;
    protected $serviceLocator;

    public function __construct($translator, $serviceLocator)
    {
        $this->translator = $translator;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @param $entityName
     * @param $fieldName
     * @param $entity
     * @return string|void
     */
    public function __invoke($entityName, $fieldName, $entity)
    {

        if($this->translator->getLocale() == $this->translator->getFallbackLocale()) {
            return;
        }

        $entityId = $entity;
        if($entity instanceof EntityInterface) {
            $entityId = $entity->getId();
        }

        $finder = $this->serviceLocator->get("T4webTranslate\\{$entityName}\\Service\\Finder");

        /** @var EntityInterface $translateEntity */
        $translateEntity = $finder->find(
            [
                'Translate' => [
                    'Translate' => ['EntityId' => $entityId],
                    'Languages' => ['Locale' => $this->translator->getFallbackLocale()]
                ]
            ]
        );

        $methodName = "get" . ucfirst($fieldName);

        if($translateEntity->$methodName()) {
            return '<div class="translate default-entity">' . $translateEntity->$methodName() . '</div>';
        }
    }
}