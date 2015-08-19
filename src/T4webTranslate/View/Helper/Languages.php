<?php

namespace T4webTranslate\View\Helper;

use Zend\View\Helper\AbstractHelper;
use T4webBase\Domain\Service\BaseFinder;
use T4webBase\Domain\Collection;

class Languages extends AbstractHelper
{

    /**
     * @var BaseFinder
     */
    protected $finderService;
    protected $translator;
    protected $routeMatch;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct($finderService, $translator, $routeMatch)
    {
        $this->finderService = $finderService;
        $this->translator = $translator;
        $this->routeMatch = $routeMatch;
    }

    public function __invoke()
    {
        $this->collection = $this->finderService->findMany();

        return $this;
    }

    public function render($template)
    {

        return $this->getView()->render(
            $template,
            [
                'collection' => $this->collection,
                'locale' => $this->translator->getLocale(),
                'route' => ($this->routeMatch) ? $this->routeMatch->getMatchedRouteName() : 'home'
            ]
        );
    }
}