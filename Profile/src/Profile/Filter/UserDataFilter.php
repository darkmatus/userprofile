<?php
namespace Profile\Filter;
/**
 * @category    Test
 * @package     Test_Model
 * @subpackage  Adapter
 * @copyright   Copyright (c) 2010 Unister GmbH
 * @author      Unister GmbH <teamleitung-dev@unister-gmbh.de>
 * @author      Michael Müller <michael.mueller@unister.de>
 * @version     $Id: UserDataFilter.php 14 2013-04-25 13:59:29Z admin $
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

class UserDataFilter
{
    protected $inputFilter;

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'blog',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            'validators' => array(
                array(
                'name'    => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 3,
                        'max'      => 300,
                    ),
                ),
            ))));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'homepage',
                'required' => false,
                'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 3,
                        'max'      => 300,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'googleplus',
                'required' => false,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 300,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'facebook',
                'required' => false,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 300,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'myspace',
                'required' => false,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 300,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'signature',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 500,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'hobby',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 3,
                            'max'      => 300,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                        'encoding' => 'UTF-8',
                        'min'      => 3,
                        'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'gender',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'InArray',
                        'options' => array(
                            'haystack' => array('weiblich','männlich'),
                            'messages' => array(
                                'notInArray' => 'Bitte Geschlecht angeben!'
                            ),
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'icq',
                'required' => false,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
}