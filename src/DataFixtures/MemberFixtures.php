<?php

/**
 * Member Fixtures.
 */

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Member;
use Doctrine\Persistence\ObjectManager;

/**
 * Class MemberFixtures.
 */
class MemberFixtures extends BaseFixture
{
	/**
	 * Load member fixture.
	 *
	 * @param ObjectManager $manager Object manager.
	 *
	 * @return void
	 */
	public function loadData(ObjectManager $manager): void
	{
		$this->createMany(Member::class, 30, function (Member $member) {
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

			$member->setPhotoFilename('user-photo-' . $photoFilename . '.png');
			$member->setFirstName($firstName);
			$member->setLastName($this->faker->lastName);
			$member->setDateOfBirth($this->faker->dateTimeBetween('-60 years', '-16 years'));
			$member->setGender($gender);
			$member->setEmail($this->faker->email());
			$member->setPhoneNumber($this->faker->e164PhoneNumber());
			$member->setPin((string) $this->faker->numberBetween(10000000000, 99999999999));
		});

		$manager->flush();
	}
}
