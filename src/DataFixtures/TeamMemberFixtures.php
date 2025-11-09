<?php

/**
 * Team Member Fixtures.
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\TeamMember;
use App\Entity\TeamMemberRole;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class TeamMemberFixtures.
 */
class TeamMemberFixtures extends BaseFixture implements DependentFixtureInterface
{
	/**
	 * Password hasher.
	 *
	 * @var ObjectManager
	 */
	private UserPasswordHasherInterface $passwordHasher;

	/**
	 * Constructor.
	 *
	 * @param UserPasswordHasherInterface $passwordHasher User password hasher.
	 */
	public function __construct(UserPasswordHasherInterface $passwordHasher)
	{
		$this->passwordHasher = $passwordHasher;
	}

	/**
	 * Get dependencies.
	 *
	 * @return array
	 */
	public function getDependencies(): array
	{
		return [
			TeamMemberRoleFixtures::class,
		];
	}

	/**
	 * Load team member fixture.
	 *
	 * @param ObjectManager $manager Object manager.
	 *
	 * @return void
	 */
	public function loadData(ObjectManager $manager): void
	{
		$imGroot = new TeamMember();
		$imGroot->setPhotoFilename('imgroot.png');
		$imGroot->setFirstName('I Am');
		$imGroot->setLastName('Groot');
		$imGroot->setDateOfBirth($this->faker->dateTimeBetween('-200 years', '-100 years'));
		$imGroot->setGender('male');
		$imGroot->setEmail('groot@avengers.example');
		$imGroot->setPhoneNumber($this->faker->e164PhoneNumber());
		$imGroot->setPin((string) $this->faker->numberBetween(10000000000, 99999999999));
		$imGroot->setPassword($this->passwordHasher->hashPassword($imGroot, 'we are groot!'));
		$imGroot->addTeamRoles($this->getReference(TeamMemberRole::class . '_0', TeamMemberRole::class));

		$this->manager->persist($imGroot);

		$this->createMany(TeamMember::class, 30, function (TeamMember $teamMember) {
			// Increase chances of male/female gender.
			$gender = $this->faker->boolean(85) ? $this->faker->randomElement(['male', 'female']) : 'null';
			$firstName = $gender !== 'null' ? $this->faker->firstName($gender) : $this->faker->firstName();

			if ($gender === 'female') {
				$photoFilename = $this->faker->numberBetween(1, 20);
			} elseif ($gender === 'male') {
				$photoFilename = $this->faker->numberBetween(21, 40);
			} else {
				$photoFilename = $this->faker->numberBetween(1, 40);
			}

			$teamMember->setPhotoFilename('user-photo-' . $photoFilename . '.png');
			$teamMember->setFirstName($firstName);
			$teamMember->setLastName($this->faker->lastName);
			$teamMember->setDateOfBirth($this->faker->dateTimeBetween('-60 years', '-16 years'));
			$teamMember->setGender($gender);
			$teamMember->setEmail($this->faker->email());
			$teamMember->setPhoneNumber($this->faker->e164PhoneNumber());
			$teamMember->setPin((string) $this->faker->numberBetween(10000000000, 99999999999));
			$teamMember->addTeamRoles($this->getReference(TeamMemberRole::class . '_' . $this->faker->numberBetween(1, 3), TeamMemberRole::class));

			foreach ($this->getRandomRoles() as $role) {
				$teamMember->addTeamRoles($this->getReference(TeamMemberRole::class . '_' . $role, TeamMemberRole::class));
			}
		});

		$manager->flush();
	}

	/**
	 * Get array of random numbers representing team member role.
	 */
	public function getRandomRoles()
	{
		$numOfRoles = $this->faker->numberBetween(1, 2);
		$selectedRoles = [];

		for ($i = 1; $i < $numOfRoles; $i++) {
			$val = $this->faker->numberBetween(1, 3);

			if (!in_array($val, $selectedRoles)) {
				$selectedRoles[] = $val;
			}
		}

		return $selectedRoles;
	}
}
