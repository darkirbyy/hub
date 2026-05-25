<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Finds all Category entities and sorts them by the number order.
     *
     * @return Category[] the sorted list of Category entities
     */
    public function findAndSort(): array
    {
        $qb = $this->createQueryBuilder('c');
        $qb->orderBy('c.number', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
