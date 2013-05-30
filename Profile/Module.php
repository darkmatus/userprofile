<?php
namespace Profile;

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
        return array ('Zend\Loader\ClassMapAutoloader' => array (
            __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array (
                'namespaces' => array (__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
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
                'Service\Profile' =>  function($sm)
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
                }
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
}