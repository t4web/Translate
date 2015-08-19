<?php

namespace T4webTranslate\I18n\Translator\Loader;

use Zend\I18n\Translator\Loader\RemoteLoaderInterface;
use Zend\I18n\Translator\Loader\PhpMemoryArray;

class DbLoader implements RemoteLoaderInterface
{

    /**
     * @var \T4webBase\Domain\Service\BaseFinder
     */
    protected $wordsServiceFinder;

    public function __construct($wordsServiceFinder)
    {
        $this->wordsServiceFinder = $wordsServiceFinder;
    }

    /**
     * Load translations from a remote source.
     *
     * @param  string $locale
     * @param  string $textDomain
     * @return \Zend\I18n\Translator\TextDomain|null
     */
    public function load($locale, $textDomain)
    {

        $words = $this->wordsServiceFinder->findMany(['Translate' => ['Languages' => ['Locale' => $locale]]]);

        $messages[$textDomain][$locale] = [];
        if ($words->count()) {
            foreach ($words as $word) {
                $messages[$textDomain][$locale][$word->getKey()] = $word->getTranslate();
            }
        }

        $array = new PhpMemoryArray($messages);
        $result = $array->load($locale, $textDomain);

        return $result;
    }
}
