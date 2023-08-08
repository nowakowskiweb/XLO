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
        if (empty($filters) || !isset($filters['minPrice'])) return $queryBuilder;

        return $queryBuilder->andWhere('a.price >= :min')
            ->setParameter('min', $filters['min_price']);
    }

    public function applyMaxPriceFilter(QueryBuilder $queryBuilder, array $filters): QueryBuilder
    {
        if (empty($filters) || !isset($filters['maxPrice'])) return $queryBuilder;

        return $queryBuilder->andWhere('a.price <= :max')
            ->setParameter('max', $filters['max_price']);
    }

    public function applyCategoriesFilter(QueryBuilder $queryBuilder, array $filters): QueryBuilder
    {
        if (empty($filters) || !isset($filters['categories'])) return $queryBuilder;

        return $queryBuilder->join('a.categories', 'c')
            ->andWhere('c.id IN (:categories)')
            ->setParameter('categories', $filters['categories']);
    }

    public function applyConditionsFilter(QueryBuilder $queryBuilder, array $filters): QueryBuilder
    {
        if (empty($filters) || !isset($filters['conditionType'])) return $queryBuilder;

        return $queryBuilder->andWhere('a.conditionType = :conditionType')
            ->setParameter('conditionType', $filters['conditionType']);
    }
}
