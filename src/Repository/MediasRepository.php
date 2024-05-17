<?php

namespace App\Repository;

use App\Entity\Medias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medias>
 *
 * @method Medias|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medias|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medias[]    findAll()
 * @method Medias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medias::class);
    }

        /**
        * @param  integer $limit
        * @return Medias[] Returns an array of Medias objects
        */        
        public function findByTypeMedias($limit): array
        {
            $types=$this->createQueryBuilder('m')
                ->select('t.libelle')
                ->leftJoin ('m.type','t')
                ->groupBy('t.libelle')
                ->getQuery()
                ->getResult()
            ;

            foreach ($types as $key => $type) {
                $tbMedias[$type['libelle']]=$this->createQueryBuilder('m')
                ->select('m')
                ->leftJoin ('m.type','t')
                ->where('t.libelle =:val')
                ->andWhere('m.isArchived = false')
                ->setParameter('val', $type)
                ->orderBy('m.createdAt', 'DESC')
                ->getQuery()
                ->getResult()
            ;

            }
            return $tbMedias;
        }


    //    public function findOneBySomeField($value): ?Medias
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
