<?php

namespace App\Decorator;

use App\Entity\ResetPasswordRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

class TokenRequestRepositoryDecorator
//class TokenRequestRepositoryDecorator extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
//    use ResetPasswordRequestRepositoryTrait;
//
//    public function __construct(ManagerRegistry $registry, string $entityClass)
//    {
//        parent::__construct($registry, $entityClass);
//    }
//    public function createResetPasswordRequest(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken): ResetPasswordRequestInterface
//    {
//        return new ResetPasswordRequest($user, $expiresAt, $selector, $hashedToken);
//    }
}