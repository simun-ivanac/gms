<?php

/**
 * Base Fixture.
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TeamMemberRole;
use App\Factory\MemberFactory;
use App\Factory\MembershipFactory;
use App\Factory\PlanFactory;
use App\Factory\TeamMemberFactory;
use App\Factory\TeamMemberRoleFactory;
use App\Factory\VisitationFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
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
	 * Faker.
	 *
	 * @var Generator
	 */
	private Generator $faker;

	/**
	 * Load fixture.
	 *
	 * @param ObjectManager $manager Object manager.
	 *
	 * @return void
	 */
	public function load(ObjectManager $manager): void
	{
		// Set manager and faker.
		$this->manager = $manager;
		$this->faker = Factory::create();

		// Create roles and team members.
		$teamMemberRoles = $this->createRoles();
		$this->createOwner($teamMemberRoles['owner']);
		$this->createTeamMembers(array_diff_key($teamMemberRoles, ['owner' => true]), 10);

		// Create members.
		$members = $this->createMembers(30);

		// Create plans.
		$plans = $this->createPlans(20);

		// Create memberships (add between 0-2 plans for each member).
		$this->createMemberships($members, $plans);
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
	 * @param TeamMemberRole $owner Owner role.
	 *
	 * @return void
	 */
	public function createOwner(TeamMemberRole $owner): void
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
				'roles' => [$owner],
				'visitations' => VisitationFactory::new()->many(10, 20),
			]);
	}

	/**
	 * Create team members.
	 *
	 * @param array $secTeamRoles Secondary team member roles.
	 * @param int   $number       Number of team members.
	 *
	 * @return void
	 */
	public function createTeamMembers(array $secTeamRoles, int $number): void
	{
		TeamMemberFactory::new()
			->instantiateWith(Instantiator::withConstructor()->allowExtra('plainPassword', 'roles'))
			->many($number)
			->create(function () use ($secTeamRoles) {
				// Select random roles (allowed are: trainer / receptionist / staff, max 2 roles).
				$randomRoles = array_rand($secTeamRoles, rand(1, 2));
				$selectedRoles = array_map(fn($role) => $secTeamRoles[$role], (array) $randomRoles);

				return [
					'roles' => $selectedRoles,
					'visitations' => VisitationFactory::new()->many(10, 20),
				];
			});
	}

	/**
	 * Create members.
	 *
	 * @param int $number Number of members.
	 *
	 * @return array
	 */
	public function createMembers(int $number): array
	{
		$members = MemberFactory::new()->many($number)->create([
			'visitations' => VisitationFactory::new()->many(10, 20),
		]);

		return $members;
	}

	/**
	 * Create plans.
	 *
	 * @param int $number Number of plans.
	 *
	 * @return array
	 */
	public function createPlans(int $number): array
	{
		$plans = PlanFactory::new()->many($number)->create();

		return $plans;
	}

	/**
	 * Create memberships (add between 0-2 plans for each member).
	 *
	 * @param array $members Members.
	 * @param array $plans   Plans.
	 *
	 * @return void
	 */
	public function createMemberships(array $members, array $plans): void
	{
		$avoidDuplicates = [];

		foreach ($members as $member) {
			MembershipFactory::new()
				->range(0, 2)
				->create(function () use ($member, $plans, &$avoidDuplicates) {
					$plan = $plans[array_rand($plans)];
					$memberId = $member->getId();

					// Regenerate plan if member already has this one.
					if (
						isset($avoidDuplicates[$memberId]) &&
						in_array($plan->getId(), $avoidDuplicates[$memberId])
					) {
						$plan = $plans[array_rand($plans)];
					}

					$avoidDuplicates[$memberId][] = $plan->getId();

					// Set start and end date.
					$startDate = $this->faker->dateTimeBetween('-4 weeks', '-5 days');
					$endDate = (clone $startDate)->modify('+' . $plan->getDurationInDays() . ' days');

					// Update member status to active if end date is in the future.
					if ($endDate > new \DateTime() && !$member->getIsActive()) {
						$member->setIsActive(true);
						$member->_save();
					}

					return [
						'memberSubscriber' => $member,
						'plan' => $plan,
						'startDate' => $startDate,
						'endDate' => $endDate,
					];
				});
		}
	}
}
