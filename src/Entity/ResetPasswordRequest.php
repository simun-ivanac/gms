<?php

/**
 * ResetPasswordRequest Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

/**
 * Class ResetPasswordRequest.
 */
#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
	use ResetPasswordRequestTrait;

	/**
	 * Id.
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	/**
	 * User.
	 */
	#[ORM\ManyToOne]
	#[ORM\JoinColumn(nullable: false)]
	private ?TeamMember $user = null;

	/**
	 * Constructor.
	 *
	 * @param TeamMember         $user        User.
	 * @param \DateTimeInterface $expiresAt   Expires at.
	 * @param string             $selector    Selector.
	 * @param string             $hashedToken Hashed token.
	 */
	public function __construct(
		TeamMember $user,
		\DateTimeInterface $expiresAt,
		string $selector,
		string $hashedToken
	) {
		$this->user = $user;
		$this->initialize($expiresAt, $selector, $hashedToken);
	}

	/**
	 * Get id.
	 *
	 * @return int|null
	 */
	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * Get user.
	 *
	 * @return TeamMember
	 */
	public function getUser(): TeamMember
	{
		return $this->user;
	}
}
