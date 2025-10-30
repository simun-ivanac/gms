<?php

/**
 * Twig Helpers.
 */

declare(strict_types=1);

namespace App\Service;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class TwigHelpers.
 */
class TwigHelpers extends AbstractExtension
{
	/**
	 * Get functions. Registers new Twig functions.
	 *
	 * @return array
	 */
	public function getFunctions(): array
	{
		return [
			new TwigFunction('truncateTextInMiddle', [$this, 'truncateTextInMiddle']),
		];
	}

	/**
	 * Get filters. Registers new Twig filters.
	 *
	 * @return array
	 */
	public function getFilters(): array
	{
		return [
			// Ex: new TwigFilter('newFilter', [$this, 'newFilterFunction']),.
		];
	}

	/**
	 * Truncate text in middle.
	 * Returns: 'abcdefghij...56789z.jpg'
	 *
	 * @param string $text      Text to truncate.
	 * @param int    $maxLength Max length.
	 * @param string $separator Separator.
	 *
	 * @return string
	 */
	public function truncateTextInMiddle(string $text, int $maxLength = 25, string $separator = '...')
	{
		if (!$text || empty(trim($text)) || $maxLength === 0) {
			return '';
		}

		$maxLength -= strlen($separator);
		$start = $maxLength / 2 ;
		$trunc = strlen($text) - $maxLength;

		return substr_replace($text, $separator, $start, $trunc);
	}
}
