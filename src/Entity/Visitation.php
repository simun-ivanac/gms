<?php

/**
 * Visitation Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\VisitationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation\Timestampable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Visitation.
 */
#[ORM\Entity(repositoryClass: VisitationRepository::class)]
class Visitation
{
	/**
	 * Id.
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	/**
	 * Timestamp.
	 */
	#[ORM\Column]
	#[Timestampable(on: 'create')]
	private ?\DateTimeImmutable $timestamp = null;

	/**
	 * Message.
	 */
	#[ORM\Column(type: Types::TEXT)]
	#[Assert\NotBlank]
	private ?string $message = null;

	/**
	 * Status.
	 */
	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	private ?string $status = null;

	/**
	 * Member.
	 */
	#[ORM\ManyToOne(inversedBy: 'visitations')]
	private ?Member $member = null;

	/**
	 * Team Member.
	 */
	#[ORM\ManyToOne(inversedBy: 'visitations')]
	private ?TeamMember $teamMember = null;

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
	 * Get timestamp.
	 *
	 * @return \DateTimeImmutable|null
	 */
	public function getTimestamp(): ?\DateTimeImmutable
	{
		return $this->timestamp;
	}

	/**
	 * Set timestamp.
	 *
	 * @param \DateTimeImmutable $timestamp Timestamp.
	 *
	 * @return static
	 */
	public function setTimestamp(\DateTimeImmutable $timestamp): static
	{
		$this->timestamp = $timestamp;

		return $this;
	}

	/**
	 * Get message.
	 *
	 * @return string|null
	 */
	public function getMessage(): ?string
	{
		return $this->message;
	}

	/**
	 * Set message.
	 *
	 * @param string $message Message.
	 *
	 * @return static
	 */
	public function setMessage(string $message): static
	{
		$this->message = $message;

		return $this;
	}

	/**
	 * Get status.
	 *
	 * @return string|null
	 */
	public function getStatus(): ?string
	{
		return $this->status;
	}

	/**
	 * Set status.
	 *
	 * @param string $status Status.
	 *
	 * @return static
	 */
	public function setStatus(string $status): static
	{
		$this->status = $status;

		return $this;
	}

	/**
	 * Get member.
	 *
	 * @return Member|null
	 */
	public function getMember(): ?Member
	{
		return $this->member;
	}

	/**
	 * Set member.
	 *
	 * @param Member|null $member Member.
	 *
	 * @return static
	 */
	public function setMember(?Member $member): static
	{
		$this->member = $member;

		return $this;
	}

	/**
	 * Get team member.
	 *
	 * @return TeamMember|null
	 */
	public function getTeamMember(): ?TeamMember
	{
		return $this->teamMember;
	}

	/**
	 * Set team member.
	 *
	 * @param TeamMember|null $teamMember Team member.
	 *
	 * @return static
	 */
	public function setTeamMember(?TeamMember $teamMember): static
	{
		$this->teamMember = $teamMember;

		return $this;
	}
}
