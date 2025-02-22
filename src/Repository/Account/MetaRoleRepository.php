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

    /**
     * Finds all MetaRole entities and sorts them by their key.
     *
     * @return MetaRole[] the sorted list of MetaRole entities
     */
    public function findAndSort(): array
    {
        $qb = $this->createQueryBuilder('m');
        $qb->orderBy('m.key', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
