<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findsFavoriteAnnouncementsPaginated(int $page, int $limit = 10, $user): array
    {
        $limit = abs($limit);

        $result = [
            'items' => null,
            'pages' => null,
            'page' => null,
            'limit' => null,
        ];

        $pageLimit = max(1, $page);

        $queryBuilder = $this->createQueryBuilder('u')  // teraz 'u' reprezentuje użytkownika, bo jesteśmy w UserRepository
        ->join('u.favoriteAnnouncements', 'a')  // 'favoriteAnnouncements' to właściwość w encji User
        ->where('u = :user') // Tu filtrujemy dla konkretnego użytkownika
        ->setParameter('user', $user)
            ->orderBy('a.id', 'ASC') // Sortujesz po ogłoszeniach, więc 'a.id'
            ->setMaxResults($limit)
            ->setFirstResult(($pageLimit * $limit) - $limit);


        $paginator = new Paginator($queryBuilder);
        $data = $paginator->getQuery()->getResult();
        dd($data);
        //On vérifie qu'on a des données
        if (empty($data)) {
            return $result;
        }

        //On calcule le nombre de pages
        $pages = ceil($paginator->count() / $limit);

        // On remplit le tableau
        $result['items'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;

        return $result;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
