<?php

/**
 * Plan Factory.
 */

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Plan;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * Class PlanFactory.
 */
final class PlanFactory extends PersistentProxyObjectFactory
{
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
		return Plan::class;
	}

	/**
	 * Default values.
	 *
	 * @return array|callable
	 */
	protected function defaults(): array|callable
	{
		$areVisitationsLimited = self::faker()->boolean();

		return [
			'name' => self::faker()->word(),
			'type' => self::faker()->randomElement(['one-time', 'recurring']),
			'price' => self::faker()->numberBetween(10, 150),
			'durationInDays' => self::faker()->numberBetween(1, 60),
			'areVisitationsLimited' => $areVisitationsLimited,
			'numOfVisitations' => $areVisitationsLimited ? self::faker()->numberBetween(1, 10) : null,
			'timePeriod' => $areVisitationsLimited ? self::faker()->randomElement(['daily', 'weekly', 'monthly', 'in-total']) : null,
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
}
