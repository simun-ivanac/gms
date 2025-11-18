<?php

/**
 * ResetPasswordRequest Repository.
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ResetPasswordRequest;
use App\Entity\TeamMember;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

/**
 * Class ResetPasswordRequestRepository.
 *
 * @extends ServiceEntityRepository<ResetPasswordRequest>
 */
class ResetPasswordRequestRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
	use ResetPasswordRequestRepositoryTrait;

	/**
	 * Constructor.
	 *
	 * @param ManagerRegistry $registry Manager registry.
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, ResetPasswordRequest::class);
	}

	/**
	 * Create reset password request.
	 *
	 * @param TeamMember         $user        Team member.
	 * @param \DateTimeInterface $expiresAt   Expires at.
	 * @param string             $selector    Selector.
	 * @param string             $hashedToken Hashed token.
	 *
	 * @return ResetPasswordRequest
	 */
	public function createResetPasswordRequest(
		object $user,
		\DateTimeInterface $expiresAt,
		string $selector,
		string $hashedToken
	): ResetPasswordRequestInterface {
		return new ResetPasswordRequest($user, $expiresAt, $selector, $hashedToken);
	}
}
