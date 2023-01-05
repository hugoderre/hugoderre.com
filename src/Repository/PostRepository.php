<?php

namespace App\Repository;

use App\Entity\PostType\Post;
use App\Trait\RepositoryCustomFinders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method Post[]    findByTitleField(string $value)
 */
class PostRepository extends ServiceEntityRepository
{
    use RepositoryCustomFinders;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }
}
