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
    private $announcementRepository;

    public function __construct(ManagerRegistry $registry, AnnouncementRepository $announcementRepository)
    {
        parent::__construct($registry, User::class);
        $this->announcementRepository = $announcementRepository;
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

        // Pobierz wszystkie ulubione ogłoszenia jako tablicę
        $allAnnouncements = $user->getFavoriteAnnouncements()->toArray();

        if (empty($allAnnouncements)) {
            return $result;
        }

        // Oblicz całkowitą liczbę stron
        $totalAnnouncements = count($allAnnouncements);
        $pages = ceil($totalAnnouncements / $limit);

        // Wybierz odpowiednią porcję ogłoszeń dla danej strony
        $favoriteAnnouncements = array_slice($allAnnouncements, ($pageLimit - 1) * $limit, $limit);

        // On remplit le tableau
        $result['items'] = $favoriteAnnouncements;
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
