<?php

/**
 * Membership Factory.
 */

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Membership;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * Class MembershipFactory.
 */
final class MembershipFactory extends PersistentProxyObjectFactory
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
		return Membership::class;
	}

	/**
	 * Default values.
	 *
	 * @return array|callable
	 */
	protected function defaults(): array|callable
	{
		return [];
	}

	/**
	 * Initialize factory.
	 *
	 * @return static
	 */
	protected function initialize(): static
	{
		return $this
			->beforeInstantiate(function (array $attributes): array {
				if (!isset($attributes['planDuration']) || !is_int($attributes['planDuration'])) {
					return $attributes;
				}

				// Set start and end date.
				$startDate = self::faker()->dateTimeBetween('-10 weeks', '-3 days');
				$endDate = (clone $startDate)->modify('+' . $attributes['planDuration'] . ' days');

				$attributes['startDate'] = $startDate;
				$attributes['endDate'] = $endDate;
				unset($attributes['planDuration']);

				return $attributes;
			});
	}
}
