<?php

declare(strict_types=1);

namespace App\Repository\Param;

use App\Entity\Param\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class StatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Status::class);
    }

    public function findByOrder(): array
    {
        // Build the query (fetch one more result to determine is there are more to fetch)
        $qb = $this->createQueryBuilder('s');
        $qb->orderBy('s.number', 'ASC');

        // Execute and fetch the query
        return $qb->getQuery()->getResult();
    }
}
