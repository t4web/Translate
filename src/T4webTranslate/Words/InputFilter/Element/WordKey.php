<?php

namespace T4webTranslate\Words\InputFilter\Element;

use T4webBase\InputFilter\Element\Text;
use Zend\Validator\Db\NoRecordExists;

class WordKey extends Text
{

    /**
     * @var NoRecordExists
     */
    protected $dbNoRecordExistsValidator;

    public function __construct($options, $name = null, $min = 1, $max = 50)
    {
        parent::__construct($name, $min, $max);

        $this->dbNoRecordExistsValidator = new NoRecordExists($options);

        $this->getValidatorChain()
            ->attach($this->dbNoRecordExistsValidator);

        $messageTemplates = [
            NoRecordExists::ERROR_RECORD_FOUND => "Перевод для указанного ключа уже существует",
        ];
        $this->dbNoRecordExistsValidator->setMessages($messageTemplates);

        $this->getFilterChain()->attachByName('StringTrim');
    }

    public function isValid($context = null)
    {
        $clause[] = 'lang_id = ' . $context['langId'];

        if (isset($context['id']) && !empty($context['id'])) {
            $clause[] = 'id <> ' . $context['id'];
        }
        $this->dbNoRecordExistsValidator->setExclude(implode(' AND ', $clause));

        return parent::isValid($context);
    }
}

