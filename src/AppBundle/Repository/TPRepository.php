<?php

namespace AppBundle\Repository;

/**
 * TPRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TPRepository extends \Doctrine\ORM\EntityRepository
{
    public function listAll()
    {
        $qb = $this->createQueryBuilder('v')
            ->getQuery();

        return $qb->getResult();
    }
}
