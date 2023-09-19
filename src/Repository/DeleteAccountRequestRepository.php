<?php

namespace App\Repository;

use App\Entity\DeleteAccountRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeleteAccountRequest>
 *
 * @method DeleteAccountRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeleteAccountRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeleteAccountRequest[]    findAll()
 * @method DeleteAccountRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeleteAccountRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeleteAccountRequest::class);
    }

//    /**
//     * @return DeleteAccountRequest[] Returns an array of DeleteAccountRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DeleteAccountRequest
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
