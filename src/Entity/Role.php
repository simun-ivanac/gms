<?php

/**
 * Role Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Role.
 */
#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
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
	private ?string $teamMemberRole = null;

	/**
	 * Permissions.
	 */
	#[ORM\Column(nullable: true)]
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
	public function getTeamMemberRole(): ?string
	{
		return $this->teamMemberRole;
	}

	/**
	 * Set team member role.
	 *
	 * @param string $teamMemberRole Team role.
	 *
	 * @return static
	 */
	public function setTeamMemberRole(string $teamMemberRole): static
	{
		$this->teamMemberRole = $teamMemberRole;

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
