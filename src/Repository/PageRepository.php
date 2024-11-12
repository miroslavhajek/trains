<?php declare(strict_types=1);

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Page>
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }


    public function findPublishedPage(string $slug): ?Page
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.publishedAt <= CURRENT_TIMESTAMP()')
            ->andWhere('p.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * @return Page[]
     */
    public function findPublishedPages(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.publishedAt IS NOT NULL')
            ->orderBy('p.publishedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
