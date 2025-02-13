<?php

declare(strict_types=1);

namespace App\Repository\Hub;

use App\Entity\Hub\Appli;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppliRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appli::class);
    }

    public function findAndSort(): array
    {
        // Build the query (fetch one more result to determine is there are more to fetch)
        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin('a.category', 'c') // Explicitly join the category table
            ->orderBy('c.number', 'ASC')
            ->addOrderBy('a.number', 'ASC');

        // Execute and fetch the query
        return $qb->getQuery()->getResult();
    }
}
