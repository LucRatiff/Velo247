<?php

namespace App\Repository;

use App\Entity\TakenUsernames;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TakenUsernames>
 *
 * @method TakenUsernames|null find($id, $lockMode = null, $lockVersion = null)
 * @method TakenUsernames|null findOneBy(array $criteria, array $orderBy = null)
 * @method TakenUsernames[]    findAll()
 * @method TakenUsernames[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TakenUsernamesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TakenUsernames::class);
    }

    public function save(TakenUsernames $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TakenUsernames $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TakenUsernames[] Returns an array of TakenUsernames objects
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

//    public function findOneBySomeField($value): ?TakenUsernames
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
