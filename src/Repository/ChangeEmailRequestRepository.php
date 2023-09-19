<?php

namespace App\Repository;

use App\Decorator\TokenRequestRepositoryDecorator;
use App\Entity\ChangeEmailRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChangeEmailRequest>
 *
 * @method ChangeEmailRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChangeEmailRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChangeEmailRequest[]    findAll()
 * @method ChangeEmailRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChangeEmailRequestRepository extends TokenRequestRepositoryDecorator
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChangeEmailRequest::class);
    }
}
