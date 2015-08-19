<?php

namespace T4webTranslate\Words\Form;

use Zend\Form\Form;
use T4webBase\Domain\Service\BaseFinder;

class EntityForm extends Form
{

    /**
     * @var BaseFinder
     */
    protected $finder;

    public function __construct($finder)
    {
        $this->finder = $finder;

        parent::__construct('user');

        $this->add(
            [
                'type' => 'Zend\Form\Element\Hidden',
                'name' => 'id',
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Text',
                'name' => 'key',
                'filters' => [['name' => 'StringTrim']],
                'options' => [
                    'label' => 'Ключ:',
                ],
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Text',
                'name' => 'translate',
                'filters' => [['name' => 'StringTrim']],
                'options' => [
                    'label' => 'Перевод:',
                ],
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Select',
                'name' => 'langId',
                'options' => [
                    'label' => 'Язык:',
                    'value_options' => $this->getLanguages(),
                ]
            ]
        );
    }

    private function getLanguages()
    {
        $languages = $this->finder->findMany();

        return $languages->getValuesByAttribute('code', true);
    }
}