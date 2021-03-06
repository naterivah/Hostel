<?php

namespace Bittich\HotelBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * ReservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservationRepository extends EntityRepository {

    public function findByUser($iduser, $nombreParPage, $page) {
        $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.client', 'u')
                ->andWhere('u.id = :iduser')
                ->addSelect('u')
                ->setParameter('iduser', $iduser);
        $qb->setFirstResult(($page - 1) * $nombreParPage)
                ->setMaxResults($nombreParPage);
            return new Paginator($qb);

    }

    public function findAll() {
        $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.client', 'u')
                ->addSelect('u');

        return $qb->getQuery()
                        ->getResult();
    }

    public function pagine($nombreParPage, $page) {
        if ($page < 1) {
            throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "' . $page . '").');
        }
        $qb = $this->createQueryBuilder('p')
                ->leftJoin('p.client', 'u')
                ->addSelect('u');
        $qb->setFirstResult(($page - 1) * $nombreParPage)
                ->setMaxResults($nombreParPage);
        return new Paginator($qb);
    }

}
