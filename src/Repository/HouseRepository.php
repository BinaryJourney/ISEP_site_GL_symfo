<?php

namespace App\Repository;

use App\Entity\House;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<House>
 *
 * @method House|null find($id, $lockMode = null, $lockVersion = null)
 * @method House|null findOneBy(array $criteria, array $orderBy = null)
 * @method House[]    findAll()
 * @method House[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HouseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, House::class);
    }

    public function add(House $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(House $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllStillAvailableHouses():array {

        return $this->createQueryBuilder('u')
            ->where('NOT u.date_end <= :today')
            ->setParameter('today', date('Y-m-d'))
            ->getQuery()
            ->getResult();
    }

    public function findAllStillAvailableHousesWithAccommodation(string $capacity):array {

        return $this->createQueryBuilder('u')
            ->where('NOT u.date_end <= :today')
            ->setParameter('today', date('Y-m-d'))
            ->leftJoin('u.key_type_accommodation_capacity', 'capacity')
            ->andWhere('capacity.capacity = :capacity')
            ->setParameter('capacity', $capacity)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return House[] Returns an array of House objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?House
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
