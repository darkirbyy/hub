<?php

declare(strict_types=1);

namespace App\Repository\Other;

use App\Entity\Other\Tool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ToolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tool::class);
    }

    public function findAndGroup(): array
    {
        // Build the query (fetch one more result to determine is there are more to fetch)
        $qb = $this->createQueryBuilder('s');
        $qb->orderBy('s.type', 'ASC')->addOrderBy('s.number', 'ASC');

        // Execute and fetch the query
        $tools = $qb->getQuery()->getResult();

        // Group by type
        $toolsByType = [];
        foreach ($tools as $tool) {
            $type = $tool->getType();
            if (!isset($toolsByType[$type])) {
                $toolsByType[$type] = [];
            }
            $toolsByType[$type][] = $tool;
        }

        return $toolsByType;
    }
}
