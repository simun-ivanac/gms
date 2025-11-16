<?php

/**
 * Team Member Role Factory.
 */

declare(strict_types=1);

namespace App\Factory;

use App\Entity\TeamMemberRole;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * Class TeamMemberRoleFactory.
 */
final class TeamMemberRoleFactory extends PersistentProxyObjectFactory
{
	/**
	 * Role.
	 */
	private ?string $role = null;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * Entity class.
	 *
	 * @return string
	 */
	public static function class(): string
	{
		return TeamMemberRole::class;
	}

	/**
	 * Default values.
	 *
	 * @return array|callable
	 */
	protected function defaults(): array|callable
	{
		return [
			'role' => $this->role,
			'permissions' => $this->getRolePermissions($this->role),
		];
	}

	/**
	 * Initialize factory.
	 *
	 * @return static
	 */
	protected function initialize(): static
	{
		return $this;
	}

	/**
	 * Set role.
	 *
	 * @param string $role Role.
	 *
	 * @return static
	 */
	public function withRole(string $role): self
	{
		$this->role = $role;

		return $this;
	}

	/**
	 * Get role permissions.
	 *
	 * @param string $role Role.
	 *
	 * @return array Permissions.
	 */
	private function getRolePermissions(string $role): array
	{
		$permissions = [
			'owner' => [
				'members' => [
					'add-member',
					'edit-member',
					'delete-member',
				],
				'team-members' => [
					'add-team-member',
					'edit-team-member',
					'delete-team-member',
				],
				'plans' => [
					'add-plan',
					'edit-plan',
					'delete-plan',
				],
				'groups' => [
					'add-group',
					'edit-group',
					'delete-group',
				],
				'managing-memberships' => [
					'add-plan-to-member',
					'remove-plan-from-member',
					'renew-plan-for-member',
					'add-group-to-member',
					'remove-group-from-member',
					'renew-group-for-member',
				],
				'managing-trainers' => [
					'assign-trainer-to-group',
					'remove-trainer-from-group',
				],
				'app-settings' => [
					'edit-app-settings',
				]
			],
			'trainer' => [
				'groups' => [
					'add-group',
					'edit-group',
					'delete-group',
				],
				'managing-memberships' => [
					'add-group-to-member',
					'remove-group-from-member',
					'renew-group-for-member',
				],
				'managing-trainers' => [
					'assign-trainer-to-group',
					'remove-trainer-from-group',
				],
			],
			'receptionist' => [
				'members' => [
					'add-member',
					'edit-member',
					'delete-member',
				],
				'managing-memberships' => [
					'add-plan-to-member',
					'remove-plan-from-member',
					'renew-plan-for-member',
					'add-group-to-member',
					'remove-group-from-member',
					'renew-group-for-member',
				],
				'managing-trainers' => [
					'assign-trainer-to-group',
					'remove-trainer-from-group',
				],
			],
			'staff' => [],
		];

		return $permissions[$role] ?? [];
	}
}
