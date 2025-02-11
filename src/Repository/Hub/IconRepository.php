<?php

declare(strict_types=1);

namespace App\Repository\Hub;

use App\Entity\Hub\Icon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IconRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Icon::class);
    }
}
