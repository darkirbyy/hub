<?php

declare(strict_types=1);

namespace App\Repository\Account;

use App\Entity\Account\MetaRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MetaRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MetaRole::class);
    }

    public function findAndSort(): array
    {
        // Build the query (fetch one more result to determine is there are more to fetch)
        $qb = $this->createQueryBuilder('m');
        $qb->orderBy('m.key', 'ASC');

        // Execute and fetch the query
        return $qb->getQuery()->getResult();
    }
}
