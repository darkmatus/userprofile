<?php

namespace Profile\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;

class ProfileForm extends Form
{
    public $id;

    public function __construct($id = null, $name = null)
    {
        $this->id = $id;

        parent::__construct ( "Profile" );

        $this->setAttribute ( 'method', 'post' );
        $this->add(array(
                'name' => 'username',
                'options' => array (
                        'label' => 'Benutzername: '),
                'attributes' => array(
                        'type' => 'text',
                        'required' => 'true' ) ) );

        $this->add(array(
                'name' => 'password',
                'options' => array (
                    'label' => 'Passwort: '),
                'attributes' => array(
                        'type' => 'password' ) ) );
        $this->add(array(
                'name' => 'lastname',
                'options' => array (
                        'label' => 'Nachname'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'name',
                'options' => array (
                    'label' => 'Vorname'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'id','attributes' => array(
                        'value' =>  $this->id,
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'name_description',
                'options' => array (
                        'label' => 'Bedeutung des Benutzernamens'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'homepage',
                'options' => array (
                        'label' => 'Homepage'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'googleplus',
                'options' => array (
                        'label' => 'Google+'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'facebook',
                'options' => array (
                        'label' => 'Facebookname'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'myspace',
                'options' => array (
                        'label' => 'Myspacename'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'signature',
                'options' => array (
                    'label' => 'Signatur'),
                'attributes' => array(
                    'type' => 'textarea',
                    'class' => 'ckeditor') ) );
        $this->add(array(
                'name' => 'hobby',
                'options' => array (
                        'label' => 'Hobbys'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'city',
                'options' => array (
                        'label' => 'Wohnort'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'icq',
                'options' => array (
                        'label' => 'ICQ'),
                'attributes' => array(
                        'type' => 'text' ) ) );
        $this->add(array(
                'name' => 'blog',
                'options' => array (
                        'label' => 'Blog'),
                'attributes' => array(
                        'type' => 'text' ) ) );

        $this->add(array('type' => 'Zend\Form\Element\Select',
                'name' => 'gender','options' => array (
                'label' => 'Geschlecht','value_options' => array (
                        '1' => 'Wähle dein Geschlecht aus','weiblich' => 'weiblich','männlich' => 'männlich' ) ),'attributes' => array (
                                'value' => '1' )
         ) );

        $this->add(array(
            'name' => 'birth',
            'options' => array (
                'label' => 'Geburtstag (Tag.Monat.Jahr)'),
                'attributes' => array(
                    'type' => 'text')
            )
        );

        $this->add(array(
                'name' => 'submit',
                'attributes' => array(
                        'type' => 'submit',
                        'value' => 'aktualisieren',
                        'id' => 'submitbutton' ) ) );
    }
}