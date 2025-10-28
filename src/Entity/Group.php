<?php

/**
 * Group Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Group.
 */
#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
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
	 * Repeatability.
	 */
	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Assert\Choice(['recurring', 'one-session'])]
	private ?string $repeatability = null;

	/**
	 * Number of sessions per week.
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Positive]
	private ?int $numOfSessionsPerWeek = null;

	/**
	 * Payment frequency in days.
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Positive]
	private ?int $paymentFrequencyInDays = null;

	/**
	 * Is group active.
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
	 * Get repeatability.
	 *
	 * @return string|null
	 */
	public function getRepeatability(): ?string
	{
		return $this->repeatability;
	}

	/**
	 * Set repeatability.
	 *
	 * @param string $repeatability Repeatability.
	 *
	 * @return static
	 */
	public function setRepeatability(string $repeatability): static
	{
		$this->repeatability = $repeatability;

		return $this;
	}

	/**
	 * Get number of sessions per week.
	 *
	 * @return int|null
	 */
	public function getNumOfSessionsPerWeek(): ?int
	{
		return $this->numOfSessionsPerWeek;
	}

	/**
	 * Set number of sessions per week.
	 *
	 * @param int|null $numOfSessionsPerWeek Number of sessions per week.
	 *
	 * @return static
	 */
	public function setNumOfSessionsPerWeek(?int $numOfSessionsPerWeek): static
	{
		$this->numOfSessionsPerWeek = $numOfSessionsPerWeek;

		return $this;
	}

	/**
	 * Get payment frequency in days.
	 *
	 * @return int|null
	 */
	public function getPaymentFrequencyInDays(): ?int
	{
		return $this->paymentFrequencyInDays;
	}

	/**
	 * Set payment frequency in days.
	 *
	 * @param int|null $paymentFrequencyInDays Payment frequency in days.
	 *
	 * @return static
	 */
	public function setPaymentFrequencyInDays(?int $paymentFrequencyInDays): static
	{
		$this->paymentFrequencyInDays = $paymentFrequencyInDays;

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
