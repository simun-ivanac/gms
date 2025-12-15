<?php

/**
 * Visitation Repository.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Visitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class VisitationRepository.
 *
 * @extends ServiceEntityRepository<Visitation>
 */
class VisitationRepository extends ServiceEntityRepository
{
	/**
	 * Constructor.
	 *
	 * @param ManagerRegistry $registry Manager registry.
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Visitation::class);
	}

	/**
	 * Get all visitations.
	 *
	 * @param array $options Options.
	 *
	 * @return array
	 */
	public function findAllEntries(array $options = []): array
	{
		$perPage = $options['perPage'] ?? 60;
		$page = $options['page'] ?? 1;
		$orderBy = $options['orderBy'] ?? 'DESC';
		$offset = ($page - 1) * $perPage;

		$qb = $this->createQueryBuilder('v')
			->orderBy('v.timestamp', $orderBy)
			->setMaxResults($perPage)
			->setFirstResult($offset);

		return $this->formatEntries(new Paginator($qb->getQuery()));
	}

	/**
	 * Find visitations for team member.
	 *
	 * @param int   $teamMemberId Team Member ID.
	 * @param array $options      Options.
	 *
	 * @return array
	 */
	public function findByTeamMember(int $teamMemberId, array $options = []): array
	{
		$perPage = $options['perPage'] ?? 30;
		$page = $options['page'] ?? 1;
		$orderBy = $options['orderBy'] ?? 'DESC';
		$offset = ($page - 1) * $perPage;

		$qb = $this->createQueryBuilder('v')
			->andWhere('v.teamMember = :val')
			->setParameter('val', $teamMemberId)
			->orderBy('v.timestamp', $orderBy)
			->setMaxResults($perPage)
			->setFirstResult($offset);

		return $this->formatEntries(new Paginator($qb->getQuery()), 'team-member');
	}

	/**
	 * Find visitations for member.
	 *
	 * @param int   $memberId Member ID.
	 * @param array $options  Options.
	 *
	 * @return array
	 */
	public function findByMember(int $memberId, array $options = []): array
	{
		$perPage = $options['perPage'] ?? 30;
		$page = $options['page'] ?? 1;
		$orderBy = $options['orderBy'] ?? 'DESC';
		$offset = ($page - 1) * $perPage;

		$qb = $this->createQueryBuilder('v')
			->andWhere('v.member = :val')
			->setParameter('val', $memberId)
			->orderBy('v.timestamp', $orderBy)
			->setMaxResults($perPage)
			->setFirstResult($offset);

		return $this->formatEntries(new Paginator($qb->getQuery()), 'member');
	}

	/**
	 * Format (and filter) results.
	 *
	 * @param Paginator $paginator Paginator.
	 * @param string    $user      User.
	 *
	 * @return array
	 */
	private function formatEntries(Paginator $paginator, string $user = ''): array
	{
		$arrResult = [];

		foreach ($paginator as $entry) {
			$newEntry = [
				'id' => $entry->getId(),
				'timestamp' => $entry->getTimestamp(),
				'message' => $entry->getMessage(),
				'status' => $entry->getStatus(),
			];

			if ($entry->getTeamMember() && $user !== 'team-member') {
				$newEntry['teamMemberId'] = $entry->getTeamMember()->getId();
			}

			if ($entry->getMember() && $user !== 'member') {
				$newEntry['memberId'] = $entry->getMember()->getId();
			}

			$arrResult[] = $newEntry;
		}

		return $arrResult;
	}
}
