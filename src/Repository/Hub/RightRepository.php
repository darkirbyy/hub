<?php

declare(strict_types=1);

namespace App\Repository\Hub;

use App\Entity\Hub\Right;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RightRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Right::class);
    }

    /**
     * Finds all Right entities and sorts them, first by the name of the appli their are related to, second by their role.
     *
     * @return Right[] the sorted list of Right entities
     */
    public function findAndSort(): array
    {
        $qb = $this->createQueryBuilder('r');
        $qb->leftJoin('r.appli', 'a')->orderBy('a.name', 'ASC')->addOrderBy('r.role', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
