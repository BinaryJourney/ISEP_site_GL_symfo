<?php

namespace App\Repository;

use App\Entity\TypeAccommodationCapacity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeAccommodationCapacity>
 *
 * @method TypeAccommodationCapacity|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeAccommodationCapacity|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeAccommodationCapacity[]    findAll()
 * @method TypeAccommodationCapacity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeAccommodationCapacityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeAccommodationCapacity::class);
    }

    public function add(TypeAccommodationCapacity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeAccommodationCapacity $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TypeAccommodationCapacity[] Returns an array of TypeAccommodationCapacity objects
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

//    public function findOneBySomeField($value): ?TypeAccommodationCapacity
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
