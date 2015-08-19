<?php

namespace T4webTranslate\View\Helper;

use Zend\View\Helper\Url as ZendUrl;

class Url extends ZendUrl
{

    public function __construct($translator = null)
    {
        $this->translator = $translator;
    }

    public function __invoke($name = null, $params = [], $options = [], $reuseMatchedParams = false)
    {
        $uri = parent::__invoke($name, $params, $options, $reuseMatchedParams);
        if(false !== strpos($uri, '/admin')) {
            return $uri;
        }

        if (isset($params['locale'])) {
            $code = $params['locale'];
        } else {
            $locale = $this->translator->getLocale();
            $code = preg_replace('/\_.*/', '', $locale);
        }

        $array = explode('/', $uri);
        $array = array_diff($array, ['']);
        array_unshift($array, $code);

        $uri = '/' . implode('/', $array);

        return $uri;
    }
}