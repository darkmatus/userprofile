<?php

namespace Profile\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;

class LoginForm extends Form
{

    public function __construct()
    {

        parent::__construct ( "Login" );

        $this->setAttribute ( 'method', 'post' );

        $this->add(array(
                'name' => 'username',
                'options' => array (
                        'label' => 'Benutzername: '),
                'attributes' => array(
                        'type' => 'text' ) ) );

        $this->add(array(
                'name' => 'password',
                'options' => array (
                    'label' => 'Passwort: '),
                'attributes' => array(
                        'type' => 'password' ) ) );

        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                        'type' => 'submit',
                        'value' => 'Einloggen',
                        'id' => 'submitbutton' ) ) );
    }
}