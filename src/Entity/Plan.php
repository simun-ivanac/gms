<?php

/**
 * Plan Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Plan.
 */
#[ORM\Entity(repositoryClass: PlanRepository::class)]
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
	#[Assert\Positive]
	private ?int $price = null;

	/**
	 * Currency.
	 */
	#[ORM\Column(length: 255)]
	#[Assert\Currency]
	private ?string $currency = null;

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
	#[Assert\NotBlank]
	#[Assert\Type('bool')]
	private ?bool $areVisitationsLimited = null;

	/**
	 * Number of visitations per week (if limited).
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Positive]
	private ?int $numOfVisitationsPerWeek = null;

	/**
	 * Is plan active.
	 */
	#[ORM\Column]
	#[Assert\Type('bool')]
	private ?bool $isActive = null;

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
	 * Get number of visitations per week.
	 *
	 * @return int|null
	 */
	public function getNumOfVisitationsPerWeek(): ?int
	{
		return $this->numOfVisitationsPerWeek;
	}

	/**
	 * Set number of visitations per week.
	 *
	 * @param int|null $numOfVisitationsPerWeek Number of visitations per week.
	 *
	 * @return static
	 */
	public function setNumOfVisitationsPerWeek(?int $numOfVisitationsPerWeek): static
	{
		$this->numOfVisitationsPerWeek = $numOfVisitationsPerWeek;

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
}
