<?php
namespace Profile;

use Profile\View\Helper\UserAdmin;

use Profile\Service\ProfileService as profile;
use Profile\Controller\Plugin\UserIdent;
use Profile\View\Helper\DisplayName;
use Profile\View\Helper\LoginWidget;
use Profile\Form\LoginForm;
use Zend\Mail\Storage;
use Profile\View\Helper\UserIdentity;
use Doctrine\ORM\EntityManager;
use Profile\Mapper\UserMapper;
use Profile\View\Helper\Bbcode;
use Zend\Authentication\AuthenticationService;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class Module implements ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array ('Zend\Loader\ClassMapAutoloader' => array (__DIR__ . '/autoload_classmap.php'),
            'Zend\Loader\StandardAutoloader' => array ('namespaces' => array (
                __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    public function getServiceConfig()
    {
        return array(
           'factories' => array(
                'Service\Profile' => function($sm)
                {
                    return new Service\ProfileService($sm);
                },
                'Zend\Authentication\AuthenticationService' => function($sm) {
                    return $sm->get('doctrine.authenticationservice.orm_default');
                },
                'userMapper' => function ($sm) {
                    $mapper = new UserMapper($sm->get('Doctrine\ORM\EntityManager'));
                    return $mapper;
                },
                'emailRenderer' => function($sm) {
                $obj = $sm->get('Zend\View\Renderer\PhpRenderer');
                return $obj;
                },
                'auth_service' => function ($sm) {
                    return new \Zend\Authentication\AuthenticationService();
                },
                'login_form' => function($sm) {
                    $form = new LoginForm();
                    return $form;
                },
           ),
       );
    }
    public function onBootstrap(\Zend\EventManager\EventInterface $e)
    {
        $application = $e->getApplication();
        $serviceManager = $application->getServiceManager();

        $controllerLoader = $serviceManager->get('ControllerLoader');

        // Add initializer to Controller Service Manager that check if controllers needs entity manager injection
        $controllerLoader->addInitializer(function ($instance) use ($serviceManager) {
            if (method_exists($instance, 'setEntityManager')) {
                $instance->setEntityManager($serviceManager->get('doctrine.entitymanager.orm_default'));
            }
        });
    }

    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'DisplayName' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new DisplayName($locator);
                    $viewHelper->setAuthService($locator->get('auth_service'));
                    return $viewHelper;
                },
                'UserId' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new UserIdentity();
                    $viewHelper->setAuthService($locator->get('auth_service'));
                    return $viewHelper;
                },
                'isAdmin' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new UserAdmin($sm);
                    $viewHelper->setAuthService($locator->get('auth_service'));
                    return $viewHelper;
                },
                'UserLoginWidget' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $viewHelper = new LoginWidget();
                    $viewHelper->setLoginForm($locator->get('login_form'));
                    return $viewHelper;
                },
            ),
        );
    }

    public function getControllerPluginConfig()
    {
        return array(
            'factories' => array(
                'UserIdent' => function ($sm) {
                    $locator = $sm->getServiceLocator();
                    $plugin = new UserIdent();
                    $plugin->setAuthService($locator->get('auth_service'));
                    $plugin->setUserMapper($locator->get('userMapper'));
                    return $plugin;
                },
            ),
        );
    }
}