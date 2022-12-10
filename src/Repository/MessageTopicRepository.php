<?php

namespace App\Repository;

use App\Entity\MessageTopic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MessageTopic>
 *
 * @method MessageTopic|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageTopic|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageTopic[]    findAll()
 * @method MessageTopic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageTopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageTopic::class);
    }

    public function save(MessageTopic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MessageTopic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    /**
     * renvoie les derniers messages postés
     * @param int $number nombre de messages
     * @return array tableau de MessageTopic
     */
    public function getLastMessages(int $number): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.id', 'DESC')
            ->setMaxResults($number)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return MessageTopic[] Returns an array of MessageTopic objects
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

//    public function findOneBySomeField($value): ?MessageTopic
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
