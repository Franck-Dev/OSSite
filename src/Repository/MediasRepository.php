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
        if ($types) {
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
        } else {
            $tbMedias=[];
        }

        return $tbMedias;
    }

    /**
     * myFindByUserProfile Permet de récupérer tous les médias suivant les autorisations du user
     *
     * @param  array $listautorisations
     * @param  string $site
     * @param  string $tri (ASC ou DESC)
     * @return void
     */
    public function myFindByUserProfile($listautorisations,$site,$convention,$tri){
        $qb=$this->createQueryBuilder('u')
            ->where('u.visibilite IN (:list)')
            ->andWhere('u.perimetre IN (:site)')
            ->setParameter('list', $listautorisations)
            ->setParameter('site', $site)
            ->orderBy('u.createdAt', $tri);
        $query = $qb->getQuery();
        $results = $query->getResult();
        return $results;
    }

    /**
     * myFindByDivision Permet de récupérer tous les médias suivant la division demandée
     *
     * @param  string $division
     * @param  string $tri (ASC ou DESC)
     * @return void
     */
    public function myFindByDivision($division,$tri){
        $qb=$this->createQueryBuilder('u')
            ->Where('u.perimetre LIKE :division')
            ->setParameter('division', '%'.$division.'%')
            ->orderBy('u.createdAt', $tri);
        $query = $qb->getQuery();
        $results = $query->getResult();
        return $results;
    }
}
