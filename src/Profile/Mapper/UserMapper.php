<?php
namespace Profile\Mapper;
/**
 * @category    Mapper
 * @package     profile\Mapper
 * @copyright   Copyright (c) 2013 S-P-IT.de
 * @author      Michael Müller <mueller@s-p-it.de>
 * @version     $Id: UserMapper.php 14 2013-04-25 13:59:29Z admin $
 */

/**
 * Mapper for all user interaction with database
 * @category    Mapper
 * @package     Profile\Mapper
 * @copyright   Copyright (c) 2013 S-P-IT.de
 */
use Profile\Repository\UserRepository;

class UserMapper
{

    /**
     * ServiceManager
     * @var ServiceManager
     */
    protected $_serviceManager;

    /**
     * EntityManager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_em;

    /**
     * Constructor
     * @param EntityManager $em
     */
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     * sets the Doctrine Entity Manager
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     * gets the Doctrine Entity Manager
     */
    public function getEntityManager()
    {
        if (null === $this->_em) {
            $this->_em = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        }
        return $this->_em;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->_serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->_serviceManager = $serviceManager;
        return $this;
    }

    /********************************** Funktions **********************************/

    public function save($entity)
    {
        return $this->_em->getRepository('Profile\Entity\User')->save($entity);
    }

    public function saveData($entity)
    {
        return $this->_em->getRepository('Profile\Entity\Userdata')->save($entity);
    }

    public function findId($id)
    {
        return $this->_em->getRepository('Profile\Entity\User')->findOneByuserid($id);
    }

    public function findUserDataById($userId)
    {
        return $this->_em->getRepository('Profile\Entity\UserData')->findOneByuserid($userId);
    }

    public function findUserByUsername($username)
    {
        return $this->_em->getRepository('Profile\Entity\User')->findOneByusername($username);
    }

    public function findUserByToken($token)
    {
        return $this->_em->getRepository('Profile\Entity\User')->findOneBytoken($token);
    }

    public function fetchAll()
    {
        return $this->_em->getRepository('Profile\Entity\User')->findAll();
    }

    public function deleteUser($entity)
    {
        return $this->_em->getRepository('Profile\Entity\User')->deleteUser($entity);
    }
}