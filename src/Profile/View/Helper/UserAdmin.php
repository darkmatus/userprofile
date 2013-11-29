<?php

namespace Profile\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Authentication\AuthenticationService;

class UserAdmin extends AbstractHelper
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    protected $_sm;

    /**
     * __invoke
     *
     * @access public
     * @return \Profile\Entity\User
     */
    public function __invoke()
    {
        if ($this->getAuthService()->hasIdentity()) {
            $admin   = $this->getAuthService()->getIdentity();
            $mapper  = $this->_sm->getServiceLocator()->get('userMapper');
            $isAdmin = $mapper->findId($admin['userid']);
            if ($isAdmin->getIsAdmin() === 1) {
                return true;
            }
        } else {
            return false;
        }
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
     * @return \Profile\View\Helper\UserIdentity
     */
    public function setAuthService(AuthenticationService $authService)
    {
        $this->authService = $authService;
        return $this;
    }

    public function __construct($sm)
    {
        $this->_sm = $sm;
    }

}
