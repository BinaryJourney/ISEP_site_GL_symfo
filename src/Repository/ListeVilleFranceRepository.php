<?php

namespace App\Repository;

use App\Entity\ListeVilleFrance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function PHPUnit\Framework\exactly;

/**
 * @extends ServiceEntityRepository<ListeVilleFrance>
 *
 * @method ListeVilleFrance|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListeVilleFrance|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListeVilleFrance[]    findAll()
 * @method ListeVilleFrance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListeVilleFranceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListeVilleFrance::class);
    }

    public function add(ListeVilleFrance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ListeVilleFrance $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ListeVilleFrance[]
     */
    public function findAllMatching(array $query, int $limit=22): array
    {
        $stringQuery = implode('', $query);
        $stringQuery = preg_replace('/-/', '-|', $stringQuery);
        $stringQuery = preg_replace('/\s+/', '-|', $stringQuery);
        $stringQuery = preg_replace('/_/', '-|', $stringQuery);
        $tempArray = explode('|', $stringQuery);
        foreach($tempArray as &$value) {
            $value = ucfirst($value);
        }
        unset($value);
        $stringQuery = implode('', $tempArray);

        return $this->createQueryBuilder('u')
            ->andWhere('u.name LIKE :query')
            ->setParameter('query', '%'.$stringQuery.'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return ListeVilleFrance[] Returns an array of ListeVilleFrance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ListeVilleFrance
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
