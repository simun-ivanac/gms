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
		return $this;
	}
}
