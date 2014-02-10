<?php
return array(
    'routes' => array(
        'profile' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/profile/',//[:action/[:id]]',
                'defaults' => array(
                    'controller' => 'profile',
                    'action'     => 'index',
                ),
            ),
        ),
        'show' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/profile/show/[:id]',
                'defaults' => array(
                    'controller' => 'profile',
                    'action'     => 'show',
                ),
            ),
        ),
        'logout' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/logout',
                'defaults' => array(
                    'controller' => 'profile',
                    'action'     => 'logout',
                ),
            ),
        ),
        'login' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/login/',
                'defaults' => array(
                    'controller' => 'profile',
                    'action'     => 'login',
                ),
            ),
        ),
        'register' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/register[/:token]',
                'defaults' => array(
                    'controller' => 'profile',
                    'action'     => 'register',
                ),
            ),
        ),
        'profile_admin' => array(
            'type' => 'Segment',
            'options' => array(
                'route' => '/admin/profile/[:action/[:id]]',
                'defaults' => array(
                    'controller' => 'profileAdmin',
                    'action'     => 'index',
                    ),
            ),
        ),
    ),
);
?>
