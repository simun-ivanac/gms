<?php

/**
 * Team Member Role Fixtures.
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TeamMemberRole;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TeamMemberRoleFixtures.
 */
class TeamMemberRoleFixtures extends BaseFixture
{
	/**
	 * Roles.
	 */
	private array $roles = [
		'owner',
		'trainer',
		'receptionist',
		'staff',
	];

	/**
	 * Load team member role fixture.
	 *
	 * @param ObjectManager $manager Object manager.
	 *
	 * @return void
	 */
	public function loadData(ObjectManager $manager): void
	{
		$this->createMany(TeamMemberRole::class, count($this->roles), function (TeamMemberRole $role, $count) {
			$role->setRole($this->roles[$count]);
			$role->setPermissions($this->getRolePermissions($this->roles[$count]));
		});

		$manager->flush();
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
