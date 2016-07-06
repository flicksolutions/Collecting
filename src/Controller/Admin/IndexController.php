<?php
namespace Collecting\Controller\Admin;

use Collecting\Form\CollectingForm;
use Omeka\Form\ConfirmForm;
use Omeka\Mvc\Exception;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function adminAction()
    {
        $site = $this->currentSite();

        $view = new ViewModel;
        $view->setVariable('site', $site);
        return $view;
    }

    public function indexAction()
    {
        $site = $this->currentSite();
        $collectingForms = $this->api()
            ->search('collecting_forms', ['site_id' => $site->id()])->getContent();

        $view = new ViewModel;
        $view->setVariable('site', $site);
        $view->setVariable('collectingForms', $collectingForms);
        return $view;
    }

    public function showAction()
    {
        $site = $this->currentSite();
        $collectingForm = $this->api()
            ->read('collecting_forms', $this->params('id'))->getContent();

        $view = new ViewModel;
        $view->setVariable('site', $site);
        $view->setVariable('collectingForm', $collectingForm);
        return $view;
    }

    public function addAction()
    {
        return $this->handleAddEdit();
    }

    public function editAction()
    {
        return $this->handleAddEdit();
    }

    protected function handleAddEdit()
    {
        $site = $this->currentSite();
        $form = $this->getForm(CollectingForm::class);
        $isEdit = (bool) ('edit' === $this->params('action'));

        $view = new ViewModel;
        $view->setTemplate('collecting/admin/index/form');
        $view->setVariable('site', $site);
        $view->setVariable('form', $form);
        $view->setVariable('isEdit', $isEdit);

        if ($isEdit) {
            $collectingForm = $this->api()
                ->read('collecting_forms', $this->params('id'))->getContent();
            $form->setData($collectingForm->jsonSerialize());
            $view->setVariable('collectingForm', $collectingForm);
        }

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $data['o:site']['o:id'] = $site->id();
            $form->setData($data);
            if ($form->isValid()) {
                $response = $isEdit
                    ? $this->api($form)->update('collecting_forms', $collectingForm->id(), $data)
                    : $this->api($form)->create('collecting_forms', $data);
                if ($response->isSuccess()) {
                    $collectingForm = $response->getContent();
                    $successMessage = $isEdit
                        ? $this->translate('Collecting form successfully updated')
                        : $this->translate('Collecting form successfully created');
                    $this->messenger()->addSuccess($successMessage);
                    return $this->redirect()->toUrl($collectingForm->url('show'));
                }
            } else {
                $this->messenger()->addErrors($form->getMessages());
            }
        }

        return $view;
    }

    public function deleteConfirmAction()
    {
        $site = $this->currentSite();
        $collectingForm = $this->api()
            ->read('collecting_forms', $this->params('id'))->getContent();

        $view = new ViewModel;
        $view->setTerminal(true);
        $view->setTemplate('common/delete-confirm-details');
        $view->setVariable('resourceLabel', 'collecting form');
        $view->setVariable('resource', $collectingForm);
        return $view;
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $form = $this->getForm(ConfirmForm::class);
            $form->setData($this->getRequest()->getPost());
            if ($form->isValid()) {
                $response = $this->api($form)->delete('collecting_forms', $this->params('id'));
                if ($response->isSuccess()) {
                    $this->messenger()->addSuccess($this->translate('Collecting form successfully deleted'));
                }
            } else {
                $this->messenger()->addErrors($form->getMessages());
            }
        }
        return $this->redirect()->toRoute(
            'admin/site/slug/collecting/default',
            ['action' => 'index'],
            true
        );
    }
}

