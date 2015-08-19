<?php

namespace T4webTranslate\Controller\Admin;

use T4webActionInjections\Mvc\Controller\AbstractActionController;

use Zend\Mvc\Controller\Plugin\Params;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\Plugin\Redirect;
use Zend\Form\Form;

use T4webBase\Domain\Service\BaseFinder as Finder;
use T4webBase\Domain\Service\Create;
use T4webBase\Domain\Service\Delete;

use T4webTranslate\Controller\ViewModel\Admin\EntityViewModel as View;

class WordController extends AbstractActionController
{

    public function editAction(Params $params, Finder $finder, Form $form, View $view)
    {

        $entity = $finder->find(['Translate' => ['Words' => ['Id' => (int)$params('id')]]]);
        if ($entity) {
            $view->setEntity($entity);
            $form->populateValues($entity->extract());
        }

        $view->setForm($form);

        return $view;
    }

    public function saveAction(Request $request, Create $createService, Form $form, View $view, Redirect $redirect)
    {

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $createService->create($form->getData());

                return $redirect->toRoute('admin-translate-words');
            }
        }

        $view->setForm($form);
        $view->setTemplate('translate/admin/word/edit');

        return $view;
    }

    public function deleteAction(Request $request, Params $params, Delete $deleteService, Redirect $redirect)
    {

        $deleteService->delete((int)$params('id'));

        return $redirect->toRoute('admin-translate-words', [], ['query' => $request->getQuery()->toArray()]);
    }
}