<?php

namespace App\Repository;

use App\Entity\Announcement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Announcement>
 *
 * @method Announcement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Announcement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Announcement[]    findAll()
 * @method Announcement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnouncementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announcement::class);
    }

    public function save(Announcement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Announcement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Announcement[] Returns an array of Announcement objects
     */
    public function findPublished($value): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.images', 'i')
            ->leftJoin('a.user', 'u')  // Add a join to load the user
            ->leftJoin('a.categories', 'c')  // Add a join to load the categories
            ->addSelect('i','u', 'c')  // Make sure to select images, user, and categories
            ->andWhere('a.published = :published')
            ->setParameter('published', $value)
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function paginatorQuery($value)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.images', 'i')
            ->leftJoin('a.user', 'u')  // Add a join to load the user
            ->leftJoin('a.categories', 'c')  // Add a join to load the categories
            ->addSelect('i','u', 'c')  // Make sure to select images, user, and categories
            ->andWhere('a.published = :published')
            ->setParameter('published', $value)
            ->orderBy('a.id', 'ASC')
            ->getQuery();
    }


    public function findsAnnouncementsPaginated(int $page, int $limit = 10): array
    {
        $limit = abs($limit);

        $result = [];

        $query = $this->createQueryBuilder('a')
            ->andWhere('a.published = :published')
            ->setParameter('published', true)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit) - $limit);

        $paginator = new Paginator($query);
        $data = $paginator->getQuery()->getResult();

        //On vérifie qu'on a des données
        if(empty($data)){
            return $result;
        }

        //On calcule le nombre de pages
        $pages = ceil($paginator->count() / $limit);

        // On remplit le tableau
        $result['data'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;

        return $result;
    }


//    /**
//     * @return Announcement[] Returns an array of Announcement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Announcement
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
