<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Icon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IconRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Icon::class);
    }

    /**
     * Finds all Icon entities and sorts them by their label.
     *
     * @return Icon[] the sorted list of Icon entities
     */
    public function findAndSort(): array
    {
        $qb = $this->createQueryBuilder('i');
        $qb->orderBy('i.label', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
