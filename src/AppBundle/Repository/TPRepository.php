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
        $qb = $this->createQueryBuilder('tp')
            ->getQuery();

        return $qb->getResult();
    }

    public function findTPsByIdProc($id_proc)
    {
        $qb = $this->createQueryBuilder('tp')
            ->join('tp.procs', 'proc')
            ->where('proc.id = :id')
            ->setParameter('id', $id_proc)
            ->getQuery();

        return $qb->getArrayResult();
    }

    public function findAll()
    {
        $qb = $this->createQueryBuilder('v')
            ->getQuery();

        return $qb->getArrayResult();
    }

    public function findById($id) {
        $qb = $this->createQueryBuilder('tp')
            ->where('tp.id = :id')
            ->setParameter('id', $id)
            ->getQuery();

        return $qb->getArrayResult();
    }
}
