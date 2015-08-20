<?php

namespace T4webTranslate\Controller\Admin;

use T4webActionInjections\Mvc\Controller\AbstractActionController;

use Zend\Http\PhpEnvironment\Request;
use T4webBase\Domain\Service\BaseFinder as Finder;
use T4webTranslate\Words\Form\FilterForm as Filter;
use Zend\View\Model\ViewModel;

class ListsController extends AbstractActionController
{

    public function wordsListAction(Request $request, Finder $finder, Filter $filterForm, ViewModel $view)
    {
        $limit = $request->getQuery('limit', 20);
        $page = $request->getQuery('page', 1);

        $view->setFilter($filterForm);
        $filterForm->setData($request->getQuery());

        if (!$filterForm->isValid()) {
            return $view;
        }

        $collection = $finder->findByFilter($this->buildCriteria($filterForm->getData()), $limit, $page);
        $count = $finder->count($this->buildCriteria($filterForm->getData()));

        $view->setCollection($collection);

        $pageParams = $request->getQuery()->toArray();
        $pageParams['countObject'] = $count;
        $pageParams['page'] = $page;
        $pageParams['limit'] = $limit;

        $view->setPageParams($pageParams);

        return $view;
    }

    private function buildCriteria(array $data)
    {

        $criteria = ['T4webTranslate' => ['Words' => ['Order' => 'key ASC']]];
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $criteria['T4webTranslate']['Words'][$key] = $value;
            }
        }

        return $criteria;
    }
}