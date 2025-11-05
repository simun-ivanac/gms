<?php

/**
 * Member Repository.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Member;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class MemberRepository.
 *
 * @extends ServiceEntityRepository<Member>
 */
class MemberRepository extends ServiceEntityRepository
{
	/**
	 * Constructor.
	 *
	 * @param ManagerRegistry $registry Manager registry.
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Member::class);
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
		$orderBy = $options['orderBy'] ?? 'id';
		$order = $options['order'] ?? 'ASC';

		$qb = $this->createQueryBuilder('m')
			->orderBy("m.{$orderBy}", $order)
			->setFirstResult($offset)
			->setMaxResults($perPage)
			->getQuery();

		return $qb->getResult();
	}
}
