<?php

declare(strict_types=1);

namespace App\Repository\Other;

use App\Entity\Other\Shortcut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ShortcutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shortcut::class);
    }

    /**
     * Finds all Shortcut entities and sorts them, first by their type, second by their number order.
     *
     * @return Shortcut[] the sorted list of Shortcut entities
     */
    public function findAndSort(): array
    {
        $qb = $this->createQueryBuilder('s');
        $qb->orderBy('s.type', 'ASC')->addOrderBy('s.number', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Finds, sorts, and groups Shortcut entities by type.
     *
     * @return array<string, Shortcut[]> an associative array where keys are shortcut types and values are arrays of Shortcut entities
     */
    public function findAndSortAndGroup(): array
    {
        $shortcuts = $this->findAndSort();

        $shortcutsByType = [];
        foreach ($shortcuts as $shortcut) {
            $type = $shortcut->getType();
            if (!isset($shortcutsByType[$type])) {
                $shortcutsByType[$type] = [];
            }
            $shortcutsByType[$type][] = $shortcut;
        }

        return $shortcutsByType;
    }
}
