<?php

namespace App\Trait;

trait RepositoryCustomFinders
{
    public function findByFields(array $fields, string $orderBy, int $limit = null, int $offset = null)
    {
        $query = $this->createQueryBuilder('p');
        foreach ($fields as $key => $args) {
            $query->andWhere(sprintf('p.%1$s %2$s :%1$s', $key, $args['operator'] ?? '='))
                ->setParameter($key, $args['value']);
        }
        $query->orderBy('p.id', $orderBy);
        $query->setMaxResults($limit);
        $query->setFirstResult($offset);
        return $query->getQuery()->getResult();
    }
}