<?php

/**
 * Group Repository.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class GroupRepository.
 *
 * @extends ServiceEntityRepository<Group>
 */
class GroupRepository extends ServiceEntityRepository
{
	/**
	 * Constructor.
	 *
	 * @param ManagerRegistry $registry Manager registry.
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Group::class);
	}
}
