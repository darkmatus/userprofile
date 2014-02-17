<?php

namespace Profile\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Profile\Form\LoginForm as LoginForm;
use Zend\View\Model\ViewModel;

class LoginWidget extends AbstractHelper
{
    /**
     * Login Form
     * @var LoginForm
     */
    protected $_loginForm;

    /**
     * $var string template used for view
     */
    protected $_viewTemplate;
    /**
     * __invoke
     *
     * @access public
     * @return string
     */
    public function __invoke()
    {

        $vm = new ViewModel(array(
            'form' => $this->getLoginForm(),
        ));
        $vm->setTemplate('profile/profile/login.phtml');
        return $this->getView()->render($vm);
    }

    /**
     * Retrieve Login Form Object
     * @return LoginForm
     */
    public function getLoginForm()
    {
        return $this->_loginForm;
    }

    /**
     * Inject Login Form Object
     * @param LoginForm $loginForm
     * @return UserLoginWidget
     */
    public function setLoginForm(LoginForm $loginForm)
    {
        $this->_loginForm = $loginForm;
        return $this;
    }

    /**
     * @param string $viewTemplate
     * @return UserLoginWidget
     */
    public function setViewTemplate($viewTemplate)
    {
        $this->_viewTemplate = $viewTemplate;
        return $this;
    }

}
