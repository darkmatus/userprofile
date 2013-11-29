<?php

namespace Profile\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;
use Profile\Entity\User;

class DisplayName extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var \Profile\Mapper\UserMapper
     */
    protected $userMapper;

    public function __construct($sm)
    {
        $this->serviceManager = $sm;
    }

    /**
     * __invoke
     *
     * @access public
     * @return String
     */
    public function __invoke(User $user = null)
    {
        if (null === $user) {
            if ($this->getAuthService()->hasIdentity()) {
                $user = $this->getUserMapper()->findId($this->getAuthService()->getIdentity());
                if (!$user instanceof User) {
                    return;
                }
            } else {
                return false;
            }
        }

        $displayName = $user->getDisplayname();
        if (null === $displayName) {
            $displayName = $user->getUsername();
        }
        if (null === $displayName) {
            $displayName = $user->getEmail();
            $displayName = substr($displayName, 0, strpos($displayName, '@'));
        }

        return $displayName;
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
     * @return \Profile\View\Helper\DisplayName
     */
    public function setAuthService(AuthenticationService $authService)
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
            $this->userMapper = $this->serviceManager->get('userMapper');
        }
        return $this->userMapper;
    }
}
