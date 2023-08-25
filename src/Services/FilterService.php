<?php

namespace App\Services;

use Doctrine\ORM\QueryBuilder;

class FilterService
{
    public function applySentenceFilter(QueryBuilder $queryBuilder, array $filters): ?QueryBuilder
    {
        if (empty($filters) || !isset($filters['search'])) return $queryBuilder;

        return $queryBuilder->andWhere('a.title LIKE :searchTerm OR a.description LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $filters['search'] . '%');
    }

    public function applyMinPriceFilter(QueryBuilder $queryBuilder, array $filters): QueryBuilder
    {
        if (empty($filters) || !isset($filters['min_price'])) return $queryBuilder;

        return $queryBuilder->andWhere('a.price >= :min')
            ->setParameter('min', $filters['min_price']);
    }

    public function applyMaxPriceFilter(QueryBuilder $queryBuilder, array $filters): QueryBuilder
    {
        if (empty($filters) || !isset($filters['max_price'])) return $queryBuilder;

        return $queryBuilder->andWhere('a.price <= :max')
            ->setParameter('max', $filters['max_price']);
    }

    public function applySortingFilter(QueryBuilder $queryBuilder, array $filters): QueryBuilder
    {
        if (empty($filters) || !isset($filters['sorting'])) return $queryBuilder;

        switch ($filters['sorting']) {
            case 'price_asc':
                $queryBuilder->orderBy('a.price', 'ASC');
                break;
            case 'price_desc':
                $queryBuilder->orderBy('a.price', 'DESC');
                break;
        }
        return $queryBuilder;
    }

    public function applyCategoriesFilter(QueryBuilder $queryBuilder, array $filters): QueryBuilder
    {
        if (empty($filters) || !isset($filters['category'])) return $queryBuilder;

        return $queryBuilder->join('a.category', 'c')
            ->andWhere('c.slug = :category')
            ->setParameter('category', $filters['category']);
    }

    public function applyConditionsFilter(QueryBuilder $queryBuilder, array $filters): QueryBuilder
    {
        if (empty($filters['condition_type'])) return $queryBuilder;

        $conditions = (array) $filters['condition_type'];  // Upewnij się, że to jest tablica

        return $queryBuilder->andWhere('a.conditionType IN (:conditions)')
            ->setParameter('conditions', $conditions);
    }
}
