<?php

/**
 * Visitation Factory.
 */

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Visitation;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * Class VisitationFactory.
 */
final class VisitationFactory extends PersistentProxyObjectFactory
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
		return Visitation::class;
	}

	/**
	 * Default values.
	 *
	 * @return array|callable
	 */
	protected function defaults(): array|callable
	{
		return [
			'message' => self::faker()->text(75),
			'status' => self::faker()->boolean(85) ? 'success' : 'error',
			'timestamp' => \DateTimeImmutable::createFromMutable(self::faker()->dateTimeBetween('-45 days', '-2 days')),
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
