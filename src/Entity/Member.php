<?php

/**
 * Member Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Member.
 */
#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: '`member`')]
class Member
{
	/**
	 * Id.
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	/**
	 * Photo.
	 */
	#[ORM\Column(length: 255, nullable: true)]
	#[Assert\Image(maxSize: '2M')]
	#[Assert\Length(min: 1, max: 255)]
	private ?string $photo = null;

	/**
	 * First name.
	 */
	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Assert\Length(min: 2, max: 255)]
	private ?string $firstName = null;

	/**
	 * Last name.
	 */
	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Assert\Length(min: 2, max: 255)]
	private ?string $lastName = null;

	/**
	 * Date of birth.
	 */
	#[ORM\Column(type: Types::DATE_MUTABLE)]
	#[Assert\NotBlank]
	#[Assert\Type('\DateTimeInterface')]
	private ?\DateTime $dateOfBirth = null;

	/**
	 * Gender.
	 */
	#[ORM\Column(length: 255, nullable: true)]
	#[Assert\NotBlank]
	#[Assert\Choice(['male', 'female', 'null'])]
	private ?string $gender = null;

	/**
	 * Email.
	 */
	#[ORM\Column(length: 255, nullable: true)]
	#[Assert\Email]
	#[Assert\Length(min: 4, max: 255)]
	private ?string $email = null;

	/**
	 * Phone number.
	 * (+385981119999, no spaces, optional '+', 7-14 characters)
	 */
	#[ORM\Column(length: 255, nullable: true)]
	#[Assert\Regex("/^\\+?[1-9][0-9]{7,14}$/")]
	private ?string $phoneNumber = null;

	/**
	 * Personal identification number.
	 */
	#[ORM\Column(type: Types::BIGINT)]
	#[Assert\NotBlank]
	#[Assert\Positive]
	#[Assert\Length(exactly: 11)]
	private ?string $pin = null;

	/**
	 * Is member active.
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
	 * Get photo.
	 *
	 * @return string|null
	 */
	public function getPhoto(): ?string
	{
		return $this->photo;
	}

	/**
	 * Set photo.
	 *
	 * @param string|null $photo Photo.
	 *
	 * @return static
	 */
	public function setPhoto(?string $photo): static
	{
		$this->photo = $photo;

		return $this;
	}

	/**
	 * Get first name.
	 *
	 * @return string|null
	 */
	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	/**
	 * Set first name.
	 *
	 * @param string $firstName First name.
	 *
	 * @return static
	 */
	public function setFirstName(string $firstName): static
	{
		$this->firstName = $firstName;

		return $this;
	}

	/**
	 * Get last name.
	 *
	 * @return string|null
	 */
	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	/**
	 * Set last name.
	 *
	 * @param string $lastName Last name.
	 *
	 * @return static
	 */
	public function setLastName(string $lastName): static
	{
		$this->lastName = $lastName;

		return $this;
	}

	/**
	 * Get date of birth.
	 *
	 * @return \DateTime|null
	 */
	public function getDateOfBirth(): ?\DateTime
	{
		return $this->dateOfBirth;
	}

	/**
	 * Set date of birth.
	 *
	 * @param \DateTime $dateOfBirth Date of birth.
	 *
	 * @return static
	 */
	public function setDateOfBirth(\DateTime $dateOfBirth): static
	{
		$this->dateOfBirth = $dateOfBirth;

		return $this;
	}

	/**
	 * Get gender.
	 *
	 * @return string|null
	 */
	public function getGender(): ?string
	{
		return $this->gender;
	}

	/**
	 * Set gender.
	 *
	 * @param string|null $gender Gender.
	 *
	 * @return static
	 */
	public function setGender(?string $gender): static
	{
		$this->gender = $gender;

		return $this;
	}

	/**
	 * Get email.
	 *
	 * @return string|null
	 */
	public function getEmail(): ?string
	{
		return $this->email;
	}

	/**
	 * Set email.
	 *
	 * @param string|null $email Email.
	 *
	 * @return static
	 */
	public function setEmail(?string $email): static
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Get phone number.
	 *
	 * @return string|null
	 */
	public function getPhoneNumber(): ?string
	{
		return $this->phoneNumber;
	}

	/**
	 * Set phone number.
	 *
	 * @param string|null $phoneNumber Phone number.
	 *
	 * @return static
	 */
	public function setPhoneNumber(?string $phoneNumber): static
	{
		$this->phoneNumber = $phoneNumber;

		return $this;
	}

	/**
	 * Get personal identification number.
	 *
	 * @return string|null
	 */
	public function getPin(): ?string
	{
		return $this->pin;
	}

	/**
	 * Set personal identification number.
	 *
	 * @param string $pin Personal identification number.
	 *
	 * @return static
	 */
	public function setPin(string $pin): static
	{
		$this->pin = $pin;

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
