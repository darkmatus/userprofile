<?php
namespace Profile\Filter;
/**
 * @category    Test
 * @package     Test_Model
 * @subpackage  Adapter
 * @copyright   Copyright (c) 2010 Unister GmbH
 * @author      Unister GmbH <teamleitung-dev@unister-gmbh.de>
 * @author      Michael Müller <michael.mueller@unister.de>
 * @version     $Id: RegisterFilter.php 14 2013-04-25 13:59:29Z admin $
 */

/**
 * Kurze Beschreibung der Klasse
 *
 * Lange Beschreibung der Klasse (wenn vorhanden)...
 *
 * @category    Test
 * @package     Test_Model
 * @subpackage  Adapter
 * @copyright   Copyright (c) 2013 Unister GmbH
 */
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class RegisterFilter
{
    protected $inputFilter;

public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Nicht genutzt.");
    }

    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                    'name'     => 'username',
                    'required' => true,
                    'filters'  => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                    ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'displayname',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                    'name' => 'password',
                    'required' => true,
                    'filters' => array(
                        array(
                        'name' => 'StringTrim')
                        ),
                    'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 6,
                            'max'      => 128
                        )
                    )
                )
            )));

            $inputFilter->add($factory->createInput(array(
                'name' => 'password_verify',
                'required' => true,
                'filters' => array(
                     array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array( 'min' => 6 ),
                    ),
                    array(
                        'name' => 'identical',
                        'options' => array(
                            'token' => 'password' )
                        ),
                    ),
                )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}