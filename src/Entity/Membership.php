<?php

/**
 * Membership Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MembershipRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Membership.
 */
#[ORM\Entity(repositoryClass: MembershipRepository::class)]
class Membership
{
	/**
	 * Id.
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	/**
	 * Start date.
	 */
	#[ORM\Column]
	private ?\DateTime $startDate = null;

	/**
	 * End date.
	 */
	#[ORM\Column]
	private ?\DateTime $endDate = null;

	/**
	 * Member id.
	 */
	#[ORM\ManyToOne(inversedBy: 'memberships')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Member $memberId = null;

	/**
	 * Plan id.
	 */
	#[ORM\ManyToOne(inversedBy: 'memberships')]
	#[ORM\JoinColumn(nullable: true)]
	private ?Plan $planId = null;

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
	 * Get start date.
	 *
	 * @return \DateTime|null
	 */
	public function getStartDate(): ?\DateTime
	{
		return $this->startDate;
	}

	/**
	 * Set start date.
	 *
	 * @param \DateTime $startDate
	 *
	 * @return static
	 */
	public function setStartDate(\DateTime $startDate): static
	{
		$this->startDate = $startDate;

		return $this;
	}

	/**
	 * Get end date.
	 *
	 * @return \DateTime|null
	 */
	public function getEndDate(): ?\DateTime
	{
		return $this->endDate;
	}

	/**
	 * Set end date.
	 *
	 * @param \DateTime $endDate
	 *
	 * @return static
	 */
	public function setEndDate(\DateTime $endDate): static
	{
		$this->endDate = $endDate;

		return $this;
	}

	/**
	 * Get member id.
	 *
	 * @return Member|null
	 */
	public function getMemberId(): ?Member
	{
		return $this->memberId;
	}

	/**
	 * Set member id.
	 *
	 * @param Member|null $memberId
	 *
	 * @return static
	 */
	public function setMemberId(?Member $memberId): static
	{
		$this->memberId = $memberId;

		return $this;
	}

	/**
	 * Get plan id.
	 *
	 * @return Plan|null
	 */
	public function getPlanId(): ?Plan
	{
		return $this->planId;
	}

	/**
	 * Set plan id.
	 *
	 * @param Plan|null $planId
	 *
	 * @return static
	 */
	public function setPlanId(?Plan $planId): static
	{
		$this->planId = $planId;

		return $this;
	}
}
