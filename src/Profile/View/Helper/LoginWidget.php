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
    protected $loginForm;

    /**
     * $var string template used for view
     */
    protected $viewTemplate;
    /**
     * __invoke
     *
     * @access public
     * @return string
     */
    public function __invoke()
    {
//         if (array_key_exists('render', $options)) {
//             $render = $options['render'];
//         } else {
//             $render = true;
//         }
//         if (array_key_exists('redirect', $options)) {
//             $redirect = $options['redirect'];
//         } else {
//             $redirect = false;
//         }

        $vm = new ViewModel(array(
            'form' => $this->getLoginForm(),
        ));
        $vm->setTemplate('profile/profile/login.phtml');
//         if ($render) {
            return $this->getView()->render($vm);
//         } else {
//             return $vm;
//         }
    }

    /**
     * Retrieve Login Form Object
     * @return LoginForm
     */
    public function getLoginForm()
    {
        return $this->loginForm;
    }

    /**
     * Inject Login Form Object
     * @param LoginForm $loginForm
     * @return ZfcUserLoginWidget
     */
    public function setLoginForm(LoginForm $loginForm)
    {
        $this->loginForm = $loginForm;
        return $this;
    }

    /**
     * @param string $viewTemplate
     * @return ZfcUserLoginWidget
     */
    public function setViewTemplate($viewTemplate)
    {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }

}
