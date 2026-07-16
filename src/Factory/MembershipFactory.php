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
	 * Plan duration.
	 */
	private ?int $planDuration = null;

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
		$startDate = self::faker()->dateTimeBetween('-10 weeks', '-3 days');
		$endDate = $this->planDuration === null ? null : (clone $startDate)->modify("+{$this->planDuration} days");

		return [
			'startDate' => $startDate,
			'endDate' => $endDate,
		];
	}

	/**
	 * Set plan duration.
	 *
	 * @param int $planDuration Plan duration.
	 *
	 * @return static
	 */
	public function withPlanDuration(int $planDuration): self
	{
		$this->planDuration = $planDuration;

		return $this;
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
