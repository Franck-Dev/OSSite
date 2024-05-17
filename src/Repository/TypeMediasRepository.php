<?php

namespace App\Repository;

use App\Entity\TypeMedias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeMedias>
 *
 * @method TypeMedias|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeMedias|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeMedias[]    findAll()
 * @method TypeMedias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeMediasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeMedias::class);
    }

    //    /**
    //     * @return TypeMedias[] Returns an array of TypeMedias objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TypeMedias
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
