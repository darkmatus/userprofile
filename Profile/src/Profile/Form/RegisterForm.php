<?php

namespace Profile\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;

class RegisterForm extends Form
{

    public function __construct()
    {

        parent::__construct ( "Registrieren" );

        $this->setAttribute ( 'method', 'post' );

        $this->add(array(
                'name' => 'username',
                'options' => array (
                        'label' => 'Benutzername*: '),
                'attributes' => array(
                        'type' => 'text' ) ) );

        $this->add(array(
            'name' => 'displayname',
            'options' => array (
                'label' => 'Anzeigename*: '),
                'attributes' => array(
                    'type' => 'text' ) ) );

        $this->add(array(
            'name' => 'city',
            'options' => array (
                'label' => 'Wohnort*: '),
                'attributes' => array(
                    'type' => 'text' ) ) );

        $this->add(array(
            'name' => 'email',
            'options' => array (
                    'label' => 'Emailadresse*: '),
            'attributes' => array(
                    'type' => 'email' ) ) );

        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
                'maxlength' => '128', 'size' => '32'),
            'options' => array(
                'label' => 'Passwort*:'),
        ));

        $this->add(array(
                'name' => 'password_verify',
                'type' => 'Zend\Form\Element\Password',
                'attributes' => array(
                    'maxlength' => '128', 'size' => '32'),
                'options' => array(
                    'label' => 'Passwortwiederholung*:'),
        ));

        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                        'type' => 'submit',
                        'value' => 'Einloggen',
                        'id' => 'submitbutton' ) ) );
    }
}