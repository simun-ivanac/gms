<?php

/**
 * Temporary Team Member Settings that are saved in cookie.
 */

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Class TeamMemberSettingsInCookie.
 */
class TeamMemberSettingsInCookie
{
	/**
	 * Fixed part of cookie name.
	 * Whole part: 'team_user_' . $teamMemberId.
	 */
	private const COOKIE_NAME = 'team_user_';

	/**
	 * Team Member cookie.
	 */
	private array $teamMemberCookie = [];

	/**
	 * Settings key saved in cookie.
	 */
	public const IS_EDITING_ALLOWED = 'isEditingAllowed';

	/**
	 * TeamMemberSettingsInCookie constructor.
	 *
	 * @param RequestStack $requestStack Request stack.
	 */
	public function __construct(private RequestStack $requestStack)
	{
	}

	/**
	 * Get cookie.
	 *
	 * @param int $teamMemberId Team Member ID.
	 *
	 * @return array
	 */
	public function getCookie(int $teamMemberId): array
	{
		if (!empty($this->teamMemberCookie)) {
			return $this->teamMemberCookie;
		}

		$request = $this->requestStack->getCurrentRequest();

		if (!$request) {
			return $this->teamMemberCookie;
		}

		$cookie = $request->cookies->get(self::COOKIE_NAME . $teamMemberId);

		$this->teamMemberCookie = $cookie ? (json_decode($cookie, true) ?? []) : [];

		return $this->teamMemberCookie;
	}

	/**
	 * Update cookie.
	 *
	 * @param int      $teamMemberId Team Member ID.
	 * @param array    $newData      New data.
	 * @param Response $response     Response.
	 *
	 * @return bool
	 */
	public function updateCookie(int $teamMemberId, array $newData, Response $response): bool
	{
		// If user editing is disallowed, delete cookie.
		if (!$newData[TeamMemberSettingsInCookie::IS_EDITING_ALLOWED]) {
			return $this->deleteCookie($teamMemberId, $response);
		}

		// Update cookie.
		$cookie = $this->getCookie($teamMemberId);
		$cookie = array_merge($this->teamMemberCookie, $newData);
		$this->teamMemberCookie = $cookie;

		$newCookie = Cookie::create(self::COOKIE_NAME . $teamMemberId, json_encode($cookie))
			->withExpires(strtotime('+1 hour'))
			->withPath('/')
			->withSecure(true)
			->withHttpOnly(true)
			->withSameSite('lax');

		$response->headers->setCookie($newCookie);

		return true;
	}

	/**
	 * Delete cookie.
	 *
	 * @param int      $teamMemberId Team Member ID.
	 * @param Response $response     Response.
	 *
	 * @return bool
	 */
	public function deleteCookie(int $teamMemberId, Response $response): bool
	{
		if (!empty($this->teamMemberCookie)) {
			$this->teamMemberCookie = [];
		}

		$response->headers->clearCookie(self::COOKIE_NAME . $teamMemberId, '/');

		return true;
	}

	/**
	 * Get cookie key value.
	 *
	 * @param int    $teamMemberId Team Member ID.
	 * @param string $key          Key.
	 * @param mixed  $default      Default value.
	 *
	 * @return mixed
	 */
	public function getCookieKey(int $teamMemberId, string $key, mixed $default = null): mixed
	{
		$cookie = $this->getCookie($teamMemberId);

		return $cookie[$key] ?? $default;
	}
}
