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
	 * Member subscriber.
	 */
	#[ORM\ManyToOne(inversedBy: 'memberships')]
	#[ORM\JoinColumn(nullable: false)]
	private ?Member $memberSubscriber = null;

	/**
	 * Plan.
	 */
	#[ORM\ManyToOne(inversedBy: 'memberships')]
	#[ORM\JoinColumn(nullable: true)]
	private ?Plan $plan = null;

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
	 * Get member subscriber.
	 *
	 * @return Member|null
	 */
	public function getMemberSubscriber(): ?Member
	{
		return $this->memberSubscriber;
	}

	/**
	 * Set member subscriber.
	 *
	 * @param Member|null $member
	 *
	 * @return static
	 */
	public function setMemberSubscriber(?Member $memberSubscriber): static
	{
		$this->memberSubscriber = $memberSubscriber;

		return $this;
	}

	/**
	 * Get plan.
	 *
	 * @return Plan|null
	 */
	public function getPlan(): ?Plan
	{
		return $this->plan;
	}

	/**
	 * Set plan.
	 *
	 * @param Plan|null $plan
	 *
	 * @return static
	 */
	public function setPlan(?Plan $plan): static
	{
		$this->plan = $plan;

		return $this;
	}
}
