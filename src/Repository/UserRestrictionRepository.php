<?php

namespace App\Repository;

use App\Entity\UserRestriction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserRestriction>
 *
 * @method UserRestriction|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserRestriction|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserRestriction[]    findAll()
 * @method UserRestriction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRestrictionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserRestriction::class);
    }

    public function add(UserRestriction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserRestriction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

	public function getIpBlacklist(): array
	{
		$ips = $this->createQueryBuilder('ur')
			->select('ur.ip')
			->where('ur.ban = true')
			->getQuery()
			->getResult();

		return array_map(fn ($ip) => $ip['ip'], $ips);
	}
}
