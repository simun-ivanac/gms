<?php

/**
 * Team Member Role Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TeamMemberRoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TeamMemberRole.
 */
#[ORM\Entity(repositoryClass: TeamMemberRoleRepository::class)]
class TeamMemberRole
{
	/**
	 * Id.
	 */
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	/**
	 * Team Member Role.
	 */
	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Assert\Choice(['owner', 'trainer', 'receptionist', 'staff'])]
	private ?string $role = null;

	/**
	 * Permissions.
	 */
	#[ORM\Column(nullable: true)]
	#[Assert\Type('array')]
	private ?array $permissions = null;

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
	 * Get team member role.
	 *
	 * @return string|null
	 */
	public function getRole(): ?string
	{
		return $this->role;
	}

	/**
	 * Set team member role.
	 *
	 * @param string $role Team role.
	 *
	 * @return static
	 */
	public function setRole(string $role): static
	{
		$this->role = $role;

		return $this;
	}

	/**
	 * Get permissions.
	 *
	 * @return array|null
	 */
	public function getPermissions(): ?array
	{
		return $this->permissions;
	}

	/**
	 * Set permissions.
	 *
	 * @param array|null $permissions Permissions.
	 *
	 * @return static
	 */
	public function setPermissions(?array $permissions): static
	{
		$this->permissions = $permissions;

		return $this;
	}
}
