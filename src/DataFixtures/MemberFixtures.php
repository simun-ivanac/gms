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
	 */
	public function loadData(ObjectManager $manager): void
	{
		$this->createMany(Member::class, 30, function (Member $member) {
			$member->setPhotoFilename('user-photo-' . $this->faker->numberBetween(1, 20) . '.jpg');
			$member->setFirstName($this->faker->firstName);
			$member->setLastName($this->faker->lastName);
			$member->setDateOfBirth($this->faker->dateTimeBetween('-70 years', '-10 years'));

			// Increase chances of male/female gender.
			if ($this->faker->boolean(85)) {
				$member->setGender($this->faker->randomElement(['male', 'female']));
			} else {
				$member->setGender('null');
			}

			$member->setEmail($this->faker->email());
			$member->setPhoneNumber($this->faker->e164PhoneNumber());
			$member->setPin((string) $this->faker->numberBetween(10000000000, 99999999999));
			$member->setIsActive(false);
		});

		$manager->flush();
	}
}
