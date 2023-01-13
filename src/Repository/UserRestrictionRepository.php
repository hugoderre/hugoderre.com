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

	public function getIpRestrictionList($restrictionType): array
	{
		$ips = [];

		switch($restrictionType) {
			case 'all':
				$restrictions = $this->findAll();
				break;
			case 'ban':
				$restrictions = $this->findBy(['ban' => true]);
				break;
			case 'soft':
				$restrictions = $this->findBy(['ban' => false]);
				break;
			default:
				throw new \InvalidArgumentException('Invalid restriction type');
		}

		foreach ($restrictions as $restriction) {
			$ips[] = $restriction->getIp();
		}

		return $ips;
	}

	public function isIpRestricted($ip, $restrictionType = 'all'): bool
	{
		if (in_array($ip, $this->getIpRestrictionList($restrictionType), true)) {
			return true;
		}

		return false;
	}
}
