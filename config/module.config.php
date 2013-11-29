<?php
namespace Profile;

return array(
        'view_manager' => array(
                'template_path_stack' => array(
                        'profile' => __DIR__ . '/../view',
                ),
        ),

        'router' => include __DIR__ . '/router.config.php',

        'controllers' => array(
                'invokables' => array(
                        'profile'      => 'Profile\Controller\ProfileController',
                        'profileAdmin' => 'Profile\Controller\ProfileAdminController',
                ),
        ),
        'doctrine' => array(
            'authentication' => array(
                'orm_default' => array(
                    'object_manager' => 'Doctrine\ORM\EntityManager',
                    'identity_class' => 'Profile\Entity\User',
                    'identity_property' => 'username',
                    'credential_property' => 'password',
                ),
            ),
            'driver' => array(
                'profile_entity' => array(
                    'class' => 'Doctrine\ORM\Mapping\Driver\XmlDriver',
                    'paths' => __DIR__ . '/xml/profile'
                ),
                'orm_default' => array(
                    'drivers' => array(
                        'Profile\Entity'  => 'profile_entity'
                    )
                )
            )
        ),
        'session' => array(
                'remember_me_seconds' => 2419200,
                'use_cookies' => true,
                'cookie_httponly' => true,
        ),
        'user_config' => include 'config/autoload/user.config.php',
    );