<?php

namespace T4webTranslate\Words\Form;

use Zend\Form\Form;
use T4webBase\Domain\Service\BaseFinder;

class FilterForm extends Form
{
    /**
     * @var BaseFinder
     */
    protected $finder;

    public function __construct($finder)
    {
        $this->finder = $finder;

        parent::__construct('filter');

        $this->add(
            [
                'type' => 'Zend\Form\Element\Text',
                'name' => 'key',
                'filters' => [['name' => 'StringTrim']],
                'options' => [
                    'label' => 'Ключ:',
                ],
                'attributes' => [
                    'placeholder' => 'Введите ключ для поиска',
                ],
            ]
        );

        $this->add(
            [
                'type' => 'Zend\Form\Element\Select',
                'name' => 'langId',
                'options' => [
                    'label' => 'Язык:',
                    'empty_option' => 'Выберите язык',
                    'value_options' => $this->getLanguages()->getValuesByAttribute('code', true),
                ],

            ]
        );
    }

    private function getLanguages()
    {
        return $this->finder->findMany();
    }
}