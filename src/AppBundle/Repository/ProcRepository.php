<?php

namespace AppBundle\Repository;

/**
 * ProcRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProcRepository extends \Doctrine\ORM\EntityRepository
{
    public function listAll()
    {
        $qb = $this->createQueryBuilder('v')
            ->getQuery();

        return $qb->getResult();
    }
}
