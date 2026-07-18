<?php

/**
 * Plan Repository.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Plan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class PlanRepository.
 *
 * @extends ServiceEntityRepository<Plan>
 */
class PlanRepository extends ServiceEntityRepository
{
	/**
	 * Constructor.
	 *
	 * @param ManagerRegistry $registry Manager registry.
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Plan::class);
	}

	/**
	 * Find latest.
	 *
	 * @param array $options Options.
	 *
	 * @return mixed
	 */
	public function findLatest(array $options = []): mixed
	{
		$offset = $options['offset'] ?? 0;
		$perPage = $options['perPage'] ?? 20;
		$order = $options['order'] ?? 'ASC';

		$qb = $this->createQueryBuilder('p')
			->select('p as plan', 'COUNT(m.id) as activeMemberCount')
			->leftJoin('p.memberships', 'm', 'WITH', 'm.endDate > :now OR m.endDate IS NULL')
			->groupBy('p.id')
			->addOrderBy('p.id', $order)
			->setFirstResult($offset)
			->setMaxResults($perPage)
			->setParameter('now', new \DateTime());

		return new Paginator($qb->getQuery(), false);
	}
}
