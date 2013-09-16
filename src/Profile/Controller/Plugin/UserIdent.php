<?php

namespace Profile\Controller\Plugin;

use Profile\Service\ProfileService;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserIdent extends AbstractPlugin
{
    /**
     * @var ProfileService
     */
    protected $profileService;

    /**
     * @var UserMapper
     */
    protected $userMapper;

    /**
     * @var AuthService
     */
    protected $authService;

    /**
     * @var AuthAdapter
     */
    protected $authAdapter;

    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Proxy convenience method
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->getAuthService()->hasIdentity();
    }

    /**
     * Proxy convenience method
     *
     * @return mixed
     */
    public function getIdentity()
    {
        if ($this->getAuthService()->getIdentity() != null) {
            $id = $this->getAuthService()->getIdentity();
            $user = $this->getUserMapper()->findId($id['userid']);
            $user->setPassword(null);
            return $user;
        }
        return $this->getAuthService()->getIdentity();
    }

    /**
     * Get authService.
     *
     * @return AuthenticationService
     */
    public function getAuthService()
    {
        return $this->authService;
    }

    /**
     * Set authService.
     *
     * @param AuthenticationService $authService
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
        return $this;
    }

    /**
     * getUserMapper
     *
     * @return \Profile\Mapper\UserMapper
     */
    public function getUserMapper()
    {
        if (null === $this->userMapper) {
            $this->userMapper = $this->serviceManager ->get('userMapper');
        }
        return $this->userMapper;
    }

    public function setUserMapper($mapper)
    {
        $this->userMapper = $mapper;
        return $this;
    }
}
