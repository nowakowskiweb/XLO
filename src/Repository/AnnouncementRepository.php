<?php

namespace App\Repository;

use App\Entity\Announcement;
use App\Form\Type\CategoriesType;
use App\Form\Type\ConditionType;
use App\Form\Type\SortingType;
use App\Services\FilterService;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

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
    private $filterService;
    public function __construct(ManagerRegistry $registry,FilterService $filterService)
    {
        parent::__construct($registry, Announcement::class);
        $this->filterService = $filterService;
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

    public function addPublishedAnnouncements(QueryBuilder $queryBuilder): QueryBuilder
    {
        return $queryBuilder->andWhere('a.published = :published')
            ->setParameter('published', true);
    }

    public function findsAnnouncementsPaginated(int $page, int $limit = 10, Request $request): array
    {
        $limit = abs($limit);

        $filters = [
            'search' => $request->query->get('search'),
            'min_price' => $request->query->get('min_price'),
            'max_price' => $request->query->get('max_price'),
            'sorting' => $request->query->get('sorting'),
            'category' => $request->query->get('category'),
            'condition_type' => $request->query->all('condition_type')
        ];

        $filters = array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        });

        $pageLimit = max(1, $page);

        $query = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($pageLimit * $limit) - $limit);

        $queryBuilder = $this->addPublishedAnnouncements($query);

        $queryBuilder = $this->filterService->applySentenceFilter($queryBuilder, $filters);
        $queryBuilder = $this->filterService->applyMinPriceFilter($queryBuilder, $filters);
        $queryBuilder = $this->filterService->applyMaxPriceFilter($queryBuilder, $filters);
        $queryBuilder = $this->filterService->applySortingFilter($queryBuilder, $filters);
        $queryBuilder = $this->filterService->applyCategoriesFilter($queryBuilder, $filters);
        $queryBuilder = $this->filterService->applyConditionsFilter($queryBuilder, $filters);

        $paginator = new Paginator($queryBuilder);
        $data = $paginator->getQuery()->getResult();

        if (isset($filters['condition_type'])) $filters['condition_type'] = ConditionType::mapConditions($filters['condition_type']);
        if (isset($filters['sorting'])) $filters['sorting'] = SortingType::mapSorting([$filters['sorting']]);
        if (isset($filters['category'])) $filters['category'] = CategoriesType::mapCategories([$filters['category']]);

        $result = [
            'items' => null,
            'pages' => null,
            'page' => null,
            'limit' => null,
            'filters' => $filters,
        ];

        if (empty($data)) {
            return $result;
        }

        $pages = ceil($paginator->count() / $limit);

        $result['items'] = $data;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;

        return $result;
    }

    public function findFavoritesForUser($userId)
    {
        return $this->createQueryBuilder('a')
            ->join('a.favoritedBy', 'f')
            ->where('f.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function findsPostedAnnouncementsPaginated(int $page, int $limit = 10, $user): array
    {
        $limit = abs($limit);

        $result = [
            'items' => null,
            'pages' => null,
            'page' => null,
            'limit' => null,
        ];

        $pageLimit = max(1, $page);

        $queryBuilder = $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($pageLimit * $limit) - $limit);

        $paginator = new Paginator($queryBuilder);
        $postedAnnouncements = $paginator->getQuery()->getResult();

        if (empty($postedAnnouncements)) {
            return $result;
        }

        $pages = ceil($paginator->count() / $limit);

        $result['items'] = $postedAnnouncements;
        $result['pages'] = $pages;
        $result['page'] = $page;
        $result['limit'] = $limit;

        return $result;
    }

}
