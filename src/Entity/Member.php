<?php

/**
 * Member Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MemberRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Gedmo\Mapping\Annotation\SoftDeleteable;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Member.
 */
#[ORM\Entity(repositoryClass: MemberRepository::class)]
#[ORM\Table(name: '`member`')]
#[SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: false)]
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
	#[Assert\Length(min: 1, max: 255)]
	private ?string $photoFilename = null;

	/**
	 * Temporary field for handling uploaded images/photos.
	 */
	#[Assert\Image(
		maxSize: '2M',
		mimeTypes: [
			'image/jpeg',
			'image/jpg',
			'image/png',
			'image/webp',
			'image/gif',
			'image/bmp',
			'image/svg',
			'image/tiff',
		],
	)]
	private ?File $photoFile = null;

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
	#[ORM\Column(nullable: true)]
	#[Assert\Type('bool')]
	private ?bool $isActive = null;

	/**
	 * Is member deactivated.
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Type('bool')]
	private ?bool $isDeactivated = null;

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
	 * Deactivated at.
	 */
	#[ORM\Column(nullable: true)]
	#[Timestampable(on: 'change', field: 'isDeactivated', value: true)]
	private ?\DateTimeImmutable $deactivatedAt = null;

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
	 * Get photo filename.
	 *
	 * @return string|null
	 */
	public function getPhotoFilename(): ?string
	{
		return $this->photoFilename;
	}

	/**
	 * Set photo filename.
	 *
	 * @param string|null $photo Photo Filename.
	 *
	 * @return static
	 */
	public function setPhotoFilename(?string $photoFilename): static
	{
		$this->photoFilename = $photoFilename;

		return $this;
	}

	/**
	 * Get photo file.
	 *
	 * @return File|null
	 */
	public function getPhotoFile(): ?File
	{
		return $this->photoFile;
	}

	/**
	 * Set photo file.
	 *
	 * @param File|null $file File.
	 *
	 * @return static
	 */
	public function setPhotoFile(?File $file): static
	{
		$this->photoFile = $file;

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
	public function setIsActive(?bool $isActive): static
	{
		$this->isActive = $isActive;

		return $this;
	}

	/**
	 * Get is deactivated.
	 *
	 * @return bool|null
	 */
	public function getIsDeactivated(): ?bool
	{
		return $this->isDeactivated;
	}

	/**
	 * Set is deactivated.
	 *
	 * @param bool $isDeactivated Is deactivated.
	 *
	 * @return static
	 */
	public function setIsDeactivated(?bool $isDeactivated): static
	{
		$this->isDeactivated = $isDeactivated;

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
	 * Get deactivated datetime.
	 *
	 * @return \DateTimeImmutable|null
	 */
	public function getDeactivatedAt(): ?\DateTimeImmutable
	{
		return $this->deactivatedAt;
	}

	/**
	 * Set deactivated datetime.
	 *
	 * @param \DateTimeImmutable $deactivatedAt Deactivated at.
	 *
	 * @return static
	 */
	public function setDeactivatedAt(\DateTimeImmutable $deactivatedAt): static
	{
		$this->deactivatedAt = $deactivatedAt;

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
