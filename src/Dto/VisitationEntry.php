<?php

/**
 * Visitation Entry Dto.
 */

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class VisitationEntry.
 */
final class VisitationEntry
{
	#[Assert\NotBlank]
	#[Assert\Choice(['member', 'teamMember'])]
	public string $userType;

	#[Assert\NotBlank]
	#[Assert\Positive]
	public int $userId;
}
