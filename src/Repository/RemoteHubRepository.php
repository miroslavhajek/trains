<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\RemoteHub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RemoteHub>
 */
class RemoteHubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RemoteHub::class);
    }
}
