<?php

declare(strict_types=1);

namespace App\Repository\Hub;

use App\Entity\Hub\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function findAndSort(): array
    {
        // Build the query (fetch one more result to determine is there are more to fetch)
        $qb = $this->createQueryBuilder('c');
        $qb->orderBy('c.number', 'ASC');

        // Execute and fetch the query
        return $qb->getQuery()->getResult();
    }
}
