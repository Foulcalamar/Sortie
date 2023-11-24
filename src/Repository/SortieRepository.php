<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function rechercheSorties($data)
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.campus', 'c');

        if (!empty($data->campus)) {
            $qb->andWhere('c.id = :campus')
                ->setParameter('campus', $data->campus);
        }

        if (!empty($data->cle)) {
            $qb->andWhere('s.nom LIKE :cle')
                ->setParameter('cle', "%{$data->cle}%");
        }

        if (!empty($data->dateFrom)) {
            $qb->andWhere('s.dateHeureDebut >= :dateFrom')
                ->setParameter('dateFrom', $data->dateFrom);
        }

        if (!empty($data->dateTo)) {
            $qb->andWhere('s.dateHeureDebut <= :dateTo')
                ->setParameter('dateTo', $data->dateTo);
        }

        if (!empty($data->organisateurRecherche)) {
            $qb->andWhere('s.organisateur = :organisateur');
        }

        if (!empty($data->inscritsRecherche)) {
            $qb->andWhere('s.participants IN (:inscrits)');
        }

        if (!empty($data->noninscritsRecherche)) {
            $qb->andWhere('s.participants NOT IN (:inscrits)');
        }

        if (!empty($data->pasee)) {
            $qb->andWhere('s.etat = :Pasée');
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
