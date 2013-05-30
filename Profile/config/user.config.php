<?php
return array(
    /*
     * define your salt which crypts the password
     */
    'salt' => '362V2PwaLhAr2IkV356QTGT26VEZFo56X0UUsXo8km41qECN6PFrvSHLBQGX6TS',

    /*
     * define your identity colum in your database
     */
    'identity' => 'username',

    /*
     * define the password column
     */
    'credential' => 'password',

    'doctrineAuth' => array(
        'objectManager' => 'doctrine.entitymanager.orm_default',
        'objectRepository' => 'Profile\Repository\User' ,
        'identityClass' => 'Profile\Entity\User',
        'identityProperty' => 'username',
        'credentialProperty' => 'password'
    ),

    /*
     * defines which Imagetypes are allowed
     */
    'profileImage' => array(
        'jpeg', 'jpg', 'gif', 'png'
    ),

    'mail' =>array(
        'subject' => 'Deine Registrierung auf Schwarzes-Sachsen.de',
        'from' => 'kontakt@schwarzes-sachsen.de',
    ),
);