<?php

declare(strict_types=1);

namespace App\Repository\Account;

use App\Entity\Account\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    public function findAndSort(): array
    {
        // Build the query (fetch one more result to determine is there are more to fetch)
        $qb = $this->createQueryBuilder('r');
        $qb->leftJoin('r.appli', 'a')->orderBy('a.name', 'ASC')->addOrderBy('r.key', 'ASC');

        // Execute and fetch the query
        return $qb->getQuery()->getResult();
    }
}
