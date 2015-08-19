<?php

namespace T4webTranslate\Controller\ViewModel\Admin;

use Zend\View\Model\JsonModel;
use T4webBase\InputFilter\InvalidInputError;

class AjaxViewModel extends JsonModel
{

    /**
     * @param array|InvalidInputError $errors
     */
    public function setErrors($errors)
    {
        if (!($errors instanceof InvalidInputError) && is_array($errors)) {
            $errors = new InvalidInputError($errors);
        }
        $this->setVariable('message', 'error');
        $this->setVariable('errors', $errors->getErrors());
    }
}
