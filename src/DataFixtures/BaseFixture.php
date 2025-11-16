<?php

/**
 * Base Fixture.
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\MemberFactory;
use App\Factory\TeamMemberFactory;
use App\Factory\TeamMemberRoleFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Zenstruck\Foundry\Object\Instantiator;

/**
 * Class BaseFixture.
 */
class BaseFixture extends Fixture
{
	/**
	 * Object manager.
	 *
	 * @var ObjectManager
	 */
	protected $manager;

	/**
	 * Load fixture.
	 *
	 * @param ObjectManager $manager Object manager.
	 *
	 * @return void
	 */
	public function load(ObjectManager $manager): void
	{
		// Set manager.
		$this->manager = $manager;

		// Populate database.
		$this->createMembers(30);
		$teamMemberRoles = $this->createRoles();
		$this->createOwner(['owner' => $teamMemberRoles['owner']]);
		$this->createTeamMembers(array_diff_key($teamMemberRoles, ['owner' => true]), 10);
	}

	/**
	 * Create members.
	 *
	 * @param int $number Number of members.
	 *
	 * @return void
	 */
	public function createMembers(int $number): void
	{
		MemberFactory::createMany($number);
	}

	/**
	 * Create roles.
	 *
	 * @return array
	 */
	public function createRoles(): array
	{
		$owner = TeamMemberRoleFactory::new()->withRole('owner')->create();
		$trainer = TeamMemberRoleFactory::new()->withRole('trainer')->create();
		$receptionist = TeamMemberRoleFactory::new()->withRole('receptionist')->create();
		$staff = TeamMemberRoleFactory::new()->withRole('staff')->create();

		return [
			'owner' => $owner,
			'trainer' => $trainer,
			'receptionist' => $receptionist,
			'staff' => $staff,
		];
	}

	/**
	 * Create owner.
	 *
	 * @param array $owner Owner role.
	 *
	 * @return void
	 */
	public function createOwner(array $owner): void
	{
		TeamMemberFactory::new()
			->instantiateWith(Instantiator::withConstructor()->allowExtra('plainPassword', 'roles'))
			->create([
				'photoFilename' => 'imgroot.png',
				'firstName' => 'I Am',
				'lastName' => 'Groot',
				'gender' => 'male',
				'email' => 'groot@avengers.example',
				'phoneNumber' => '+14875699103',
				'pin' => '12345678901',
				'plainPassword' => 'we are groot!',
				'is_active' => true,
				'roles' => [
					$owner['owner'],
				],
			]);
	}

	/**
	 * Create team members.
	 *
	 * @param array $secTeamRoles Secondary team member roles.
	 * @param int $number Number of team members.
	 *
	 * @return void
	 */
	public function createTeamMembers(array $secTeamRoles, int $number): void
	{
		TeamMemberFactory::new()
			->instantiateWith(Instantiator::withConstructor()->allowExtra('plainPassword', 'roles'))
			->many($number)
			->create(function () use ($secTeamRoles) {
				// Select random roles (allowed are: trainer, receptionist, staff, max 2 roles).
				$selectedRoles = [];
				$randomRoles = array_rand($secTeamRoles, rand(1, count($secTeamRoles) - 1));
				$randomRoles = is_array($randomRoles) ? array_unique($randomRoles) : [$randomRoles];

				foreach ($randomRoles as $role) {
					$selectedRoles[] = $secTeamRoles[$role];
				}

				return [
					'roles' => $selectedRoles,
				];
			});
	}
}
