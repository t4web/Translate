<?php

namespace T4webTranslate\Controller\ViewModel\Admin;

use Zend\View\Model\ViewModel;
use Application\Traits\ViewModel\PageParamsTrait;
use T4webBase\Domain\Collection;
use T4webBase\Domain\EntityInterface;
use Zend\Form\Form;

class ListViewModel extends ViewModel
{
    use PageParamsTrait;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var EntityInterface
     */
    protected $entity;

    /**
     * @var Form
     */
    protected $filter;

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param Collection $list
     */
    public function setCollection($list)
    {
        $this->collection = $list;
    }

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

    /**
     * @param null $name
     * @return \Zend\Form\ElementInterface|Form
     */
    public function getFilter($name = null)
    {
        if (!empty($name)) {
            return $this->filter->get($name);
        }

        return $this->filter;
    }

    /**
     * @param Form $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }
}
