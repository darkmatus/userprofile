<?php
namespace Profile\Service;

use Profile\Entity\User;
use Zend\View\Model\ViewModel;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Message;
use Profile\Entity\UserData;
use Zend\Validator\Date;
use Zend\Authentication\Adapter\AdapterInterface;
use Application\Service\AbstractService;
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

    /**
     * @var Doctrine AuthAdapter
     */
    protected $authAdapter;

    /**
     * @var unknown_type
     */
    protected $emailRenderer;

    /**
     * @var \Profile\Mapper\UserMapper
     */
    protected $userMapper;

    /**
     * @var user_config array
     */
    protected $options;

    /**
     * getUserMapper
     *
     * @return \Profile\Mapper\UserMapper
     */
    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->getServiceManager()->get('userMapper');
        }
        return $this->userMapper;
    }

    /**
     * setUserMapper
     *
     * @param UserMapper $userMapper
     * @return User
     */
    public function setUserMapper(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
        return $this;
    }

    public function setOptions($options)
    {
        return $this->options = $options;
    }

    public function getOptions()
    {
        if (null === $this->options) {
            $config = $this->getServiceManager()->get('config');
            $this->options = $config['user_config'];
        }
        return $this->options;
    }

    /**
     * gets the AuthAdapter
     * @return Zend\Authentication\AuthenticationService
     */
    public function getAuthAdapter()
    {
        if (null === $this->authAdapter) {
            $this->authAdapter = $this->getServiceManager()
                ->get('Zend\Authentication\AuthenticationService');
        }
        return $this->authAdapter;
    }

    /**
     * Checks whether an user is logged in or not
     * @return boolean True if user is logged in, false otherwise
     */
    public function hasIdentity()
    {
        return $this->getAuthAdapter()->hasIdentity();
    }

    public function getIdentity()
    {
        return $this->getAuthAdapter()->getIdentity();
    }

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * gets the Doctrine Entity Manager
     */
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }

    public function addUser($entity, $form, $request)
    {
        $entity->setPassword($this->hashPassWd($request->getPost('password')));
        $entity->setUsername($request->getPost('username'));
        $entity->setEmail($request->getPost('email'));
        $entity->setCity($request->getPost('city'));
        $entity->setDisplayname($request->getPost('displayname'));
        $token = $this->registerMail($request);
        $entity->setToken($token);
        $entity->setLogin(1);
        $user = $this->getUserMapper()->save($entity);
        $data = new UserData();
        $data->setUser($user);
        $this->getUserMapper()->saveData($data);
        return true;
    }

    public function editProfile($form, $new)
    {
        if ($form->isValid())
        {
            $homepage = trim($form->get('homepage')->getValue(), 'http://');
            $new->setHomepage($homepage);
            $new->setUser($this->getUserById($this->getIdentity()->getUserId()));
            $user = $this->getUserMapper()->save($new);
            return true;
        }
        return false;
    }

    public function showProfile($id)
    {
        if (!empty($id)){
            $user = $this->getUserById($id);
        } else {
            $user = $this->getUserById($this->getIdentity()->getUserId());
        }
        return $user;
    }

    public function deleteProfile($id)
    {
        if ($this->getUserMapper->find($id) != null){
            $res = $this->getEntityManager()->getRepository('Profile\Entity\User')->find($id);
            $this->getEntityManager()->remove($res);
        }
        $res = $this->getEntityManager()->getRepository('Profile\Entity\User')->find($id);
        $this->getEntityManager()->remove($res);
        $this->getEntityManager()->flush();
    }

    public function login($username, $password)
    {
        if($this->loginAllowed($username)) {
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

    public function logout()
    {
        $this->getAuthAdapter()->clearIdentity();
        session_regenerate_id();
    }

    public function hashPassWd($string)
    {
        $options = $this->getOptions();
        $hashpw = $string . $options['salt'];
        return hash('sha256', $hashpw);
    }

    public function getUserById($userId)
    {
        return $this->getUserMapper()->findId($userId);
    }

    public function getUserDataById($userId)
    {
        return $this->getUserMapper()->findUserDataById($userId);
    }

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

    public function upload($form, $request, $file)
    {
        $filter = new UploadFilter();
        $form->setInputFilter($filter->getInputFilter());

        $nonFile = $request->getPost()->toArray();
        $data = array_merge( $nonFile, array('fileupload'=> $file['name']));
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

    public function getUserByUsername($username)
    {
        return $this->getUserMapper()->findUserByUsername($username);
    }

    public function loginAllowed($username)
    {
        $user = $this->getUserByUsername($username);
        if($user instanceof User) {
            if($user->getLogin() === null) {
                return true;
            }
        }
        return false;
    }

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
