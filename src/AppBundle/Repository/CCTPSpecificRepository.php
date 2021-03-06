<?php

namespace AppBundle\Repository;

/**
 * CCTPSpecificRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CCTPSpecificRepository extends \Doctrine\ORM\EntityRepository
{
    public function listAll()
    {
        $qb = $this->createQueryBuilder('cctp_s')
            ->getQuery();

        return $qb->getResult();
    }

    public function listAllByProjet($id_projet)
    {
        $qb = $this->createQueryBuilder('cctp_s')
            ->join('cctp_s.projet', 'projet')
            ->where('projet.id = :id')
            ->setParameter('id', $id_projet)
            ->getQuery();

        return $qb->getArrayResult();
    }
}
