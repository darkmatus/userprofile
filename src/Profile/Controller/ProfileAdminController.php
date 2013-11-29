<?php

namespace Profile\Controller;

use Profile\Filter\RegisterFilter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Profile\Form\RegisterForm;
use Profile\Entity\User;
use Doctrine\ORM\EntityManager;

class ProfileAdminController extends AbstractActionController
{

    protected $options;

    public function setOptions($options)
    {
        return $this->options = $options;
    }

    public function getOptions()
    {
        if (null === $this->options) {
            $config = $this->getServiceLocator()->get('config');
            $this->options = $config['user_config'];
        }
        return $this->options;
    }

    /**
     * Service that handles all interactions with the profile tables
     * @var ProfileService $_profileService
     */
    private $_profileService;

    /**
     * Doctrine2 Entity Manager
     * @var entityManager $_em
     */
    private $_em;

    /**
     * Getter for the Profile Service
     * @return Profile\Service\ProfileService
     */
    private function getProfileService()
    {
        if (isset($this->_profileService) === false) {
            $this->_profileService = $this->getServiceLocator()->get('Service\Profile');
        }
        return $this->_profileService;
    }

    public function getEntityManager()
    {
        if (null === $this->_em) {
            $this->_em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_em;
    }

    public function addAction()
    {
        $error   = '';
        $token   = $this->params()->fromRoute('token');
        $form    = new RegisterForm();
        $request = $this->getRequest();

        if ($request->isPost()) {
            $exUser = $this->getEntityManager()->getRepository('Profile\Entity\User')
                ->findByusername($request->getPost('username'));
            if (empty($exUser)) {
                $user = new User();
                $filter = new RegisterFilter();
                $form->bind($user);
                $form->setInputFilter($filter->getInputFilter());
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $check = $this->getProfileService()->addUser($user, $form, $request);
                    if ($check === true) {
                        return $this->redirect()->toRoute('home');
                    } else {
                        return array('form' => $form);
                    }
                }
            } else {
                $this->flashMessenger()
                    ->addErrorMessage('Der Username ist bereits vergeben. Bitte wähle einen anderen.');
            }
        }
        return array('form' => $form,
                     'error' => $this->flashMessenger()->getErrorMessages());
    }

    public function indexAction()
    {
        $service = $this->getProfileService();
        $user    = $service->getUserMapper()->fetchAll();
        return array('user' => $user);
    }
}