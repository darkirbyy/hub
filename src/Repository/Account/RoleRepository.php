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

    /**
     * Finds all Role entities and sorts them, first by the name of the appli their are related to, second by their key.
     *
     * @return Role[] the sorted list of Role entities
     */
    public function findAndSort(): array
    {
        $qb = $this->createQueryBuilder('r');
        $qb->leftJoin('r.appli', 'a')->orderBy('a.name', 'ASC')->addOrderBy('r.key', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
