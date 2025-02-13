<?php

declare(strict_types=1);

namespace App\Repository\Hub;

use App\Entity\Hub\Icon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IconRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Icon::class);
    }

    public function findAndSort(): array
    {
        // Build the query (fetch one more result to determine is there are more to fetch)
        $qb = $this->createQueryBuilder('i');
        $qb->orderBy('i.label', 'ASC');

        // Execute and fetch the query
        return $qb->getQuery()->getResult();
    }
}
