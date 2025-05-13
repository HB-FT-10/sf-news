<?php

namespace App\Repository;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[]
     */
    public function findHomepageArticles(): array
    {
        /*
        SELECT * FROM article
        WHERE created_at > DATE_SUB(CURRENT_DATE(), 1, 'YEAR')
        AND visible = 1
        ORDER BY created_at DESC
        LIMIT 10
        */
        return $this->createQueryBuilder('a')
            ->andWhere('a.createdAt > :lastYear')
            ->andWhere('a.visible = :visible')
            ->setParameter('lastYear', (new DateTime())->modify('-1 year'))
            ->setParameter('visible', true)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Article[]
     */
    public function findAllWithCategories(): array
    {
        return $this->createQueryBuilder('a')
            ->join('a.category', 'c')
            ->addSelect('c')
            ->getQuery()
            ->getResult();
    }
}