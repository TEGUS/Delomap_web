<?php

namespace AppBundle\Repository;

/**
 * ContractantRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContractantRepository extends \Doctrine\ORM\EntityRepository
{
    public function listAll()
    {
        $qb = $this->createQueryBuilder('contractant')
            ->getQuery();

        return $qb->getResult();
    }
}
