<?php

/**
 * Base Fixture.
 */

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

/**
 * Class BaseFixture.
 */
abstract class BaseFixture extends Fixture
{
	/**
	 * Faker.
	 *
	 * @var Generator
	 */
	protected $faker;

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
	 */
	public function load(ObjectManager $manager): void
	{
		$this->faker = Factory::create();
		$this->manager = $manager;
		$this->loadData($manager);
	}

	/**
	 * Persist data.
	 *
	 * @param ObjectManager $manager Object manager.
	 */
	abstract protected function loadData(ObjectManager $manager);

	/**
	 * Helper function for creating many entries at the same time.
	 *
	 * @param string $className Class name.
	 * @param int    $count     Count.
	 * @param callable $factory Factory.
	 */
	protected function createMany(string $className, int $count, callable $factory)
	{
		for ($i = 0; $i < $count; $i++) {
			$entity = new $className();
			$factory($entity, $i);

			$this->manager->persist($entity);

			// Store for usage later as App\Entity\ClassName_#COUNT#.
			$this->addReference($className . '_' . $i, $entity);
		}
	}
}
