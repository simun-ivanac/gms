<?php

/**
 * Team Member Role Entity.
 */

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TeamMemberRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
	 * Team Members.
	 *
	 * @var Collection<int, TeamMember>
	 */
	#[ORM\ManyToMany(targetEntity: TeamMember::class, mappedBy: 'teamRoles')]
	private Collection $teamMembers;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->teamMembers = new ArrayCollection();
	}

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

	/**
	 * Get team members.
	 *
	 * @return Collection<int, TeamMember>
	 */
	public function getTeamMembers(): Collection
	{
		return $this->teamMembers;
	}

	/**
	 * Add team member.
	 *
	 * @param TeamMember $teamMember Team member.
	 *
	 * @return static
	 */
	public function addTeamMember(TeamMember $teamMember): static
	{
		if (!$this->teamMembers->contains($teamMember)) {
			$this->teamMembers->add($teamMember);
			$teamMember->addTeamRoles($this);
		}

		return $this;
	}

	/**
	 * Remove team member.
	 *
	 * @param TeamMember $teamMember Team member.
	 *
	 * @return static
	 */
	public function removeTeamMember(TeamMember $teamMember): static
	{
		if ($this->teamMembers->removeElement($teamMember)) {
			$teamMember->removeTeamRoles($this);
		}

		return $this;
	}
}
