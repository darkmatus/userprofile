<?php

namespace Profile\Controller;

use Decoda\Decoda;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Profile\Filter\UploadFilter;
use Profile\Form\UploadForm;
use Profile\Entity\UserData;
use Profile\Filter\RegisterFilter;
use Profile\Filter\UserDataFilter;
use Profile\Filter\UserInputFilter as UserInputFilter;
use Zend\Session\Container;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Profile\Form\RegisterForm;
use Profile\Form\ProfileForm;
use Profile\Form\LoginForm;
use Profile\Entity\User;
use Doctrine\ORM\EntityManager;

/**
 *
 * @author mi.mueller
 * @copyright Michael Mueller mueller@s-p-it.de S-P-IT.de
 */
class ProfileController extends AbstractActionController
{

    /**
     * OptionsArray
     * @var array
     */
    protected $_options;

    /**
     * set the Options
     * @param array $options
     * @return array
     */
    public function setOptions($options)
    {
        return $this->_options = $options;
    }

    /**
     * returns the Options
     */
    public function getOptions()
    {
        if (null === $this->_options) {
            $config = $this->getServiceLocator()->get('config');
            $this->_options = $config['user_config'];
        }
        return $this->_options;
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

    /**
     * get the EntityManager
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->_em) {
            $this->_em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_em;
    }

    /**
     * registerAction
     * @return \Zend\Http\Response|multitype:\Profile\Form\RegisterForm |multitype:\Profile\Form\RegisterForm multitype:
     */
    public function registerAction()
    {
        $error = '';
        $token = $this->params()->fromRoute('token');
        if (!empty($token)) {
            $check = $this->getProfileService()->confirmUser($token);
            if ($check === true) {
                $this->flashMessenger()->addMessage('Freischaltung erfolgreich. Du kannst dich nun einloggen.');
                return $this->redirect()->toRoute('login');
            }
            $this->flashMessenger()->addErrorMessage('Der angegebene Token ist ungültig oder der Account wurde bereits'
                                                    . ' aktiviert. Bitte registriere dich oder logge dich ein.');
        }
        $form = new RegisterForm();
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

    /**
     * loginAction
     * @return multitype:string \Profile\Form\LoginForm
     * |multitype:\Profile\Form\LoginForm multitype: |\Zend\Http\Response
     */
    public function loginAction()
    {
        $error = '';
        if ($this->getProfileService()->hasIdentity() === false) {

            $form = new LoginForm();
            $options = $this->getOptions();
            $request = $this->getRequest();
            if ($request->isPost()) {
                $username = $request->getPost('username');
                if ($this->getProfileService()->loginAllowed($username)) {
                    $password = $this->getProfileService()->hashPassWd($request->getPost('password'));
                    $result = $this->getProfileService()->login($username, $password);
                    if ($result->getCode() == -4) {
                        $error = 'Es ist ein unbekannter Fehler aufgetreten. Bitte teile dies einem Administrator mit.';
                    } elseif ($result->getCode() == -3) {
                        $error = 'Es wurde ein falsches Passwort angegeben';
                    } elseif ($result->getCode() == -2) {
                        $error = 'Die Identität ist nicht eindeutig';
                    } elseif ($result->getCode() == -1) {
                        $error = 'Es wurde ein unbekannter Benutzername angegeben';
                    } elseif ($result->getCode() == 0) {
                        $error = 'Es ist ein unbekannter Fehler aufgetreten. Bitte teile dies einem Administrator mit.';
                    } elseif ($result->getCode() == 1) {
                        $this->redirect()->toRoute('home');
                    }
                    return array(
                        'form' => $form,
                        'error' => $error);
                }
                $error = 'Dein Account wurde noch nicht bestätigt. Bitte klicke den Link in der Mail.';
                return array(
                    'form' => $form,
                    'error' => $error);
            }
            return array('form' => $form, 'message' => $this->flashMessenger()->getMessages());
        }
        return $this->redirect()->toRoute('home');
    }

    /**
     * (non-PHPdoc)
     * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
     */
    public function indexAction()
    {
        $service = $this->getProfileService();
        if ($service->hasIdentity() === false) {
            $this->flashMessenger()->addErrorMessage('Bitte logge dich ein um dein Profil zu bearbeiten.');
            return $this->redirect()->toRoute('login');
        }
        $id = $service->getIdentity()->getUserId();
        $user = $service->getUserById($id);
        $form = new ProfileForm($id);
        $upForm = new UploadForm();
        $userData = $service->getUserDataById($id);
        if (empty($userData)) {
            $userData = new UserData();
        }
        $form->bind($userData);
        $request = $this->getRequest();

        if ($request->isPost()) {
            $data = $this->params()->fromPost();
            if ($data['birth'] != $user->getUserdata()->getBirth()) {
                $this->getProfileService()->updateBirthday($data['birth']);
            }
            $filter = new UserDataFilter();
            $form->setData($request->getPost());
            $form->setInputFilter($filter->getInputFilter());
            $check = $service->editProfile($form, $userData);
            if ($check === true) {
                $this->flashMessenger()->addMessage('Profil erfolgreich geändert.');
                return $this->redirect()->toRoute('show');
            }
        }
        $form->get('submit')->setAttribute('label', 'aktualisieren');
        return array('form' => $form,
            'upload' => $upForm);
    }

    /**
     * showAction
     * @return multitype:multitype: |multitype:multitype: \Profile\Entity\User |\Zend\Http\Response
     */
    public function showAction()
    {
        if ($this->getProfileService()->getIdentity() != null) {
            $em = $this->getEntityManager();
            $routeId = $this->params()->fromRoute('id');
            if ($routeId != null) {
                $id = $this->params()->fromRoute('id');
            } else {
                $id = $this->getProfileService()->getIdentity()->getUserId();
            }

            $profile = $this->getProfileService()->showProfile($id);
            if ($profile == null) {
                $this->flashMessenger()
                    ->addErrorMessage('Das angeforderte Profil existiert nicht. Du wirst zur Startseite umgeleitet');
                return array(
                        'error' => $this->flashMessenger()->getErrorMessages());
            }
            return array('profile' => $profile,
                         'message' => $this->flashMessenger()->getMessages());
        }
        $this->flashMessenger()->addErrorMessage('Bitte logge dich ein um dieses Profil zu sehen.');
        return $this->redirect()->toRoute('home');
    }

    /**
     * logoutAction
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        $this->getProfileService()->logout();
        return $this->redirect()->toRoute('home');
    }

    /**
     * uploadAction
     * @return \Zend\Http\Response|multitype:\Profile\Form\UploadForm
     */
    public function uploadAction()
    {
        $form = new UploadForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $file    = $this->params()->fromFiles('fileupload');
            if ($this->getProfileService()->upload($form, $request, $file) == true) {
                return $this->redirect()->toRoute('show');
            }
        }
        return array('form' => $form);
    }
}