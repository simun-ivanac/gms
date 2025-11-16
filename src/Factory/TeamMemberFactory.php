<?php

/**
 * Team Member Factory.
 */

declare(strict_types=1);

namespace App\Factory;

use App\Entity\TeamMember;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class TeamMemberFactory.
 */
final class TeamMemberFactory extends PersistentProxyObjectFactory
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
	 * Entity class.
	 *
	 * @return string
	 */
	public static function class(): string
	{
		return TeamMember::class;
	}

	/**
	 * Default values.
	 *
	 * @return array|callable
	 */
	protected function defaults(): array|callable
	{
		$gender = self::faker()->boolean(85) ? self::faker()->randomElement(['male', 'female']) : 'null';
		$firstName = $gender !== 'null' ? self::faker()->firstName($gender) : self::faker()->firstName();

		if ($gender === 'female') {
			$photoFilename = self::faker()->numberBetween(1, 20);
		} elseif ($gender === 'male') {
			$photoFilename = self::faker()->numberBetween(21, 40);
		} else {
			$photoFilename = self::faker()->numberBetween(1, 40);
		}

		return [
			'photoFilename' => 'user-photo-' . $photoFilename . '.png',
			'firstName' => $firstName,
			'lastName' => self::faker()->lastName(),
			'dateOfBirth' => self::faker()->dateTimeBetween('-60 years', '-16 years'),
			'gender' => $gender,
			'email' => self::faker()->email(),
			'phoneNumber' => self::faker()->e164PhoneNumber(),
			'pin' => self::faker()->numberBetween(10000000000, 99999999999),
		];
	}

	/**
	 * Initialize factory.
	 *
	 * @return static
	 */
	protected function initialize(): static
	{
		return $this->afterInstantiate(function (TeamMember $teamMember, array $parameters): void {
			if (isset($parameters['plainPassword'])) {
				$teamMember->setPassword(
					$this->passwordHasher->hashPassword($teamMember, $parameters['plainPassword'])
				);
			}

			if (isset($parameters['roles'])) {
				foreach ($parameters['roles'] as $role) {
					$teamMember->addTeamRoles($role);
				}
			}
		});
	}
}
