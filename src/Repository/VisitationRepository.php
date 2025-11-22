<?php

/**
 * Visitation Repository.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Visitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
}
