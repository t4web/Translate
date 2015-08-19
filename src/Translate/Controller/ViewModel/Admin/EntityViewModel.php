<?php

namespace T4webTranslate\Controller\ViewModel\Admin;

use Zend\View\Model\ViewModel;
use T4webBase\Domain\EntityInterface;
use Application\Traits\ViewModel\FormTrait;

class EntityViewModel extends ViewModel
{

    use FormTrait;

    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * @return EntityInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param EntityInterface $entity
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    }
}
