<?php

namespace Profile\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserdataRepository extends EntityRepository
{
    /**
     * saves Userdata
     * @param Userdata $entity
     */
    public function save($entity)
    {
        return $this->persist($entity);
    }

    /**
     * deletes an user by Entity
     * @param Entity $delete
     */
    public function deleteUser($delete)
    {
        $this->getEntityManager()->remove($delete);
        if ($this->getEntityManager()->flush()) {
            return true;
        }
        return false;
    }

    /**
     * sets the Entity and write it to the Database
     * @param UserEntity $entity
     */
    protected function persist($entity)
    {
        $this->getEntityManager()->persist($entity);
        return $this->getEntityManager()->flush();
    }
}