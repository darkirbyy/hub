<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Appli;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppliRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appli::class);
    }

    /**
     * Finds all Appli entities and sorts them, first by the number order of their category, second by their own number order.
     *
     * @return Appli[] the sorted list of Appli entities
     */
    public function findAndSort(): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->leftJoin('a.category', 'c') // Explicitly join the category table
            ->orderBy('c.number', 'ASC')
            ->addOrderBy('a.number', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
