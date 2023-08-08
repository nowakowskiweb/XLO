<?php

namespace App\Repository;

use App\Entity\Announcement;
use App\Services\FilterService;
use Doctrine\ORM\QueryBuilder;
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

    public function findsAnnouncementsPaginated(int $page, int $limit = 10, $request): array
    {
        $limit = abs($limit);

        $filters = [
            'search' => $request->query->get('search'),
            'minPrice' => $request->query->get('min-price'),
            'maxPrice' => $request->query->get('max-price'),
            'sorting' => $request->query->get('sorting'),
            'conditionType' => $request->query->get('conditionType')
        ];

        $filters = array_filter($filters, function ($value) {
            return !is_null($value) && $value !== '';
        });


        $result = [
            'items' => null,
            'pages' => null,
            'page' => null,
            'limit' => null,
            'filters' => null,
        ];


        $query = $this->createQueryBuilder('a')
            ->orderBy('a.id', 'ASC')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit) - $limit);

        $queryBuilder = $this->addPublishedAnnouncements($query);

        $queryBuilder = $this->filterService->applySentenceFilter($queryBuilder, $filters);
        $queryBuilder = $this->filterService->applyMinPriceFilter($queryBuilder, $filters);
        $queryBuilder = $this->filterService->applyMaxPriceFilter($queryBuilder, $filters);
        $queryBuilder = $this->filterService->applyCategoriesFilter($queryBuilder, $filters);
        $queryBuilder = $this->filterService->applyConditionsFilter($queryBuilder, $filters);

        $paginator = new Paginator($queryBuilder);
        $data = $paginator->getQuery()->getResult();

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
        $result['filters'] = $filters;

        return $result;
    }
}
