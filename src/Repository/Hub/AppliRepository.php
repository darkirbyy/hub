<?php

declare(strict_types=1);

namespace App\Repository\Hub;

use App\Entity\Hub\Appli;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AppliRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appli::class);
    }
}
