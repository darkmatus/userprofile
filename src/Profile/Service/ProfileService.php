<?php
namespace Profile\Service;

use Profile\Entity\User;
use Zend\View\Model\ViewModel;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Message;
use Profile\Entity\UserData;
use Zend\Validator\Date;
use Zend\Authentication\Adapter\AdapterInterface;
use SchwarzesSachsenCore\Service\AbstractService;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\Authentication\AuthenticationService;
use Profile\Form\ProfileForm;
use Profile\Entity\Profile;
use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;
use Zend\Validator\File\Extension;
use Zend\Validator\File\Size;
use Profile\Filter\UploadFilter;
use Zend\View\Renderer\RendererInterface as ViewRenderer;

class ProfileService extends AbstractService
{

    /*************************** Class Variables ***************************/

    /**
     * @var Doctrine AuthAdapter
     */
    protected $_authAdapter;

    /**
     * @var unknown_type
     */
    protected $_emailRenderer;

    /**
     * @var \Profile\Mapper\UserMapper
     */
    protected $_userMapper;

    /**
     * @var user_config array
     */
    protected $_options;


    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em;

    /*************************** Getter and Setter ***************************/

    /**
     * getUserMapper
     *
     * @return \Profile\Mapper\UserMapper
     */
    public function getUserMapper()
    {
        if (null === $this->_userMapper) {
            $this->_userMapper = $this->getServiceManager()->get('userMapper');
        }
        return $this->_userMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapper $userMapper
     * @return User
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->_userMapper = $userMapper;
        return $this;
    }

    /**
     * set the options from config
     * @param array $options
     * @return array || null
     */
    public function setOptions($options)
    {
        return $this->_options = $options;
    }

    public function getOptions()
    {
        if (null === $this->_options) {
            $config = $this->getServiceManager()->get('config');
            $this->_options = $config['user_config'];
        }
        return $this->_options;
    }

    /**
     * gets the AuthAdapter
     * @return Zend\Authentication\AuthenticationService
     */
    public function getAuthAdapter()
    {
        if (null === $this->_authAdapter) {
            $this->_authAdapter = $this->getServiceManager()
                ->get('Zend\Authentication\AuthenticationService');
        }
        return $this->_authAdapter;
    }

    /**
     * Checks whether an user is logged in or not
     * @return boolean True if user is logged in, false otherwise
     */
    public function hasIdentity()
    {
        return $this->getAuthAdapter()->hasIdentity();
    }

    /**
     * returns the UserEntity
     * @return Ambigous <\Zend\Authentication\mixed, NULL>
     */
    public function getIdentity()
    {
        return $this->getAuthAdapter()->getIdentity();
    }

    /**
     * set the EntityManager
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     * gets the Doctrine Entity Manager
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        if (null === $this->_em) {
            $this->_em = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_em;
    }

    /*************************** Functions ***************************/

    /**
     * prepare all Data to add a new User and add them to the database
     * @param Profile\Entity\User $entity
     * @param \Profile\Form\RegisterForm $form
     * @param httpRequest $request
     * @return boolean
     */
    public function addUser($entity, $form, $request)
    {
        $entity->setPassword($this->hashPassWd($request->getPost('password')));
        $token = $this->registerMail($request);
        $entity->setToken($token);
        $entity->setIsAdmin(0);
        $entity->setLogin(1);
        $user = $this->getUserMapper()->save($entity);
        $data = new UserData();
        $data->setUser($user);
        $result = $this->getUserMapper()->saveData($data);

        if ($result instanceof UserData) {
            return true;
        }
        return false;
    }

    /**
     * edit a given Profile
     * @param \Profile\Form\RegisterForm $form
     * @param Profile\Entity\User $entity
     * @return boolean
     */
    public function editProfile($form, $entity)
    {
        if ($form->isValid()) {
            $homepage = trim($form->get('homepage')->getValue(), 'http://');
            $entity->setHomepage($homepage);
            $entity->setUser($this->getUserById($this->getIdentity()->getUserId()));
            $user = $this->getUserMapper()->save($entity);
            return true;
        }
        return false;
    }

    /**
     * get a Profile from the Database with given ID
     * or if no ID is given the user as self and returns them
     * @param integer $id
     * @return \Profile\Entity\User
     */
    public function showProfile($id)
    {
        if (!empty($id)) {
            $user = $this->getUserById($id);
        } else {
            $user = $this->getUserById($this->getIdentity()->getUserId());
        }
        return $user;
    }

    /**
     * delets an Entity given by ID
     * @param integer $id
     * @return boolean
     */
    public function deleteProfile($id)
    {
        $entity = $this->getUserMapper()->findId($id);

        if ($this->getUserMapper()->findId($id) != null) {
            return $this->getUserMapper()->deleteUser($entity);
        }
        return false;

    }

    /**
     * log-in an user
     * @param string $username
     * @param string $password
     * @return void|\Zend\Authentication\Result
     */
    public function login($username, $password)
    {
        if ($this->loginAllowed($username)) {
            $authService = $this->getAuthAdapter();
            $adapter = $authService->getAdapter();
            $adapter->setIdentityValue($username)
                    ->setCredentialValue($password);
            $authResult = $authService->authenticate();
            if ($authResult->isValid()) {
                $authResult->getIdentity();
                $authResult->getIdentity()->getUserdata();
                $authService->getStorage()->write($authResult->getIdentity());
            }
            return $authResult;
        }
        return;
    }

    /**
     * log-out an user
     */
    public function logout()
    {
        $this->getAuthAdapter()->clearIdentity();
        session_regenerate_id();
    }

    /**
     * generates the password with hash
     * @param string $string
     * @return string
     */
    public function hashPassWd($string)
    {
        $options = $this->getOptions();
        $hashpw = $string . $options['salt'];
        return hash('sha256', $hashpw);
    }

    /**
     * get the user By Id
     * @param integer $userId
     */
    public function getUserById($userId)
    {
        return $this->getUserMapper()->findId($userId);
    }

    /**
     * gets the User_Data by given ID
     * @param integer $userId
     * @return \Profile\Entity\UserData
     */
    public function getUserDataById($userId)
    {
        return $this->getUserMapper()->findUserDataById($userId);
    }

    /**
     * updates the Birthday
     * @param string $birthday
     * @return boolean
     */
    public function updateBirthday($birthday)
    {
        $date = new \DateTime($birthday);
        $val = new Date();
        $val->setFormat('d.m.Y');
        $dbUser = $this->getUserMapper()->findUserDataById($this->getIdentity()->getUserId());
        $dbUser->setBirth($date);
        $result = $this->getUserMapper()->saveData($dbUser);
        if ($result == null) {
            return true;
        }
        return false;
    }

    /**
     * upload a ProfileImage
     * @param \Profile\Form\UploadForm $form
     * @param httpRequest $request
     * @param file $file file to upload
     * @return boolean
     */
    public function upload($form, $request, $file)
    {
        $filter = new UploadFilter();
        $form->setInputFilter($filter->getInputFilter());

        $nonFile = $request->getPost()->toArray();
        $data = array_merge( $nonFile, array('fileupload' => $file['name']));
        $form->setData($data);

        if ($form->isValid()) {
            $options = $this->getOptions();
            $size = new Size(array('min' => '5kB', 'max' => '1.5MB'));
            $type = new Extension($options['profileImage']);

            $adapter = new \Zend\File\Transfer\Adapter\Http();
            $adapter->setValidators(array($size, $type), $file['name']);
            if ((!$adapter->isValid()) || file_exists('public/images/profile_uploads/' . $file['name'])) {
                return false;
            } else {
                $adapter->setDestination('public/images/profile_uploads');
                if ($adapter->receive($file['name'])) {
                    $filter->exchangeArray($form->getData());
                    $user = $this->getUserById($this->getIdentity()->getUserId());
                    $entity = $this->getUserMapper()->findUserDataById($user->getUserid());
                    if ($entity == null) {
                        $entity = new UserData();
                        $entity->setUser($user);

                    }
                    $entity->setPicture('/images/profile_uploads/' . $file['name']);
                    $this->getUserMapper()->saveData($entity);
                    return true;
                }
            }
        }
    }

    /**
     * generetes a unique Token
     * and send an mail to the registered user with an message an the token
     * @param unknown_type $request
     */
    public function registerMail($request)
    {
        $options = $this->getOptions();
        $user = $request->getPost('username');
        $token = $this->hashPassWd($request->getPost('email') . $user);
        $mail = new Message();
        $mail->addFrom($options['mail']['from'])
            ->addTo($request->getPost('email'))
            ->setSubject($options['mail']['subject']);
        $mail->getHeaders()->removeHeader('Content-type');
        $mail->getHeaders()->addHeaderLine('Content-Type', 'text/html; charset=UTF-8');

        $viewModel = new ViewModel(array('token' => $token, 'username' => $user));
        $viewModel->setTerminal(true)->setTemplate('profile/email/verification');

        $mail->setBody($this->getEmailRenderer()->render($viewModel));

        $transport = new Sendmail();
        $transport->send($mail);

        return $token;
    }

    /**
     * returns User given by username
     * @param string $username
     * @return \Profile\Entity\User
     */
    public function getUserByUsername($username)
    {
        return $this->getUserMapper()->findUserByUsername($username);
    }

    /**
     * checks if a user has the rights to log-in
     * @param string $username
     * @return boolean
     */
    public function loginAllowed($username)
    {
        $user = $this->getUserByUsername($username);
        if ($user instanceof User) {
            if ($user->getLogin() === null) {
                return true;
            }
        }
        return false;
    }

    /**
     * confirms the User with token from Mail
     * @param string $token
     * @return boolean
     */
    public function confirmUser($token)
    {
        $user = $this->getUserMapper()->findUserByToken($token);
        if ($user != null) {
            $user->setToken(null);
            $user->setLogin(null);
            try {
                $this->getUserMapper()->save($user);
            } catch (\Exception $e) {
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * sets the Email-Renderer
     * @param ViewRenderer $emailRenderer
     * @return \Profile\Service\ProfileService
     */
    public function setMessageRenderer(ViewRenderer $emailRenderer)
    {
        $this->emailRenderer = $emailRenderer;
        return $this;
    }

    /**
     * gets the AuthAdapter
     * @return Zend\Authentication\AuthenticationService
     */
    public function getEmailRenderer()
    {
        if (null === $this->emailRenderer) {
            $this->emailRenderer = $this->getServiceManager()
            ->get('emailRenderer');
        }
        return $this->emailRenderer;
    }
}
