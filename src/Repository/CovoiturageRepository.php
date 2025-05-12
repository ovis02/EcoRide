<?php

namespace App\Repository;

use App\Entity\Covoiturage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Covoiturage>
 */
class CovoiturageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Covoiturage::class);
    }

    /**
     * Recherche les covoiturages selon les critères de recherche facultatifs.
     */
    public function findFiltered(
        ?string $depart, 
        ?string $destination, 
        ?string $date, 
        ?int $passagers, 
        ?bool $eco = null, 
        ?float $prixMax = null, 
        ?int $dureeMax = null, 
        ?int $noteMin = null
    ): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.chauffeur', 'u')
            ->leftJoin('c.vehicule', 'v')
            ->addSelect('u', 'v')
            ->orderBy('c.dateDepart', 'ASC');

        if ($depart) {
            $qb->andWhere('c.depart LIKE :depart')
               ->setParameter('depart', '%' . $depart . '%');
        }

        if ($destination) {
            $qb->andWhere('c.arrivee LIKE :destination')
               ->setParameter('destination', '%' . $destination . '%');
        }

        if ($date) {
            // Filtre sur la journée entière
            $dateObj = new \DateTime($date);
            $startOfDay = (clone $dateObj)->setTime(0, 0, 0);
            $endOfDay = (clone $dateObj)->modify('+1 day')->setTime(0, 0, 0);

            $qb->andWhere('c.dateDepart >= :startOfDay')
               ->andWhere('c.dateDepart < :endOfDay')
               ->setParameter('startOfDay', $startOfDay)
               ->setParameter('endOfDay', $endOfDay);
        }

        if ($passagers) {
            $qb->andWhere('c.nbPlacesDispo >= :passagers')
               ->setParameter('passagers', $passagers);
        }

        if ($eco !== null) {
            $qb->andWhere('c.estEcologique = :eco')
               ->setParameter('eco', $eco);
        }

        if ($prixMax !== null) {
            $qb->andWhere('c.prix <= :prixMax')
               ->setParameter('prixMax', $prixMax);
        }

        if ($noteMin !== null) {
            $qb->leftJoin('u.avisRecus', 'avis')
               ->groupBy('c.id')
               ->having('AVG(avis.note) >= :noteMin')
               ->setParameter('noteMin', $noteMin);
        }

        return $qb->getQuery()->getResult();
    }
}
