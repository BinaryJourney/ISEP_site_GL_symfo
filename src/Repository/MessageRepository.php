<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 *
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function add(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Message $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllMessagesBetween(int $uid1, int $uid2): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.key_sender = :uid1 AND u.key_receiver = :uid2')
            ->orWhere('u.key_receiver = :uid1 AND u.key_sender = :uid2')
            ->setParameter('uid1', $uid1)
            ->setParameter('uid2', $uid2)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws Exception
     */
    public function findAllThreadsOfUser(int $uid): array
    {

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT main.* FROM message main LEFT JOIN message earlier
                        ON earlier.timestamp > main.timestamp AND
                        (
                            earlier.key_receiver_id = main.key_receiver_id AND earlier.key_sender_id = main.key_sender_id OR
                            earlier.key_receiver_id = main.key_sender_id AND earlier.key_sender_id = main.key_receiver_id
                        )
                WHERE (main.key_receiver_id = :id OR main.key_sender_id = :id) AND earlier.id IS NULL
                ORDER BY main.timestamp DESC';

        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(['id' => $uid]);

        return $result->fetchAllAssociative();
    }

//    /**
//     * @return Message[] Returns an array of Message objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Message
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
