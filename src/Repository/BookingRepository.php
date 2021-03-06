<?php

namespace App\Repository;

use App\Entity\Booking;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 *
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function add(Booking $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Booking $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllAcceptedBookingDates(): array {

        return $this->createQueryBuilder('u')
            ->select('u.date_begin')
            ->addSelect('u.date_end')
            ->leftJoin('u.key_house', 'house')
            ->leftJoin('u.status', 'status')
            ->where('status.status = :status')
            ->setParameter('status', 'ACCEPTE')
            ->getQuery()
            ->getResult();
    }

    public function findAllOversteppingBookings(Booking $acceptedBooking): array {

        return $this->createQueryBuilder('u')
            ->where('u.date_begin >= :date_begin')
            ->andWhere('u.date_end <= :date_end')
            ->andWhere('u.id != :id')
            ->setParameter('date_begin', $acceptedBooking->getDateBegin()->format('Y-m-d'))
            ->setParameter('date_end', $acceptedBooking->getDateEnd()->format('Y-m-d'))
            ->setParameter('id', $acceptedBooking->getId())
            ->getQuery()
            ->getResult();

    }

//    /**
//     * @return Booking[] Returns an array of Booking objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Booking
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
