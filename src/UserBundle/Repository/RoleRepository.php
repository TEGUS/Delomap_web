<?php

namespace UserBundle\Repository;

/**
 * RoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAll()
    {
        $qb = $this->createQueryBuilder('role')
            ->getQuery();

        return $qb->getResult();
    }
}
