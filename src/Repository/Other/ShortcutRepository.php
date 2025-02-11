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

    public function findAndGroup(): array
    {
        // Build the query (fetch one more result to determine is there are more to fetch)
        $qb = $this->createQueryBuilder('s');
        $qb->orderBy('s.type', 'ASC')->addOrderBy('s.number', 'ASC');

        // Execute and fetch the query
        $shortcuts = $qb->getQuery()->getResult();

        // Group by type
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
