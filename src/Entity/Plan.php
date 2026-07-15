<?php

/**
 * Plan Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Gedmo\Mapping\Annotation\SoftDeleteable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Plan.
 */
#[ORM\Entity(repositoryClass: PlanRepository::class)]
#[SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: false)]
class Plan
{
	/**
	 * Id.
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	/**
	 * Name.
	 */
	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Assert\Length(min: 2, max: 255)]
	private ?string $name = null;

	/**
	 * Type.
	 */
	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Assert\Choice(['recurring', 'one-time'])]
	private ?string $type = null;

	/**
	 * Price.
	 */
	#[ORM\Column]
	#[Assert\NotBlank]
	#[Assert\PositiveOrZero]
	private ?int $price = null;

	/**
	 * Currency.
	 */
	#[ORM\Column(length: 255)]
	#[Assert\Currency]
	private ?string $currency = 'EUR';

	/**
	 * Duration in days.
	 */
	#[ORM\Column]
	#[Assert\NotBlank]
	#[Assert\Positive]
	private ?int $durationInDays = null;

	/**
	 * Are visitations limited? (yes: define manually limit, no: unlimited).
	 */
	#[ORM\Column]
	#[Assert\NotNull]
	#[Assert\Type('bool')]
	private ?bool $areVisitationsLimited = null;

	/**
	 * Number of visitations (if limited).
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Positive]
	private ?int $numOfVisitations = null;

	/**
	 * Time period.
	 */
	#[ORM\Column(length: 255, nullable: true)]
	#[Assert\Choice(['daily', 'weekly', 'monthly', 'in-total'])]
	private ?string $timePeriod = null;

	/**
	 * Is plan active.
	 */
	#[ORM\Column]
	#[Assert\Type('bool')]
	private ?bool $isActive = true;

	/**
	 * Status changed at.
	 */
	#[ORM\Column(nullable: true)]
	#[Timestampable(on: 'change', field: 'isActive')]
	private ?\DateTimeImmutable $statusChangedAt = null;

	/**
	 * Created at.
	 */
	#[ORM\Column]
	#[Timestampable(on: 'create')]
	private ?\DateTimeImmutable $createdAt = null;

	/**
	 * Updated at.
	 */
	#[ORM\Column]
	#[Timestampable(on: 'update')]
	private ?\DateTimeImmutable $updatedAt = null;

	/**
	 * Deleted at.
	 */
	#[ORM\Column(nullable: true)]
	private ?\DateTimeImmutable $deletedAt = null;

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
	 * Get name.
	 *
	 * @return string|null
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * Set name.
	 *
	 * @param string $name Name.
	 *
	 * @return static
	 */
	public function setName(string $name): static
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get type.
	 *
	 * @return string|null
	 */
	public function getType(): ?string
	{
		return $this->type;
	}

	/**
	 * Set type.
	 *
	 * @param string $type Type.
	 *
	 * @return static
	 */
	public function setType(string $type): static
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Get price.
	 *
	 * @return int|null
	 */
	public function getPrice(): ?int
	{
		return $this->price;
	}

	/**
	 * Set price.
	 *
	 * @param int $price Price.
	 *
	 * @return static
	 */
	public function setPrice(int $price): static
	{
		$this->price = $price;

		return $this;
	}

	/**
	 * Get currency.
	 *
	 * @return string|null
	 */
	public function getCurrency(): ?string
	{
		return $this->currency ?: 'eur';
	}

	/**
	 * Set currency.
	 *
	 * @param string $currency Currency.
	 *
	 * @return static
	 */
	public function setCurrency(string $currency): static
	{
		$this->currency = $currency;

		return $this;
	}

	/**
	 * Get duration in days.
	 *
	 * @return int|null
	 */
	public function getDurationInDays(): ?int
	{
		return $this->durationInDays;
	}

	/**
	 * Set duration in days.
	 *
	 * @param int $durationInDays Duration in days.
	 *
	 * @return static
	 */
	public function setDurationInDays(int $durationInDays): static
	{
		$this->durationInDays = $durationInDays;

		return $this;
	}

	/**
	 * Get if visitations are limited.
	 *
	 * @return bool|null
	 */
	public function getAreVisitationsLimited(): ?bool
	{
		return $this->areVisitationsLimited;
	}

	/**
	 * Set if visitations are limited.
	 *
	 * @param bool $areVisitationsLimited Are visitations limited.
	 *
	 * @return static
	 */
	public function setAreVisitationsLimited(bool $areVisitationsLimited): static
	{
		$this->areVisitationsLimited = $areVisitationsLimited;

		return $this;
	}

	/**
	 * Get number of allowed visitations.
	 *
	 * @return int|null
	 */
	public function getNumOfVisitations(): ?int
	{
		return $this->numOfVisitations;
	}

	/**
	 * Set number of allowed visitations.
	 *
	 * @param int|null $numOfVisitations Number of allowed visitations.
	 *
	 * @return static
	 */
	public function setNumOfVisitations(?int $numOfVisitations): static
	{
		$this->numOfVisitations = $numOfVisitations;

		return $this;
	}

	/**
	 * Get time period.
	 *
	 * @return string|null
	 */
	public function getTimePeriod(): ?string
	{
		return $this->timePeriod;
	}

	/**
	 * Set time period.
	 *
	 * @param string|null $timePeriod Time period.
	 *
	 * @return static
	 */
	public function setTimePeriod(?string $timePeriod): static
	{
		$this->timePeriod = $timePeriod;

		return $this;
	}

	/**
	 * Get is active.
	 *
	 * @return bool|null
	 */
	public function getIsActive(): ?bool
	{
		return $this->isActive;
	}

	/**
	 * Set is active.
	 *
	 * @param bool $isActive Is active.
	 *
	 * @return static
	 */
	public function setIsActive(bool $isActive): static
	{
		$this->isActive = $isActive;

		return $this;
	}

	/**
	 * Get status changed at datetime.
	 *
	 * @return \DateTimeImmutable|null
	 */
	public function getStatusChangedAt(): ?\DateTimeImmutable
	{
		return $this->statusChangedAt;
	}

	/**
	 * Set status changed at datetime.
	 *
	 * @param \DateTimeImmutable|null $statusChangedAt Status changed at.
	 *
	 * @return static
	 */
	public function setStatusChangedAt(?\DateTimeImmutable $statusChangedAt): static
	{
		$this->statusChangedAt = $statusChangedAt;

		return $this;
	}

	/**
	 * Get created datetime.
	 *
	 * @return \DateTimeImmutable|null
	 */
	public function getCreatedAt(): ?\DateTimeImmutable
	{
		return $this->createdAt;
	}

	/**
	 * Set created datetime.
	 *
	 * @param \DateTimeImmutable $createdAt Created at.
	 *
	 * @return static
	 */
	public function setCreatedAt(\DateTimeImmutable $createdAt): static
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * Get updated datetime.
	 *
	 * @return \DateTimeImmutable|null
	 */
	public function getUpdatedAt(): ?\DateTimeImmutable
	{
		return $this->updatedAt;
	}

	/**
	 * Set updated datetime.
	 *
	 * @param \DateTimeImmutable $updatedAt Updated at.
	 *
	 * @return static
	 */
	public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
	{
		$this->updatedAt = $updatedAt;

		return $this;
	}

	/**
	 * Get deleted datetime.
	 *
	 * @return \DateTimeImmutable|null
	 */
	public function getDeletedAt(): ?\DateTimeImmutable
	{
		return $this->deletedAt;
	}

	/**
	 * Set deleted datetime.
	 *
	 * @param \DateTimeImmutable|null $deletedAt Deleted at.
	 *
	 * @return static
	 */
	public function setDeletedAt(?\DateTimeImmutable $deletedAt): static
	{
		$this->deletedAt = $deletedAt;

		return $this;
	}
}
