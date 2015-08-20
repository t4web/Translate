<?php

namespace T4webTranslate\Controller\Admin;

use T4webActionInjections\Mvc\Controller\AbstractActionController;

use Zend\Http\Response;
use Zend\Json\Json;
use Zend\Mvc\Controller\Plugin\Params;
use Zend\Http\PhpEnvironment\Request;
use Zend\Form\Form;

use T4webBase\Domain\Service\BaseFinder as Finder;
use T4webBase\Domain\Service\Update;

use T4webTranslate\Controller\ViewModel\Admin\AjaxViewModel as View;

class AjaxWordController extends AbstractActionController
{

    public function saveAction(
        Params $params,
        Request $request,
        Response $response,
        Form $form,
        Finder $finderService,
        Update $updateService,
        Form $form,
        View $view
    ) {

        if ($request->getMethod() !== Request::METHOD_PUT) {
            return $view;
        }

        $id = $params('id');

        $entity = $finderService->find(['T4webTranslate' => ['Words' => ['Id' => (int)$id]]]);
        if (!$entity) {
            $response->setStatusCode(Response::STATUS_CODE_404);
            $view->setErrors(['message' => 'bad params']);

            return $view;
        }

        $data = Json::decode($request->getContent(), Json::TYPE_ARRAY);

        $form->setData($data);
        if (!$form->isValid()) {
            $response->setStatusCode(Response::STATUS_CODE_404);
            $view->setErrors($form->getMessages());

            return $view;
        }

        $entity->populate($data);
        $result = $updateService->update($id, $entity->extract());

        $view->setVariables($result->extract());

        return $view;
    }
}