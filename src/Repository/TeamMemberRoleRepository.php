<?php

/**
 * Team Member Role Repository.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TeamMemberRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TeamMemberRoleRepository.
 *
 * @extends ServiceEntityRepository<Role>
 */
class TeamMemberRoleRepository extends ServiceEntityRepository
{
	/**
	 * Constructor.
	 *
	 * @param ManagerRegistry $registry Manager registry.
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, TeamMemberRole::class);
	}
}
