<?php
namespace Profile\Form;
/**
 * @category    Test
 * @package     Test_Model
 * @subpackage  Adapter
 * @copyright   Copyright (c) 2010 Unister GmbH
 * @author      Unister GmbH <teamleitung-dev@unister-gmbh.de>
 * @author      Michael Müller <michael.mueller@unister.de>
 * @version     $Id: UploadForm.php 18 2013-05-27 11:17:30Z admin $
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
use Zend\Form\Form;

class UploadForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('Profile');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');

        $this->add(array(
            'name' => 'fileupload',
            'attributes' => array(
                'type'  => 'file',
            ),
            'options' => array(
            'label' => 'Benutzerfoto hochladen',
            ),
        ));


        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Upload'
            ),
        ));
    }
}