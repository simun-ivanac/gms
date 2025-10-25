<?php

/**
 * TeamMember Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TeamMemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TeamMember.
 */
#[ORM\Entity(repositoryClass: TeamMemberRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class TeamMember implements UserInterface, PasswordAuthenticatedUserInterface
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
	private ?string $photo = null;

	/**
	 * First name.
	 */
	#[ORM\Column(length: 255)]
	private ?string $firstName = null;

	/**
	 * Last name.
	 */
	#[ORM\Column(length: 255)]
	private ?string $lastName = null;

	/**
	 * Date of birth.
	 */
	#[ORM\Column(type: Types::DATE_MUTABLE)]
	private ?\DateTime $dateOfBirth = null;

	/**
	 * Gender.
	 */
	#[ORM\Column(length: 255, nullable: true)]
	private ?string $gender = null;

	/**
	 * Email.
	 */
	#[ORM\Column(length: 180)]
	private ?string $email = null;

	/**
	 * Phone number.
	 */
	#[ORM\Column(length: 255)]
	private ?string $phoneNumber = null;

	/**
	 * Personal identification number.
	 */
	#[ORM\Column(type: Types::BIGINT)]
	private ?string $pin = null;

	/**
	 * Is team member active.
	 */
	#[ORM\Column]
	private ?bool $isActive = null;

	/**
	 *The user roles.
	 */
	#[ORM\Column]
	private array $roles = [];

	/**
	 * The hashed password.
	 */
	#[ORM\Column]
	private ?string $password = null;

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
	 * @param string $email Email.
	 *
	 * @return static
	 */
	public function setEmail(string $email): static
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
	 * @param string $phoneNumber Phone number.
	 *
	 * @return static
	 */
	public function setPhoneNumber(string $phoneNumber): static
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

	/**
	 * Get roles.
	 *
	 * @see UserInterface
	 *
	 * @return array
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		$roles[] = 'staff';

		return array_unique($roles);
	}

	/**
	 * Set roles.
	 *
	 * @param list<string> $roles Roles.
	 *
	 * @return static
	 */
	public function setRoles(array $roles): static
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * Get password.
	 *
	 * @see PasswordAuthenticatedUserInterface
	 *
	 * @return string|null
	 */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/**
	 * Set password.
	 *
	 * @param string $password Password.
	 *
	 * @return static
	 */
	public function setPassword(string $password): static
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
	 *
	 * @return array
	 */
	public function __serialize(): array
	{
		$data = (array) $this;
		$data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);

		return $data;
	}

	/**
	 * Erase credentials - deprecated.
	 */
	#[\Deprecated]
	public function eraseCredentials(): void
	{
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 *
	 * @return string
	 */
	public function getUserIdentifier(): string
	{
		return (string) $this->email;
	}
}
