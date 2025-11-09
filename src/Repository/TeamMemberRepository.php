<?php

/**
 * TeamMember Repository.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TeamMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Class TeamMemberRepository.
 *
 * @extends ServiceEntityRepository<TeamMember>
 */
class TeamMemberRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
	/**
	 * Constructor.
	 *
	 * @param ManagerRegistry $registry Manager registry.
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, TeamMember::class);
	}

	/**
	 * Used to upgrade (rehash) the user's password automatically over time.
	 *
	 * @param PasswordAuthenticatedUserInterface $user The user interface.
	 * @param string $newHashedPassword The new hashed password.
	 *
	 * @return void
	 */
	public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
	{
		if (!$user instanceof TeamMember) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
		}

		$user->setPassword($newHashedPassword);
		$this->getEntityManager()->persist($user);
		$this->getEntityManager()->flush();
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

		$qb = $this->createQueryBuilder('tm')
			->addOrderBy('tm.createdAt', $order)
			->addOrderBy('tm.id', $order)
			->leftJoin('tm.teamRoles', 'tr')
			->addSelect('tr')
			->setFirstResult($offset)
			->setMaxResults($perPage);

		return new Paginator($qb->getQuery());
	}
}
